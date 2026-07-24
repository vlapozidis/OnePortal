@props(['user'])

<section>
    <header>
        <h2 class="text-lg font-medium text-[var(--portal-text-primary)]">
            {{ __('Profile Photo') }}
        </h2>

        <p class="mt-1 text-sm text-[var(--portal-text-secondary)]">
            {{ __('Update the photo shown next to your name across the portal.') }}
        </p>

        @if ($user->isEntraConnected())
            <x-entra-badge :user="$user" class="mt-3" />
        @endif
    </header>

    <div class="mt-6 flex flex-wrap items-center gap-6">
        <x-user-avatar :user="$user" size="xl" />

        <div class="flex flex-col gap-3">
            <form method="post" action="{{ route('profile.avatar.update') }}" enctype="multipart/form-data" class="flex flex-wrap items-center gap-3">
                @csrf

                <input
                    id="avatar"
                    name="avatar"
                    type="file"
                    accept="image/png,image/jpeg,image/webp"
                    onchange="this.form.requestSubmit()"
                    class="block w-full max-w-xs text-sm text-[var(--portal-text-secondary)] file:mr-3 file:rounded-none file:border file:border-[var(--portal-border)] file:bg-[var(--portal-bg)] file:px-3 file:py-1.5 file:text-sm file:font-semibold file:text-[var(--portal-text-primary)] hover:file:border-[var(--portal-primary)]"
                />

                <x-input-error class="w-full" :messages="$errors->get('avatar')" />
            </form>

            @if ($user->avatar_path)
                <form method="post" action="{{ route('profile.avatar.destroy') }}">
                    @csrf
                    @method('delete')
                    <x-secondary-button type="submit">
                        <i class="bi bi-trash mr-2"></i>{{ __('Remove Photo') }}
                    </x-secondary-button>
                </form>
            @endif

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-[var(--portal-text-secondary)]"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </div>
</section>
