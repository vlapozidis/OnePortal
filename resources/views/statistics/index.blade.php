<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold leading-tight text-[var(--portal-text-primary)]">
            {{ __('Leave Statistics') }}
        </h2>

        <form method="GET" action="{{ route('statistics.index') }}" id="statistics-filters" class="flex flex-wrap items-center gap-2">
            <select
                id="employee_id"
                name="employee_id"
                onchange="document.getElementById('statistics-filters').submit()"
                class="w-48 rounded-none border border-[var(--portal-border)] bg-[var(--portal-bg)] px-3 py-1.5 text-xs text-[var(--portal-text-primary)] focus:border-[var(--portal-primary)] focus:ring-[var(--portal-primary)]"
            >
                <option value="">{{ __('All Employees') }}</option>
                @foreach ($employees as $employee)
                    <option value="{{ $employee->id }}" @selected($filters['employee_id'] === $employee->id)>
                        {{ $employee->name }}
                    </option>
                @endforeach
            </select>

            <select
                id="range"
                name="range"
                onchange="document.getElementById('statistics-filters').submit()"
                class="w-40 rounded-none border border-[var(--portal-border)] bg-[var(--portal-bg)] px-3 py-1.5 text-xs text-[var(--portal-text-primary)] focus:border-[var(--portal-primary)] focus:ring-[var(--portal-primary)]"
            >
                <option value="30" @selected($filters['range'] === '30')>{{ __('Last 30 Days') }}</option>
                <option value="90" @selected($filters['range'] === '90')>{{ __('Last 90 Days') }}</option>
                <option value="180" @selected($filters['range'] === '180')>{{ __('Last 6 Months') }}</option>
                <option value="365" @selected($filters['range'] === '365')>{{ __('Last 12 Months') }}</option>
                <option value="all" @selected($filters['range'] === 'all')>{{ __('All Time') }}</option>
            </select>

            @if ($filters['employee_id'] || $filters['range'] !== '365')
                <a href="{{ route('statistics.index') }}" class="text-xs text-[var(--portal-text-secondary)] transition hover:text-[var(--portal-text-primary)]">
                    <i class="bi bi-x-circle mr-1"></i>{{ __('Clear filters') }}
                </a>
            @endif
        </form>
    </x-slot>

    @php
        $filterSuffix = trim(($employeeName ? ' — '.$employeeName : '').' — '.$rangeLabel, ' —');
    @endphp

    <div class="mx-auto max-w-7xl space-y-6">
        <div class="overflow-hidden rounded-none border border-[var(--portal-border)] bg-[var(--portal-surface)] p-6">
            <div class="flex items-center justify-between gap-2">
                <h3 class="text-lg font-semibold text-[var(--portal-text-primary)]">{{ __('Leave Requests Over Time') }} — {{ $filterSuffix }}</h3>
                <button
                    type="button"
                    onclick="exportChartCsv('leavesPerMonth')"
                    class="flex shrink-0 items-center gap-1.5 rounded-none border border-[var(--portal-border)] px-3 py-1.5 text-xs font-semibold text-[var(--portal-text-secondary)] transition hover:border-[var(--portal-primary)] hover:text-[var(--portal-text-primary)]"
                >
                    <i class="bi bi-file-earmark-excel"></i>{{ __('Export to Excel') }}
                </button>
            </div>
            <div class="mt-4" style="position: relative; height: 260px;">
                <canvas id="leavesPerMonthChart"></canvas>
            </div>
        </div>

        <div class="overflow-hidden rounded-none border border-[var(--portal-border)] bg-[var(--portal-surface)] p-6">
            <div class="flex items-center justify-between gap-2">
                <h3 class="text-lg font-semibold text-[var(--portal-text-primary)]">{{ __('Department Leave Usage') }} — {{ $filterSuffix }}</h3>
                <button
                    type="button"
                    onclick="exportChartCsv('departmentLeaveUsage')"
                    class="flex shrink-0 items-center gap-1.5 rounded-none border border-[var(--portal-border)] px-3 py-1.5 text-xs font-semibold text-[var(--portal-text-secondary)] transition hover:border-[var(--portal-primary)] hover:text-[var(--portal-text-primary)]"
                >
                    <i class="bi bi-file-earmark-excel"></i>{{ __('Export to Excel') }}
                </button>
            </div>
            <div class="mt-4" style="position: relative; height: 300px;">
                <canvas id="departmentLeaveChart"></canvas>
            </div>
        </div>

        <div class="overflow-hidden rounded-none border border-[var(--portal-border)] bg-[var(--portal-surface)] p-6">
            <div class="flex items-center justify-between gap-2">
                <h3 class="text-lg font-semibold text-[var(--portal-text-primary)]">{{ __('Employee Leave Statistics') }} — {{ $filterSuffix }}</h3>
                <button
                    type="button"
                    onclick="exportChartCsv('employeeLeaveStatistics')"
                    class="flex shrink-0 items-center gap-1.5 rounded-none border border-[var(--portal-border)] px-3 py-1.5 text-xs font-semibold text-[var(--portal-text-secondary)] transition hover:border-[var(--portal-primary)] hover:text-[var(--portal-text-primary)]"
                >
                    <i class="bi bi-file-earmark-excel"></i>{{ __('Export to Excel') }}
                </button>
            </div>
            <div class="mt-4" style="position: relative; height: 340px;">
                <canvas id="employeeLeaveChart"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
    <script>
        // CSV export (opens directly in Excel) for each chart's underlying data.
        const exportDatasets = {
            leavesPerMonth: {
                headers: [{{ \Illuminate\Support\Js::from(__('Date')) }}, {{ \Illuminate\Support\Js::from(__('Leave Requests')) }}],
                rows: @json($leavesPerMonth['labels']).map((label, i) => [label, @json($leavesPerMonth['data'])[i]]),
            },
            departmentLeaveUsage: {
                headers: [{{ \Illuminate\Support\Js::from(__('Department')) }}, {{ \Illuminate\Support\Js::from(__('Leave Requests')) }}],
                rows: @json($departmentLeaveUsage['labels']).map((label, i) => [label, @json($departmentLeaveUsage['data'])[i]]),
            },
            employeeLeaveStatistics: {
                headers: [{{ \Illuminate\Support\Js::from(__('Employee')) }}, {{ \Illuminate\Support\Js::from(__('Approved')) }}, {{ \Illuminate\Support\Js::from(__('Pending')) }}, {{ \Illuminate\Support\Js::from(__('Rejected')) }}],
                rows: @json($employeeLeaveStatistics['labels']).map((label, i) => [
                    label,
                    @json($employeeLeaveStatistics['approved'])[i],
                    @json($employeeLeaveStatistics['pending'])[i],
                    @json($employeeLeaveStatistics['rejected'])[i],
                ]),
            },
        };

        const exportFileSuffix = {{ \Illuminate\Support\Js::from(($filters['employee_id'] ? \Illuminate\Support\Str::slug($employeeName).'_' : '').$filters['range']) }};

        function exportChartCsv(key) {
            const { headers, rows } = exportDatasets[key];
            const csv = [headers, ...rows]
                .map((row) => row.map((value) => `"${String(value).replace(/"/g, '""')}"`).join(','))
                .join('\r\n');

            const blob = new Blob(['﻿' + csv], { type: 'text/csv;charset=utf-8;' });
            const url = URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = url;
            link.download = `${key}_${exportFileSuffix}.csv`;
            document.body.appendChild(link);
            link.click();
            link.remove();
            URL.revokeObjectURL(url);
        }

        // Canvas drawing can't resolve CSS var() references, so the current
        // theme's computed colors are read once up front and passed as
        // literal values — otherwise Chart.js silently fails to apply them.
        const rootStyles = getComputedStyle(document.documentElement);
        const cssVar = (name) => rootStyles.getPropertyValue(name).trim();

        const chartColors = {
            primary: cssVar('--portal-primary'),
            secondary: '#F97316',
            success: '#22C55E',
            warning: '#EAB308',
            danger: '#EF4444',
            info: '#06B6D4',
            gridLine: cssVar('--portal-border'),
            text: cssVar('--portal-text-secondary'),
            surface: cssVar('--portal-surface'),
        };

        const chartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: {
                        color: chartColors.text,
                        font: {
                            family: "'figtree', sans-serif",
                        },
                    },
                },
            },
            scales: {
                y: {
                    ticks: { color: chartColors.text },
                    grid: { color: chartColors.gridLine },
                },
                x: {
                    ticks: { color: chartColors.text },
                    grid: { color: chartColors.gridLine },
                },
            },
        };

        // Leaves Per Month Chart
        const leavesPerMonthCtx = document.getElementById('leavesPerMonthChart').getContext('2d');
        new Chart(leavesPerMonthCtx, {
            type: 'line',
            data: {
                labels: @json($leavesPerMonth['labels']),
                datasets: [{
                    label: '{{ __('Leave Requests') }}',
                    data: @json($leavesPerMonth['data']),
                    borderColor: chartColors.primary,
                    backgroundColor: 'rgba(220, 38, 38, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: chartColors.primary,
                    pointBorderColor: chartColors.surface,
                    pointRadius: 5,
                    pointHoverRadius: 7,
                }],
            },
            options: {
                ...chartOptions,
                plugins: {
                    ...chartOptions.plugins,
                    filler: {
                        propagate: true,
                    },
                },
            },
        });

        // Department Leave Usage Chart
        const departmentLeaveCtx = document.getElementById('departmentLeaveChart').getContext('2d');
        new Chart(departmentLeaveCtx, {
            type: 'bar',
            data: {
                labels: @json($departmentLeaveUsage['labels']),
                datasets: [{
                    label: '{{ __('Leave Requests') }}',
                    data: @json($departmentLeaveUsage['data']),
                    backgroundColor: [
                        chartColors.primary,
                        chartColors.secondary,
                        chartColors.success,
                        chartColors.warning,
                        chartColors.danger,
                        chartColors.info,
                    ].concat(Array(4).fill(chartColors.primary)),
                    borderRadius: 6,
                    borderSkipped: false,
                }],
            },
            options: {
                ...chartOptions,
                indexAxis: 'y',
            },
        });

        // Employee Leave Statistics Chart
        const employeeLeaveCtx = document.getElementById('employeeLeaveChart').getContext('2d');
        new Chart(employeeLeaveCtx, {
            type: 'bar',
            data: {
                labels: @json($employeeLeaveStatistics['labels']),
                datasets: [
                    {
                        label: '{{ __('Approved') }}',
                        data: @json($employeeLeaveStatistics['approved']),
                        backgroundColor: chartColors.success,
                        borderRadius: 4,
                    },
                    {
                        label: '{{ __('Pending') }}',
                        data: @json($employeeLeaveStatistics['pending']),
                        backgroundColor: chartColors.warning,
                        borderRadius: 4,
                    },
                    {
                        label: '{{ __('Rejected') }}',
                        data: @json($employeeLeaveStatistics['rejected']),
                        backgroundColor: chartColors.danger,
                        borderRadius: 4,
                    },
                ],
            },
            options: {
                ...chartOptions,
            },
        });
    </script>
</x-app-layout>
