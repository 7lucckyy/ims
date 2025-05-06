<?php

namespace App\Filament\Logistics\Resources\LeaveResource\Pages;

use App\Enums\DocumentType;
use App\Filament\Logistics\Resources\LeaveResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateLeave extends CreateRecord
{
    protected static string $resource = LeaveResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $leave = static::getModel()::create([
            'user_id' => $data['user_id'],
            'reason' => $data['reason'],
            'period_of_leave' => $data['period_of_leave'],
            'start_date' => $data['start_date'],
            'aspected_resumption_date' => $data['aspected_resumption_date'],
        ]);

        $leave->document()->create([
            'type' => DocumentType::Attachment,
            'file' => $data['document'],
        ]);

        return $leave;
    }
}
