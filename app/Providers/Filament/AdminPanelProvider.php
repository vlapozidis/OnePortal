<?php

namespace App\Providers\Filament;

use App\Filament\Pages\EditProfile;
use App\Http\Middleware\RedirectAdminPasswordChangeToProfile;
use App\Http\Middleware\SetLocale;
use Filament\Actions\Action;
use Filament\Actions\View\ActionsIconAlias;
use Filament\Auth\Http\Responses\Contracts\LogoutResponse;
use Filament\Support\Facades\FilamentIcon;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\HtmlString;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function register(): void
    {
        parent::register();

        $this->app->bind(LogoutResponse::class, function () {
            return new class implements LogoutResponse
            {
                public function toResponse($request)
                {
                    return redirect()->route('login');
                }
            };
        });
    }

    public function boot(): void
    {
        FilamentIcon::register([
            ActionsIconAlias::ACTION_GROUP => 'bi-three-dots',
            ActionsIconAlias::CREATE_ACTION_GROUPED => 'bi-plus-lg',
            ActionsIconAlias::DELETE_ACTION => 'bi-trash',
            ActionsIconAlias::DELETE_ACTION_GROUPED => 'bi-trash',
            ActionsIconAlias::DELETE_ACTION_MODAL => 'bi-exclamation-triangle',
            ActionsIconAlias::DETACH_ACTION => 'bi-x-circle',
            ActionsIconAlias::DETACH_ACTION_MODAL => 'bi-exclamation-triangle',
            ActionsIconAlias::DISSOCIATE_ACTION => 'bi-x-circle',
            ActionsIconAlias::DISSOCIATE_ACTION_MODAL => 'bi-exclamation-triangle',
            ActionsIconAlias::EDIT_ACTION => 'bi-pencil-square',
            ActionsIconAlias::EDIT_ACTION_GROUPED => 'bi-pencil-square',
            ActionsIconAlias::EXPORT_ACTION_GROUPED => 'bi-download',
            ActionsIconAlias::FORCE_DELETE_ACTION => 'bi-trash3-fill',
            ActionsIconAlias::FORCE_DELETE_ACTION_GROUPED => 'bi-trash3-fill',
            ActionsIconAlias::FORCE_DELETE_ACTION_MODAL => 'bi-exclamation-triangle',
            ActionsIconAlias::IMPORT_ACTION_GROUPED => 'bi-upload',
            ActionsIconAlias::MODAL_CONFIRMATION => 'bi-question-circle',
            ActionsIconAlias::REPLICATE_ACTION => 'bi-copy',
            ActionsIconAlias::REPLICATE_ACTION_GROUPED => 'bi-copy',
            ActionsIconAlias::RESTORE_ACTION => 'bi-arrow-counterclockwise',
            ActionsIconAlias::RESTORE_ACTION_GROUPED => 'bi-arrow-counterclockwise',
            ActionsIconAlias::RESTORE_ACTION_MODAL => 'bi-arrow-counterclockwise',
            ActionsIconAlias::VIEW_ACTION => 'bi-eye',
            ActionsIconAlias::VIEW_ACTION_GROUPED => 'bi-eye',
        ]);
    }

    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('control-panel')
            ->favicon(versioned_asset('images/favicon.png'))
            ->profile(EditProfile::class, isSimple: false)
            ->brandName(config('app.name', 'OnePortal').' Control Panel')
            ->brandLogo(fn () => new HtmlString(
                '<div style="display:flex;align-items:center;gap:0.5rem;height:2rem">'
                .'<img src="'.e(versioned_asset('images/logofree.png')).'" alt="'.e(config('app.name', 'OnePortal')).'" style="height:2rem;width:auto;display:block" />'
                .'<span style="font-size:1rem;font-weight:700;line-height:1;white-space:nowrap">'.e(config('app.name', 'OnePortal').' Control Panel').'</span>'
                .'</div>'
            ))
            ->brandLogoHeight('2rem')
            ->colors([
                'primary' => Color::Red,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                SetLocale::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                RedirectAdminPasswordChangeToProfile::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->userMenuItems($this->getLanguageMenuItems())
            ->renderHook(
                PanelsRenderHook::HEAD_END,
                fn (): string => <<<'HTML'
                    <style>
                        * {
                            border-radius: 0 !important;
                        }
                        .fi-circular,
                        .fi-avatar,
                        .fi-user-avatar,
                        .fi-toggle,
                        .fi-toggle * {
                            border-radius: 9999px !important;
                        }
                    </style>
                    HTML,
            );
    }

    /**
     * @return array<string, Action>
     */
    private function getLanguageMenuItems(): array
    {
        $locales = ['en' => 'English', 'el' => 'Ελληνικά'];
        $current = app()->getLocale();
        $items = [];

        foreach ($locales as $code => $label) {
            $items['locale_'.$code] = Action::make('locale_'.$code)
                ->label($label)
                ->icon('bi-translate')
                ->color($current === $code ? 'primary' : 'gray')
                ->url(fn (): string => route('locale.switch', $code));
        }

        return $items;
    }
}
