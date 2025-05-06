<?php

namespace App\Filament\Resources\TimeSheetResource\Pages;

use App\Filament\Resources\TimeSheetResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTimeSheet extends EditRecord
{
    protected static string $resource = TimeSheetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
