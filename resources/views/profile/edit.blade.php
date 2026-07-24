<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold leading-tight text-[var(--portal-text-primary)]">
            {{ __('Settings') }}
        </h2>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-6">
        <div class="rounded-none border border-[var(--portal-border)] bg-[var(--portal-surface)] p-4 shadow sm:p-8">
            <div class="max-w-2xl">
                @include('profile.partials.update-avatar-form')
            </div>
        </div>

        <div class="rounded-none border border-[var(--portal-border)] bg-[var(--portal-surface)] p-4 shadow sm:p-8">
            <div class="max-w-2xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="rounded-none border border-[var(--portal-border)] bg-[var(--portal-surface)] p-4 shadow sm:p-8">
            <div class="max-w-2xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="rounded-none border border-[var(--portal-border)] bg-[var(--portal-surface)] p-4 shadow sm:p-8">
            <div class="max-w-2xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
