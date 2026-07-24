<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold leading-tight text-[var(--portal-text-primary)]">{{ __('Edit Department') }}</h2>
    </x-slot>

    <div class="mx-auto max-w-3xl">
        <div class="rounded-none border border-[var(--portal-border)] bg-[var(--portal-surface)] p-6">
            <form method="POST" action="{{ route('departments.update', $department) }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <x-input-label for="name" :value="__('Department Name')" />
                    <x-text-input id="name" name="name" type="text" class="mt-2 block w-full" :value="old('name', $department->name)" required />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="description" :value="__('Description')" />
                    <textarea id="description" name="description" rows="4" class="mt-2 block w-full rounded-none border border-[var(--portal-border)] bg-[var(--portal-bg)] text-[var(--portal-text-primary)] placeholder:text-[var(--portal-text-secondary)] focus:border-[var(--portal-primary)] focus:ring-[var(--portal-primary)]">{{ old('description', $department->description) }}</textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>

                <div class="flex items-center justify-end gap-2">
                    <a href="{{ route('departments.index') }}" class="rounded-none border border-[var(--portal-border)] px-4 py-2 text-sm text-[var(--portal-text-secondary)] transition hover:text-[var(--portal-text-primary)]">
                        <i class="bi bi-x-lg mr-1"></i>{{ __('Cancel') }}
                    </a>
                    <x-primary-button><i class="bi bi-check-lg mr-2"></i>{{ __('Update Department') }}</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
