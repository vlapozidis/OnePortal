<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Notifications\CheckoutReminder;
use Illuminate\Console\Command;

class SendCheckoutReminders extends Command
{
    protected $signature = 'attendance:remind-checkout';

    protected $description = 'Notify employees who checked in today but have not checked out yet';

    public function handle(): void
    {
        $pending = Attendance::query()
            ->with('user')
            ->whereDate('attendance_date', now()->toDateString())
            ->whereNotNull('checked_in_at')
            ->whereNull('checked_out_at')
            ->get();

        foreach ($pending as $attendance) {
            $attendance->user->notify(new CheckoutReminder);
        }

        $this->info("Sent {$pending->count()} checkout reminder(s).");
    }
}
