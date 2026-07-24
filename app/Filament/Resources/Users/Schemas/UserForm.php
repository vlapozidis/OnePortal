<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Profile')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required(),
                        TextInput::make('email')
                            ->label('Email address')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true),
                        TextInput::make('phone_number')
                            ->tel(),
                        Select::make('department_id')
                            ->relationship('department', 'name')
                            ->searchable()
                            ->preload(),
                        Select::make('work_mode')
                            ->options([
                                'Remote' => 'Remote',
                                'On Site' => 'On Site',
                                'Hybrid' => 'Hybrid',
                            ]),
                        Select::make('role')
                            ->options([
                                'admin' => 'Admin',
                                'employee' => 'Employee',
                            ])
                            ->required()
                            ->default('employee'),
                        Select::make('theme')
                            ->options([
                                'light' => 'Light',
                                'dark' => 'Dark',
                            ])
                            ->required()
                            ->default('light'),
                        Toggle::make('must_change_password')
                            ->helperText('User will be forced to set a new password on next login.'),
                    ]),

                Section::make('Password')
                    ->columns(2)
                    ->schema([
                        TextInput::make('password')
                            ->password()
                            ->revealable()
                            ->dehydrateStateUsing(fn (?string $state) => filled($state) ? Hash::make($state) : null)
                            ->dehydrated(fn (?string $state) => filled($state))
                            ->required(fn (string $operation) => $operation === 'create')
                            ->helperText('Leave blank to keep the current password.'),
                    ]),

                Section::make('Microsoft Entra ID')
                    ->description('Populated automatically by the SSO sync — not manually editable.')
                    ->columns(2)
                    ->collapsed()
                    ->schema([
                        TextInput::make('auth_provider')
                            ->disabled(),
                        TextInput::make('entra_id')
                            ->label('Entra Object ID')
                            ->disabled(),
                        TextInput::make('entra_email')
                            ->disabled(),
                        TextInput::make('azure_tenant_id')
                            ->disabled(),
                        DateTimePicker::make('entra_synced_at')
                            ->disabled(),
                    ]),
            ]);
    }
}
