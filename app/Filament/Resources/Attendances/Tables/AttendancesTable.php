<?php

namespace App\Filament\Resources\Attendances\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;

class AttendancesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('attendance_date', 'desc')
            ->defaultGroup(
                Group::make('user.name')
                    ->label(__('Employee'))
                    ->collapsible()
            )
            ->collapsedGroupsByDefault()
            ->groupingSettingsHidden()
            ->columns([
                TextColumn::make('user.name')
                    ->label(__('Employee'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('attendance_date')
                    ->label(__('Attendance Date'))
                    ->date()
                    ->sortable(),
                TextColumn::make('status')
                    ->label(__('Status'))
                    ->formatStateUsing(fn (string $state) => __($state))
                    ->badge()
                    ->sortable(),
                TextColumn::make('checked_in_at')
                    ->label(__('Checked In At'))
                    ->dateTime('H:i')
                    ->sortable(),
                TextColumn::make('checked_out_at')
                    ->label(__('Checked Out At'))
                    ->dateTime('H:i')
                    ->placeholder('—')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('Status'))
                    ->options(array_combine(\App\Models\Attendance::WORK_STATUSES, array_map('__', \App\Models\Attendance::WORK_STATUSES))),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
