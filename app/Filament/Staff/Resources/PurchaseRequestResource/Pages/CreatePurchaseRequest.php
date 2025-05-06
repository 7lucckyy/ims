<?php

namespace App\Filament\Staff\Resources\PurchaseRequestResource\Pages;

use App\Filament\Staff\Resources\PurchaseRequestResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreatePurchaseRequest extends CreateRecord
{
    protected static string $resource = PurchaseRequestResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['requested_by'] = Auth::id();
        $data['department_id'] = User::find($data['requested_by'])->staffDetail->department->id;

        return $data;
    }
}
