<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    public const WORK_STATUSES = [
        'Remote',
        'On Site',
        'Hybrid',
        'Leave',
    ];

    /**
     * Checking out is only allowed from this hour onward (24h, app timezone).
     */
    public const CHECK_OUT_AVAILABLE_FROM_HOUR = 17;

    protected $fillable = [
        'user_id',
        'attendance_date',
        'status',
        'checked_in_at',
        'checked_out_at',
    ];

    protected function casts(): array
    {
        return [
            'attendance_date' => 'date',
            'checked_in_at' => 'datetime',
            'checked_out_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
