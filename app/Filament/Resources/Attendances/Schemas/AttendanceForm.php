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
                    ->label(__('Employee'))
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                DatePicker::make('attendance_date')
                    ->label(__('Attendance Date'))
                    ->required(),
                Select::make('status')
                    ->label(__('Status'))
                    ->options(array_combine(Attendance::WORK_STATUSES, array_map('__', Attendance::WORK_STATUSES)))
                    ->required(),
                DateTimePicker::make('checked_in_at')
                    ->label(__('Checked In At')),
                DateTimePicker::make('checked_out_at')
                    ->label(__('Checked Out At')),
            ]);
    }
}
