<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold leading-tight text-[var(--portal-text-primary)]">{{ __('Departments') }}</h2>
        @if (auth()->user()?->isAdmin())
            <a href="{{ route('departments.create') }}" class="inline-flex items-center rounded-none bg-[var(--portal-primary)] px-4 py-2 text-sm font-semibold text-white transition hover:bg-[var(--portal-primary-hover)]">
                <i class="bi bi-plus-lg mr-2"></i>{{ __('Add Department') }}
            </a>
        @endif
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-4">
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($departments as $department)
                <div class="rounded-none border border-[var(--portal-border)] bg-[var(--portal-surface)] p-5">
                    <p class="text-base font-semibold text-[var(--portal-text-primary)]">{{ $department->name }}</p>
                    <p class="mt-2 text-sm text-[var(--portal-text-secondary)]">{{ $department->description ?: __('No description') }}</p>

                    @if (auth()->user()?->isAdmin())
                        <div class="mt-4 flex items-center gap-2">
                            <a href="{{ route('departments.edit', $department) }}" class="rounded-none border border-[var(--portal-border)] px-3 py-1.5 text-xs font-semibold text-[var(--portal-text-primary)] transition hover:border-[var(--portal-primary-hover)]">
                                <i class="bi bi-pencil-square mr-1"></i>{{ __('Edit') }}
                            </a>
                            <button
                                type="button"
                                x-data=""
                                x-on:click.prevent="$dispatch('open-modal', 'delete-department-{{ $department->id }}')"
                                class="rounded-none border border-red-700/60 px-3 py-1.5 text-xs font-semibold text-red-300 transition hover:bg-red-900/30"
                            >
                                <i class="bi bi-trash mr-1"></i>{{ __('Delete') }}
                            </button>
                        </div>

                        <x-delete-confirm-modal
                            :name="'delete-department-'.$department->id"
                            :action="route('departments.destroy', $department)"
                            :title="__('Are you sure you want to delete this department?')"
                            :message="__('This will permanently remove :name. This action cannot be undone.', ['name' => $department->name])"
                        />
                    @endif
                </div>
            @empty
                <div class="col-span-full rounded-none border border-[var(--portal-border)] bg-[var(--portal-surface)] p-8 text-center text-[var(--portal-text-secondary)]">
                    {{ __('No departments found.') }}
                </div>
            @endforelse
        </div>

        <div class="rounded-none border border-[var(--portal-border)] bg-[var(--portal-surface)] px-4 py-3 text-[var(--portal-text-secondary)]">
            {{ $departments->links() }}
        </div>
    </div>
</x-app-layout>
