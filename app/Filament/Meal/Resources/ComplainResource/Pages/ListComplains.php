<?php

namespace App\Filament\Meal\Resources\ComplainResource\Pages;

use App\Filament\Meal\Resources\ComplainResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListComplains extends ListRecords
{
    protected static string $resource = ComplainResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
