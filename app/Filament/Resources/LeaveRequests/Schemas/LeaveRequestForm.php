<?php

namespace App\Filament\Resources\LeaveRequests\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class LeaveRequestForm
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
                Select::make('department_id')
                    ->relationship('department', 'name')
                    ->searchable()
                    ->preload(),
                DatePicker::make('start_date')
                    ->required(),
                DatePicker::make('end_date')
                    ->required()
                    ->afterOrEqual('start_date'),
                Textarea::make('reason')
                    ->columnSpanFull(),
                Select::make('status')
                    ->options([
                        'Pending' => 'Pending',
                        'Approved' => 'Approved',
                        'Rejected' => 'Rejected',
                    ])
                    ->required()
                    ->default('Pending'),
                Select::make('reviewed_by')
                    ->label('Reviewed by')
                    ->relationship('reviewer', 'name')
                    ->searchable()
                    ->preload(),
                DateTimePicker::make('reviewed_at'),
                Textarea::make('admin_comment')
                    ->columnSpanFull(),
            ]);
    }
}
