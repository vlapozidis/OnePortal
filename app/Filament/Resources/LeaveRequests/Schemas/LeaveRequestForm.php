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
                    ->label(__('Employee'))
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                Select::make('department_id')
                    ->label(__('Department'))
                    ->relationship('department', 'name')
                    ->searchable()
                    ->preload(),
                DatePicker::make('start_date')
                    ->label(__('Start Date'))
                    ->required(),
                DatePicker::make('end_date')
                    ->label(__('End Date'))
                    ->required()
                    ->afterOrEqual('start_date'),
                Textarea::make('reason')
                    ->label(__('Reason'))
                    ->columnSpanFull(),
                Select::make('status')
                    ->label(__('Status'))
                    ->options([
                        'Pending' => __('Pending'),
                        'Approved' => __('Approved'),
                        'Rejected' => __('Rejected'),
                    ])
                    ->required()
                    ->default('Pending'),
                Select::make('reviewed_by')
                    ->label(__('Reviewed By'))
                    ->relationship('reviewer', 'name')
                    ->searchable()
                    ->preload(),
                DateTimePicker::make('reviewed_at')
                    ->label(__('Reviewed At')),
                Textarea::make('admin_comment')
                    ->label(__('Admin Comment'))
                    ->columnSpanFull(),
            ]);
    }
}
