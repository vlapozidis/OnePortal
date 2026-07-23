<x-app-layout>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

    <x-slot name="header">
        <h2 class="text-2xl font-semibold leading-tight text-white">{{ __('Submit Leave Request') }}</h2>
    </x-slot>

    <div class="mx-auto max-w-4xl">
        <div class="rounded-none border border-[#1F1F1F] bg-[#111111] p-6">
            <form method="POST" action="{{ route('leave-requests.store') }}" class="space-y-5">
                @csrf

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <x-input-label for="department_id" :value="__('Department')" />
                        <select id="department_id" name="department_id" class="mt-2 block w-full rounded-none border border-[#1F1F1F] bg-[#0A0A0A] text-white focus:border-[#DC2626] focus:ring-[#DC2626]" required>
                            <option value="">{{ __('Select department') }}</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->id }}" @selected((int) old('department_id', $userDepartmentId) === $department->id)>
                                    {{ $department->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('department_id')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="start_date" :value="__('Start Date')" />
                        <x-text-input id="start_date" name="start_date" type="text" class="js-date-picker mt-2 block w-full" :value="old('start_date')" autocomplete="off" required />
                        <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="end_date" :value="__('End Date')" />
                        <x-text-input id="end_date" name="end_date" type="text" class="js-date-picker mt-2 block w-full" :value="old('end_date')" autocomplete="off" required />
                        <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                    </div>
                </div>

                <div>
                    <x-input-label for="reason" :value="__('Reason')" />
                    <textarea id="reason" name="reason" rows="5" class="mt-2 block w-full rounded-none border border-[#1F1F1F] bg-[#0A0A0A] text-white placeholder:text-[#71717A] focus:border-[#DC2626] focus:ring-[#DC2626]" placeholder="{{ __('Write the reason for your leave') }}" required>{{ old('reason') }}</textarea>
                    <x-input-error :messages="$errors->get('reason')" class="mt-2" />
                </div>

                <div class="flex items-center justify-end gap-2">
                    <a href="{{ route('leave-requests.index') }}" class="rounded-none border border-[#1F1F1F] px-4 py-2 text-sm text-[#A1A1AA] transition hover:text-white">
                        <i class="bi bi-x-lg mr-1"></i>{{ __('Cancel') }}
                    </a>
                    <x-primary-button><i class="bi bi-send mr-2"></i>{{ __('Submit Request') }}</x-primary-button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');

            const startPicker = flatpickr(startDateInput, {
                dateFormat: 'Y-m-d',
                minDate: 'today',
                allowInput: false,
            });

            flatpickr(endDateInput, {
                dateFormat: 'Y-m-d',
                minDate: startDateInput.value || 'today',
                allowInput: false,
            });

            startDateInput.addEventListener('change', function () {
                endDateInput._flatpickr.set('minDate', this.value || 'today');
            });
        });
    </script>
</x-app-layout>
