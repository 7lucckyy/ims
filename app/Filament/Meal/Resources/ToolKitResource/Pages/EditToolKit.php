<?php

namespace App\Filament\Meal\Resources\ToolKitResource\Pages;

use App\Filament\Meal\Resources\ToolKitResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditToolKit extends EditRecord
{
    protected static string $resource = ToolKitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
