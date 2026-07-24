<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class LeaveRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'department_id',
        'start_date',
        'end_date',
        'reason',
        'status',
        'reviewed_by',
        'reviewed_at',
        'admin_comment',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'reviewed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Map each day-of-month in [$monthStart, $monthEnd] to the status of the
     * user's most relevant leave request covering that day, giving precedence
     * to Approved over Pending over Rejected when requests overlap.
     *
     * @return array<int, string>
     */
    public static function dayStatusesForMonth(int $userId, Carbon $monthStart, Carbon $monthEnd): array
    {
        $statusPriority = ['Approved' => 0, 'Pending' => 1, 'Rejected' => 2];
        $dayStatuses = [];

        static::query()
            ->where('user_id', $userId)
            ->where('start_date', '<=', $monthEnd)
            ->where('end_date', '>=', $monthStart)
            ->get(['start_date', 'end_date', 'status'])
            ->sortBy(fn (self $leaveRequest) => $statusPriority[$leaveRequest->status] ?? 99)
            ->each(function (self $leaveRequest) use (&$dayStatuses, $monthStart, $monthEnd) {
                $period = $leaveRequest->start_date->max($monthStart)->daysUntil($leaveRequest->end_date->min($monthEnd));

                foreach ($period as $date) {
                    $dayStatuses[$date->day] ??= $leaveRequest->status;
                }
            });

        return $dayStatuses;
    }
}
