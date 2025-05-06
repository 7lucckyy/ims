<?php

namespace App\Filament\Hr\Resources\LeaveResource\Pages;

use App\Enums\DocumentType;
use App\Filament\Resources\LeaveResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateLeave extends CreateRecord
{
    protected static string $resource = LeaveResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $leave = static::getModel()::create([
            'user_id' => $user = $data['user_id'],
            'department_id' => User::find($user)->staffDetail->department_id,
            'reason' => $data['reason'],
            'period_of_leave' => $data['period_of_leave'],
            'start_date' => $data['start_date'],
            'aspected_resumption_date' => $data['aspected_resumption_date'],
        ]);

        $data['document'] ?: $leave->document()->create([
            'type' => DocumentType::Attachment,
            'file' => $data['document'],
        ]);

        return $leave;
    }
}
