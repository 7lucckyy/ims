<?php

namespace App\Filament\Staff\Resources\TermsOfReferenceResource\Pages;

use App\Filament\Staff\Resources\TermsOfReferenceResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateTermsOfReference extends CreateRecord
{
    protected static string $resource = TermsOfReferenceResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['prepared_by'] = Auth::id();
        $data['budget_holder'] = Auth::id();

        return $data;
    }
}
