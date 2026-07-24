<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('avatar_url')
                    ->label(__('Photo'))
                    ->circular()
                    ->size(32),
                TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->label(__('Email address'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('department.name')
                    ->label(__('Department'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('role')
                    ->label(__('Role'))
                    ->formatStateUsing(fn (string $state) => __(ucfirst($state)))
                    ->badge()
                    ->color(fn (string $state) => $state === 'admin' ? 'danger' : 'gray')
                    ->sortable(),
                TextColumn::make('work_mode')
                    ->label(__('Work Mode'))
                    ->formatStateUsing(fn (?string $state) => $state ? __($state) : null)
                    ->badge()
                    ->sortable(),
                IconColumn::make('auth_provider')
                    ->label(__('Entra ID'))
                    ->boolean()
                    ->state(fn ($record) => $record->auth_provider === 'entra')
                    ->trueIcon('bi-microsoft')
                    ->falseIcon('bi-dash'),
                TextColumn::make('phone_number')
                    ->label(__('Phone Number'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label(__('Created At'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('role')
                    ->label(__('Role'))
                    ->options([
                        'admin' => __('Admin'),
                        'employee' => __('Employee'),
                    ]),
                SelectFilter::make('department_id')
                    ->label(__('Department'))
                    ->relationship('department', 'name'),
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
