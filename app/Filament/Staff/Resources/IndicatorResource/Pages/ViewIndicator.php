<?php

namespace App\Filament\Staff\Resources\IndicatorResource\Pages;

use App\Filament\Staff\Resources\IndicatorResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewIndicator extends ViewRecord
{
    protected static string $resource = IndicatorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
