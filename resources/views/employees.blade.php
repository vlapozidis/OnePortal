<x-app-layout>
	<x-slot name="header">
		<h2 class="text-2xl font-semibold leading-tight text-[var(--portal-text-primary)]">{{ __('Employees Directory') }}</h2>
	</x-slot>

	<div class="mx-auto max-w-7xl space-y-4">
		<form
			method="GET"
			action="{{ route('employees.index') }}"
			id="employee-filters"
			class="flex flex-wrap items-center gap-3 rounded-none border border-[var(--portal-border)] bg-[var(--portal-surface)] p-4"
		>
			<div class="relative">
				<i class="bi bi-search pointer-events-none absolute left-3 top-1/2 -translate-y-1/2 text-[var(--portal-text-secondary)]"></i>
				<input
					id="search"
					name="search"
					type="text"
					value="{{ $filters['search'] }}"
					placeholder="{{ __('Search by name or email') }}"
					class="w-56 rounded-none border border-[var(--portal-border)] bg-[var(--portal-bg)] py-1.5 pl-9 pr-3 text-sm text-[var(--portal-text-primary)] placeholder:text-[var(--portal-text-secondary)] focus:border-[var(--portal-primary)] focus:ring-[var(--portal-primary)]"
				>
			</div>

			<select
				id="department_id"
				name="department_id"
				onchange="document.getElementById('employee-filters').submit()"
				class="w-40 rounded-none border border-[var(--portal-border)] bg-[var(--portal-bg)] px-3 py-1.5 text-sm text-[var(--portal-text-primary)] focus:border-[var(--portal-primary)] focus:ring-[var(--portal-primary)]"
			>
				<option value="">{{ __('All Departments') }}</option>
				@foreach ($departments as $department)
					<option value="{{ $department->id }}" @selected((int) $filters['department_id'] === $department->id)>
						{{ $department->name }}
					</option>
				@endforeach
			</select>

			<select
				id="work_mode"
				name="work_mode"
				onchange="document.getElementById('employee-filters').submit()"
				class="w-40 rounded-none border border-[var(--portal-border)] bg-[var(--portal-bg)] px-3 py-1.5 text-sm text-[var(--portal-text-primary)] focus:border-[var(--portal-primary)] focus:ring-[var(--portal-primary)]"
			>
				<option value="">{{ __('All Work Modes') }}</option>
				@foreach ($workModes as $mode)
					<option value="{{ $mode }}" @selected($filters['work_mode'] === $mode)>
						{{ __($mode) }}
					</option>
				@endforeach
			</select>

			@if ($filters['search'] !== '' || $filters['department_id'] > 0 || $filters['work_mode'] !== '')
				<a href="{{ route('employees.index') }}" class="text-sm text-[var(--portal-text-secondary)] transition hover:text-[var(--portal-text-primary)]">
					<i class="bi bi-x-circle mr-1"></i>{{ __('Clear filters') }}
				</a>
			@endif
		</form>

		<script>
			(() => {
				const input = document.getElementById('search');
				let timer;
				input?.addEventListener('input', () => {
					clearTimeout(timer);
					timer = setTimeout(() => document.getElementById('employee-filters').submit(), 500);
				});
			})();
		</script>

		<div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
			@forelse ($employees as $employee)
				<div class="rounded-none border border-[var(--portal-border)] bg-[var(--portal-surface)] p-5">
					<div class="flex items-center gap-3">
						<x-user-avatar :user="$employee" size="md" />
						<div class="min-w-0">
							<p class="truncate text-base font-semibold text-[var(--portal-text-primary)]">{{ $employee->name }}</p>
							<p class="truncate text-sm text-[var(--portal-text-secondary)]">{{ $employee->email }}</p>
						</div>
					</div>

					@if ($employee->isEntraConnected())
						<x-entra-badge :user="$employee" class="mt-3" />
					@endif

					<div class="mt-4 flex flex-wrap gap-2 text-xs">
						<span class="rounded-none border border-[var(--portal-border)] px-3 py-1 text-[var(--portal-text-secondary)]">
							{{ $employee->department?->name ?? __('No department') }}
						</span>
						<span class="rounded-none border border-[var(--portal-border)] px-3 py-1 text-[var(--portal-text-secondary)]">
							{{ $employee->work_mode ? __($employee->work_mode) : __('No work mode') }}
						</span>
					</div>
				</div>
			@empty
				<div class="col-span-full rounded-none border border-[var(--portal-border)] bg-[var(--portal-surface)] p-8 text-center text-[var(--portal-text-secondary)]">
					{{ __('No employees found for the selected filters.') }}
				</div>
			@endforelse
		</div>

		<div class="rounded-none border border-[var(--portal-border)] bg-[var(--portal-surface)] px-4 py-3 text-[var(--portal-text-secondary)]">
			{{ $employees->links() }}
		</div>
	</div>
</x-app-layout>
