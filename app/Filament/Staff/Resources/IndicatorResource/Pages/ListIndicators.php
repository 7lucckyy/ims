<?php

namespace App\Filament\Staff\Resources\IndicatorResource\Pages;

use App\Filament\Staff\Resources\IndicatorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListIndicators extends ListRecords
{
    protected static string $resource = IndicatorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
