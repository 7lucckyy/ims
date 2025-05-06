<?php

namespace App\Filament\Meal\Resources\ToolKitResource\Pages;

use App\Filament\Meal\Resources\ToolKitResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListToolKits extends ListRecords
{
    protected static string $resource = ToolKitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
