<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\EntraIDUserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;

class EntraIDController extends Controller
{
    /**
     * Constructor.
     */
    public function __construct(
        protected EntraIDUserService $entraIDUserService,
    ) {}

    /**
     * Show the login page.
     */
    public function show()
    {
        return view('auth.login');
    }

    /**
     * Redirect user to Microsoft Entra ID login.
     */
    public function redirect()
    {
        return Socialite::driver('microsoft')
            ->scopes([
                'openid',
                'profile',
                'email',
                'User.Read',
            ])
            ->redirect();
    }

    /**
     * Handle callback from Microsoft Entra ID.
     */
    public function callback(Request $request)
    {
        try {
            $entraUser = Socialite::driver('microsoft')->user();
        } catch (InvalidStateException $e) {
            return redirect()->route('login')->withErrors([
                'message' => __('Invalid login state. Please try again.'),
            ]);
        }

        // Get or create user
        $user = $this->entraIDUserService->findOrCreateUser($entraUser);

        if (! $user) {
            return redirect()->route('login')->withErrors([
                'message' => __('Unable to create or update user account.'),
            ]);
        }

        // Log the user in
        Auth::login($user, remember: true);

        $request->session()->regenerate();

        if ($user->isAdmin()) {
            return redirect()->intended('/control-panel');
        }

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Logout user and optionally revoke tokens.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', __('Logged out successfully.'));
    }
}
