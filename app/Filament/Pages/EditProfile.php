<?php

namespace App\Filament\Pages;

use Filament\Auth\Pages\EditProfile as BaseEditProfile;
use Illuminate\Database\Eloquent\Model;

class EditProfile extends BaseEditProfile
{
    /**
     * @param  array<string, mixed>  $data
     */
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        if (array_key_exists('password', $data)) {
            $data['must_change_password'] = false;
        }

        return parent::handleRecordUpdate($record, $data);
    }
}
