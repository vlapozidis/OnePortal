<x-app-layout>
	<x-slot name="header">
		<h2 class="text-2xl font-semibold leading-tight text-white">Employees Directory</h2>
	</x-slot>

	<div class="mx-auto max-w-7xl space-y-4">
		<form
			method="GET"
			action="{{ route('employees.index') }}"
			id="employee-filters"
			class="flex flex-wrap items-center gap-3 rounded-none border border-[#1F1F1F] bg-[#111111] p-4"
		>
			<input
				id="search"
				name="search"
				type="text"
				value="{{ $filters['search'] }}"
				placeholder="Search by name or email"
				class="w-56 rounded-none border border-[#1F1F1F] bg-[#0A0A0A] px-3 py-1.5 text-sm text-white placeholder:text-[#71717A] focus:border-[#DC2626] focus:ring-[#DC2626]"
			>

			<select
				id="department_id"
				name="department_id"
				onchange="document.getElementById('employee-filters').submit()"
				class="w-40 rounded-none border border-[#1F1F1F] bg-[#0A0A0A] px-3 py-1.5 text-sm text-white focus:border-[#DC2626] focus:ring-[#DC2626]"
			>
				<option value="">All Departments</option>
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
				class="w-40 rounded-none border border-[#1F1F1F] bg-[#0A0A0A] px-3 py-1.5 text-sm text-white focus:border-[#DC2626] focus:ring-[#DC2626]"
			>
				<option value="">All Work Modes</option>
				@foreach ($workModes as $mode)
					<option value="{{ $mode }}" @selected($filters['work_mode'] === $mode)>
						{{ $mode }}
					</option>
				@endforeach
			</select>

			@if ($filters['search'] !== '' || $filters['department_id'] > 0 || $filters['work_mode'] !== '')
				<a href="{{ route('employees.index') }}" class="text-sm text-[#A1A1AA] transition hover:text-white">
					Clear filters
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
				<div class="rounded-none border border-[#1F1F1F] bg-[#111111] p-5">
					<p class="truncate text-base font-semibold text-white">{{ $employee->name }}</p>
					<p class="truncate text-sm text-[#A1A1AA]">{{ $employee->email }}</p>

					<div class="mt-4 flex flex-wrap gap-2 text-xs">
						<span class="rounded-none border border-[#1F1F1F] px-3 py-1 text-[#A1A1AA]">
							{{ $employee->department?->name ?? 'No department' }}
						</span>
						<span class="rounded-none border border-[#1F1F1F] px-3 py-1 text-[#A1A1AA]">
							{{ $employee->work_mode ?? 'No work mode' }}
						</span>
					</div>
				</div>
			@empty
				<div class="col-span-full rounded-none border border-[#1F1F1F] bg-[#111111] p-8 text-center text-[#A1A1AA]">
					No employees found for the selected filters.
				</div>
			@endforelse
		</div>

		<div class="rounded-none border border-[#1F1F1F] bg-[#111111] px-4 py-3 text-[#A1A1AA]">
			{{ $employees->links() }}
		</div>
	</div>
</x-app-layout>
