<?php

namespace App\Http\Controllers;

use App\Models\LeaveRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;

class StatisticsController extends Controller
{
    private const RANGE_LABELS = [
        '30' => 'Last 30 Days',
        '90' => 'Last 90 Days',
        '180' => 'Last 6 Months',
        '365' => 'Last 12 Months',
        'all' => 'All Time',
    ];

    public function index(Request $request): View
    {
        $employees = User::query()->visible()->orderBy('name')->get(['id', 'name']);

        $employeeId = $request->integer('employee_id') ?: null;
        $requestedRange = $request->query('range');
        $range = is_string($requestedRange) && array_key_exists($requestedRange, self::RANGE_LABELS) ? $requestedRange : '365';

        if ($employeeId && ! $employees->contains('id', $employeeId)) {
            $employeeId = null;
        }

        $rangeStart = $this->rangeStartDate($range);

        return view('statistics.index', [
            'employees' => $employees,
            'filters' => [
                'employee_id' => $employeeId,
                'range' => $range,
            ],
            'employeeName' => $employeeId ? $employees->firstWhere('id', $employeeId)->name : null,
            'rangeLabel' => __(self::RANGE_LABELS[$range]),
            'leavesPerMonth' => $this->getLeavesOverTime($employeeId, $range),
            'departmentLeaveUsage' => $this->getDepartmentLeaveUsage($employeeId, $rangeStart),
            'employeeLeaveStatistics' => $this->getEmployeeLeaveStatistics($employeeId, $rangeStart),
        ]);
    }

    private function rangeStartDate(string $range): ?Carbon
    {
        return match ($range) {
            '30' => now()->subDays(30),
            '90' => now()->subDays(90),
            '180' => now()->subDays(180),
            'all' => null,
            default => now()->subDays(365),
        };
    }

    private function baseQuery(?int $employeeId): \Illuminate\Database\Eloquent\Builder
    {
        $query = LeaveRequest::query();

        if ($employeeId) {
            $query->where('user_id', $employeeId);
        }

        return $query;
    }

    private function getLeavesOverTime(?int $employeeId, string $range): array
    {
        $labels = [];
        $counts = [];

        if (in_array($range, ['30', '90'], true)) {
            $days = (int) $range;

            for ($i = $days - 1; $i >= 0; $i--) {
                $date = now()->subDays($i);
                $labels[] = $date->format('M j');
                $counts[] = (clone $this->baseQuery($employeeId))
                    ->whereDate('created_at', $date->toDateString())
                    ->count();
            }

            return ['labels' => $labels, 'data' => $counts];
        }

        $monthsCount = match ($range) {
            '180' => 6,
            'all' => $this->monthsSinceEarliest($employeeId),
            default => 12,
        };

        for ($i = $monthsCount - 1; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $labels[] = $date->format('M Y');
            $counts[] = (clone $this->baseQuery($employeeId))
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
        }

        return ['labels' => $labels, 'data' => $counts];
    }

    private function monthsSinceEarliest(?int $employeeId): int
    {
        $earliest = $this->baseQuery($employeeId)->min('created_at');

        if (! $earliest) {
            return 12;
        }

        return max(1, (int) Carbon::parse($earliest)->diffInMonths(now()) + 1);
    }

    private function getDepartmentLeaveUsage(?int $employeeId, ?Carbon $rangeStart): array
    {
        $query = $this->baseQuery($employeeId)->with('department');

        if ($rangeStart) {
            $query->where('created_at', '>=', $rangeStart);
        }

        $departments = $query->whereNotNull('department_id')
            ->get()
            ->groupBy('department_id')
            ->map(function ($leaves) {
                return [
                    'name' => $leaves->first()->department?->name ?? 'Unknown',
                    'count' => $leaves->count(),
                ];
            })
            ->values()
            ->sortByDesc('count')
            ->take(10);

        return [
            'labels' => $departments->pluck('name')->toArray(),
            'data' => $departments->pluck('count')->toArray(),
        ];
    }

    private function getEmployeeLeaveStatistics(?int $employeeId, ?Carbon $rangeStart): array
    {
        $query = $this->baseQuery($employeeId)->with('user');

        if ($rangeStart) {
            $query->where('created_at', '>=', $rangeStart);
        }

        $employees = $query->whereNotNull('user_id')
            ->get()
            ->groupBy('user_id')
            ->map(function ($leaves) {
                return [
                    'name' => $leaves->first()->user?->name ?? 'Unknown',
                    'approved' => $leaves->where('status', 'Approved')->count(),
                    'pending' => $leaves->where('status', 'Pending')->count(),
                    'rejected' => $leaves->where('status', 'Rejected')->count(),
                    'total' => $leaves->count(),
                ];
            })
            ->values()
            ->sortByDesc('total')
            ->take(10);

        return [
            'labels' => $employees->pluck('name')->toArray(),
            'approved' => $employees->pluck('approved')->toArray(),
            'pending' => $employees->pluck('pending')->toArray(),
            'rejected' => $employees->pluck('rejected')->toArray(),
        ];
    }
}
