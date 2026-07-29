<?php

namespace App\Filament\Imports;

use App\Models\Department;
use App\Models\User;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserImporter extends Importer
{
    protected static ?string $model = User::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('email')
                ->requiredMapping()
                ->rules(['required', 'email', 'max:255']),
            ImportColumn::make('phone_number')
                ->rules(['nullable', 'max:255']),
            ImportColumn::make('department')
                ->relationship(resolveUsing: fn (string $state) => Department::firstOrCreate(['name' => trim($state)])),
            ImportColumn::make('work_mode')
                ->rules(['nullable', 'in:Remote,On Site,Hybrid']),
            ImportColumn::make('role')
                ->rules(['nullable', 'in:admin,employee']),
            ImportColumn::make('password')
                ->rules(['nullable', 'min:8']),
        ];
    }

    public function resolveRecord(): ?User
    {
        return User::firstOrNew([
            'email' => $this->data['email'],
        ]);
    }

    protected function beforeFill(): void
    {
        if (blank($this->data['password'] ?? null)) {
            unset($this->data['password']);

            return;
        }

        $this->data['password'] = Hash::make($this->data['password']);
    }

    protected function beforeCreate(): void
    {
        if (blank($this->record->password)) {
            $this->record->password = Hash::make(Str::random(32));
            $this->record->must_change_password = true;
        }

        $this->record->role ??= 'employee';
        $this->record->theme ??= 'light';
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = __(':count rows imported.', ['count' => number_format($import->successful_rows)]);

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.__(':count rows failed to import.', ['count' => number_format($failedRowsCount)]);
        }

        return $body;
    }
}
