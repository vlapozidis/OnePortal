<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold leading-tight text-white">{{ __('Add User') }}</h2>
    </x-slot>

    <div class="mx-auto max-w-3xl">
        <div class="rounded-none border border-[#1F1F1F] bg-[#111111] p-6">
            <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-5">
                @csrf

                <div>
                    <x-input-label for="name" :value="__('Name')" />
                    <x-text-input id="name" name="name" type="text" class="mt-2 block w-full" :value="old('name')" required autofocus />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" name="email" type="email" class="mt-2 block w-full" :value="old('email')" required />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="password" :value="__('Password')" />
                    <x-text-input id="password" name="password" type="password" class="mt-2 block w-full" required />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                    <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-2 block w-full" required />
                </div>

                <div>
                    <x-input-label for="role" :value="__('Role')" />
                    <select id="role" name="role" class="mt-2 block w-full rounded-none border border-[#1F1F1F] bg-[#0A0A0A] text-white focus:border-[#DC2626] focus:ring-[#DC2626]" required>
                        <option value="employee" @selected(old('role') === 'employee')>{{ __('Employee') }}</option>
                        <option value="admin" @selected(old('role') === 'admin')>{{ __('Admin') }}</option>
                    </select>
                    <x-input-error :messages="$errors->get('role')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="department_id" :value="__('Department')" />
                    <select id="department_id" name="department_id" class="mt-2 block w-full rounded-none border border-[#1F1F1F] bg-[#0A0A0A] text-white focus:border-[#DC2626] focus:ring-[#DC2626]">
                        <option value="">{{ __('No department') }}</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}" @selected((int) old('department_id') === $department->id)>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('department_id')" class="mt-2" />
                </div>

                <div class="flex items-center justify-end gap-2">
                    <a href="{{ route('admin.users.index') }}" class="rounded-none border border-[#1F1F1F] px-4 py-2 text-sm text-[#A1A1AA] transition hover:text-white">
                        <i class="bi bi-x-lg mr-1"></i>{{ __('Cancel') }}
                    </a>
                    <x-primary-button><i class="bi bi-check-lg mr-2"></i>{{ __('Create User') }}</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
