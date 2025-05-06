<?php

namespace App\Filament\Hr\Resources\TimeSheetResource\Pages;

use App\Filament\Hr\Resources\TimeSheetResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTimeSheet extends ViewRecord
{
    protected static string $resource = TimeSheetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
