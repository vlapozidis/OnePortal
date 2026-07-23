@props(['name', 'action', 'title', 'message'])

<x-modal :name="$name" focusable>
    <form method="POST" action="{{ $action }}" class="p-6">
        @csrf
        @method('DELETE')

        <h2 class="text-lg font-medium text-gray-900">{{ $title }}</h2>

        <p class="mt-1 text-sm text-gray-600">{{ $message }}</p>

        <div class="mt-6 flex justify-end gap-3">
            <x-secondary-button x-on:click="$dispatch('close')">
                <i class="bi bi-x-lg mr-1"></i>{{ __('Cancel') }}
            </x-secondary-button>

            <x-danger-button>
                <i class="bi bi-trash mr-2"></i>{{ __('Delete') }}
            </x-danger-button>
        </div>
    </form>
</x-modal>
