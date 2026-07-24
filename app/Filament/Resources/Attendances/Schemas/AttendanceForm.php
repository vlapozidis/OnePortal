<?php

namespace App\Filament\Resources\Attendances\Schemas;

use App\Models\Attendance;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class AttendanceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('Employee')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                DatePicker::make('attendance_date')
                    ->required(),
                Select::make('status')
                    ->options(array_combine(Attendance::WORK_STATUSES, Attendance::WORK_STATUSES))
                    ->required(),
                DateTimePicker::make('checked_in_at'),
                DateTimePicker::make('checked_out_at'),
            ]);
    }
}
