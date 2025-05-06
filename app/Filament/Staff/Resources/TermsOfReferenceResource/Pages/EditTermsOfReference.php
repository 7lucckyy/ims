<?php

namespace App\Filament\Staff\Resources\TermsOfReferenceResource\Pages;

use App\Filament\Staff\Resources\TermsOfReferenceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTermsOfReference extends EditRecord
{
    protected static string $resource = TermsOfReferenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
