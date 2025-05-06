<?php

namespace App\Filament\Logistics\Resources\DriverResource\Pages;

use App\Filament\Logistics\Resources\DriverResource;
use App\Models\Role;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class CreateDriver extends CreateRecord
{
    protected static string $resource = DriverResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $driver = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $driver->assignRole([Role::DRIVER, Role::LOGISTICS]);

        return $driver;
    }
}
