<?php

namespace App\Filament\Staff\Resources\TermsOfReferenceResource\Pages;

use App\Filament\Staff\Resources\TermsOfReferenceResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTermsOfReferences extends ListRecords
{
    protected static string $resource = TermsOfReferenceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
