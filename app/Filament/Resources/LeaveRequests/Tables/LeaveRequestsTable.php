<?php

namespace App\Filament\Resources\LeaveRequests\Tables;

use App\Models\LeaveRequest;
use App\Notifications\LeaveRequestReviewed;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;

class LeaveRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
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
                TextColumn::make('department.name')
                    ->label(__('Department'))
                    ->searchable(),
                TextColumn::make('start_date')
                    ->label(__('Start Date'))
                    ->date()
                    ->sortable(),
                TextColumn::make('end_date')
                    ->label(__('End Date'))
                    ->date()
                    ->sortable(),
                TextColumn::make('status')
                    ->label(__('Status'))
                    ->formatStateUsing(fn (string $state) => __($state))
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'Approved' => 'success',
                        'Rejected' => 'danger',
                        default => 'warning',
                    })
                    ->sortable(),
                TextColumn::make('reviewer.name')
                    ->label(__('Reviewed By'))
                    ->placeholder('—'),
                TextColumn::make('reviewed_at')
                    ->label(__('Reviewed At'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label(__('Created At'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('Status'))
                    ->options([
                        'Pending' => __('Pending'),
                        'Approved' => __('Approved'),
                        'Rejected' => __('Rejected'),
                    ]),
                SelectFilter::make('department_id')
                    ->label(__('Department'))
                    ->relationship('department', 'name'),
            ])
            ->recordActions([
                Action::make('approve')
                    ->label(__('Approve'))
                    ->icon('bi-check-circle')
                    ->color('success')
                    ->visible(fn (LeaveRequest $record) => $record->status === 'Pending')
                    ->requiresConfirmation()
                    ->schema([
                        Textarea::make('admin_comment')
                            ->label(__('Audit note (optional)')),
                    ])
                    ->action(function (LeaveRequest $record, array $data): void {
                        $record->update([
                            'status' => 'Approved',
                            'admin_comment' => $data['admin_comment'] ?? null,
                            'reviewed_by' => auth()->id(),
                            'reviewed_at' => now(),
                        ]);

                        $record->user->notify(new LeaveRequestReviewed($record));
                    }),
                Action::make('reject')
                    ->label(__('Reject'))
                    ->icon('bi-x-circle')
                    ->color('danger')
                    ->visible(fn (LeaveRequest $record) => $record->status === 'Pending')
                    ->requiresConfirmation()
                    ->schema([
                        Textarea::make('admin_comment')
                            ->label(__('Audit note (optional)')),
                    ])
                    ->action(function (LeaveRequest $record, array $data): void {
                        $record->update([
                            'status' => 'Rejected',
                            'admin_comment' => $data['admin_comment'] ?? null,
                            'reviewed_by' => auth()->id(),
                            'reviewed_at' => now(),
                        ]);

                        $record->user->notify(new LeaveRequestReviewed($record));
                    }),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
