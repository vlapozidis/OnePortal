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
                Section::make(__('Profile'))
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label(__('Name'))
                            ->required(),
                        TextInput::make('email')
                            ->label(__('Email address'))
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true),
                        TextInput::make('phone_number')
                            ->label(__('Phone Number'))
                            ->tel(),
                        Select::make('department_id')
                            ->label(__('Department'))
                            ->relationship('department', 'name')
                            ->searchable()
                            ->preload(),
                        Select::make('work_mode')
                            ->label(__('Work Mode'))
                            ->options([
                                'Remote' => __('Remote'),
                                'On Site' => __('On Site'),
                                'Hybrid' => __('Hybrid'),
                            ]),
                        Select::make('role')
                            ->label(__('Role'))
                            ->options([
                                'admin' => __('Admin'),
                                'employee' => __('Employee'),
                            ])
                            ->required()
                            ->default('employee'),
                        Select::make('theme')
                            ->label(__('Theme'))
                            ->options([
                                'light' => __('Light'),
                                'dark' => __('Dark'),
                            ])
                            ->required()
                            ->default('light'),
                        Toggle::make('must_change_password')
                            ->label(__('Must Change Password'))
                            ->helperText(__('User will be forced to set a new password on next login.')),
                    ]),

                Section::make(__('Password'))
                    ->columns(2)
                    ->schema([
                        TextInput::make('password')
                            ->label(__('Password'))
                            ->password()
                            ->revealable()
                            ->dehydrateStateUsing(fn (?string $state) => filled($state) ? Hash::make($state) : null)
                            ->dehydrated(fn (?string $state) => filled($state))
                            ->required(fn (string $operation) => $operation === 'create')
                            ->helperText(__('Leave blank to keep the current password.')),
                    ]),

                Section::make(__('Microsoft Entra ID'))
                    ->description(__('Populated automatically by the SSO sync — not manually editable.'))
                    ->columns(2)
                    ->collapsed()
                    ->schema([
                        TextInput::make('auth_provider')
                            ->label(__('Auth Provider'))
                            ->disabled(),
                        TextInput::make('entra_id')
                            ->label(__('Entra Object ID'))
                            ->disabled(),
                        TextInput::make('entra_email')
                            ->label(__('Entra Email'))
                            ->disabled(),
                        TextInput::make('azure_tenant_id')
                            ->label(__('Azure Tenant ID'))
                            ->disabled(),
                        DateTimePicker::make('entra_synced_at')
                            ->label(__('Entra Synced At'))
                            ->disabled(),
                    ]),
            ]);
    }
}
