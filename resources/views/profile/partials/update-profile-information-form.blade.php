@props(['user'])

<section>
    <header>
        <h2 class="text-lg font-medium text-white">
            {{ __('Personal Details') }}
        </h2>

        <p class="mt-1 text-sm text-[#A1A1AA]">
            {{ __('Update your personal information and preferred work mode.') }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            <div>
                <x-input-label for="phone_number" :value="__('Phone Number')" />
                <x-text-input id="phone_number" name="phone_number" type="text" class="mt-1 block w-full" :value="old('phone_number', $user->phone_number)" autocomplete="tel" />
                <x-input-error class="mt-2" :messages="$errors->get('phone_number')" />
            </div>
        </div>

        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
                <x-input-error class="mt-2" :messages="$errors->get('email')" />

                @if (\Illuminate\Support\Facades\Route::has('verification.send') && $user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div>
                        <p class="mt-2 text-sm text-[#A1A1AA]">
                            {{ __('Your email address is unverified.') }}

                            <button form="send-verification" class="underline text-sm text-[#A1A1AA] hover:text-white rounded-none focus:outline-none focus:ring-2 focus:ring-[#DC2626] focus:ring-offset-2 focus:ring-offset-[#111111]">
                                {{ __('Click here to re-send the verification email.') }}
                            </button>
                        </p>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 font-medium text-sm text-green-400">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>

            <div>
                <x-input-label for="work_mode" :value="__('Work Mode')" />
                <select id="work_mode" name="work_mode" class="mt-1 block w-full rounded-none border border-[#1F1F1F] bg-[#0A0A0A] px-3 py-2 text-sm text-white focus:border-[#DC2626] focus:ring-[#DC2626]">
                    <option value="">Select work mode</option>
                    @foreach (['Remote', 'On Site', 'Hybrid'] as $mode)
                        <option value="{{ $mode }}" @selected(old('work_mode', $user->work_mode) === $mode)>{{ $mode }}</option>
                    @endforeach
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('work_mode')" />
            </div>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save Changes') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-[#A1A1AA]"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
