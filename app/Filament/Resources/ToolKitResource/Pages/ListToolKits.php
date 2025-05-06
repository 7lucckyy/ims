<?php

namespace App\Filament\Resources\ToolKitResource\Pages;

use App\Filament\Resources\ToolKitResource;
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
