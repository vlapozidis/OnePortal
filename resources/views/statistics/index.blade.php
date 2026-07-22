<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold leading-tight text-white">
            {{ __('Leave Statistics') }}
        </h2>
    </x-slot>

    <div class="mx-auto max-w-7xl space-y-6">
        <div class="overflow-hidden rounded-none border border-[#1F1F1F] bg-[#111111] p-6">
            <h3 class="text-lg font-semibold text-white">Leaves Per Month (Last 12 Months)</h3>
            <div class="mt-4" style="position: relative; height: 300px;">
                <canvas id="leavesPerMonthChart"></canvas>
            </div>
        </div>

        <div class="overflow-hidden rounded-none border border-[#1F1F1F] bg-[#111111] p-6">
            <h3 class="text-lg font-semibold text-white">Department Leave Usage (Top 10)</h3>
            <div class="mt-4" style="position: relative; height: 350px;">
                <canvas id="departmentLeaveChart"></canvas>
            </div>
        </div>

        <div class="overflow-hidden rounded-none border border-[#1F1F1F] bg-[#111111] p-6">
            <h3 class="text-lg font-semibold text-white">Employee Leave Statistics (Top 10)</h3>
            <div class="mt-4" style="position: relative; height: 400px;">
                <canvas id="employeeLeaveChart"></canvas>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
    <script>
        const chartColors = {
            primary: '#DC2626',
            secondary: '#F97316',
            success: '#22C55E',
            warning: '#EAB308',
            danger: '#EF4444',
            info: '#06B6D4',
            gridLine: '#1F1F1F',
            text: '#A1A1AA',
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
                    label: 'Leave Requests',
                    data: @json($leavesPerMonth['data']),
                    borderColor: chartColors.primary,
                    backgroundColor: 'rgba(220, 38, 38, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: chartColors.primary,
                    pointBorderColor: '#111111',
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
                    label: 'Leave Requests',
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
                        label: 'Approved',
                        data: @json($employeeLeaveStatistics['approved']),
                        backgroundColor: chartColors.success,
                        borderRadius: 4,
                    },
                    {
                        label: 'Pending',
                        data: @json($employeeLeaveStatistics['pending']),
                        backgroundColor: chartColors.warning,
                        borderRadius: 4,
                    },
                    {
                        label: 'Rejected',
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
