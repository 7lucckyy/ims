<?php

namespace App\Filament\Meal\Resources\IndicatorResource\Pages;

use App\Filament\Meal\Resources\IndicatorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditIndicator extends EditRecord
{
    protected static string $resource = IndicatorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
