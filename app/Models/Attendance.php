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

    protected $fillable = [
        'user_id',
        'attendance_date',
        'status',
        'checked_in_at',
    ];

    protected function casts(): array
    {
        return [
            'attendance_date' => 'date',
            'checked_in_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
