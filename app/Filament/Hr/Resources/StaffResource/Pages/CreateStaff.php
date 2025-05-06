<?php

namespace App\Filament\Hr\Resources\StaffResource\Pages;

use App\Filament\Resources\StaffResource;
use App\Mail\UserIntroMail;
use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CreateStaff extends CreateRecord
{
    protected static string $resource = StaffResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $data['password'] = 'password';

        $staff = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'staffId' => Str::uuid()->toString()
        ]);

        // Assign staff role
        $staff->assignRole(Role::STAFF);

        if ($data['hod']) {

            $department = Department::find($data['department_id']);

            $department->hod_id = $staff->id;

            $department->save();

        }

        $staff->projects()->attach($data['projects']);

        // Staff details
        $staff->staffDetail()->create([
            'department_id' => $data['department_id'],
            'position_id' => $data['position_id'],
            'date_of_employment' => $data['date_of_employment'],
            'blood_group' => $data['blood_group']->value,
        ]);

        // Staff payroll
        $staff->payroll()->create([
            'currency_id' => $data['currency_id'],
            'monthly_gross' => $data['monthly_gross'],
            'paye_tax' => 0,
            'net_pay' => 0,
            'health_insurance' => 0,
            'pension' => 0,
            'bank_name' => '',
            'bank_acc_no' => '',
        ]);

        Mail::to($data['email'])->send(new UserIntroMail($data));

        return $staff;
    }
}
