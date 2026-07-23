<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold leading-tight text-white">{{ __('Edit Department') }}</h2>
    </x-slot>

    <div class="mx-auto max-w-3xl">
        <div class="rounded-none border border-[#1F1F1F] bg-[#111111] p-6">
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
                    <textarea id="description" name="description" rows="4" class="mt-2 block w-full rounded-none border border-[#1F1F1F] bg-[#0A0A0A] text-white placeholder:text-[#71717A] focus:border-[#DC2626] focus:ring-[#DC2626]">{{ old('description', $department->description) }}</textarea>
                    <x-input-error :messages="$errors->get('description')" class="mt-2" />
                </div>

                <div class="flex items-center justify-end gap-2">
                    <a href="{{ route('departments.index') }}" class="rounded-none border border-[#1F1F1F] px-4 py-2 text-sm text-[#A1A1AA] transition hover:text-white">
                        <i class="bi bi-x-lg mr-1"></i>{{ __('Cancel') }}
                    </a>
                    <x-primary-button><i class="bi bi-check-lg mr-2"></i>{{ __('Update Department') }}</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
