<?php

namespace App\Filament\Staff\Resources\IndicatorResource\Pages;

use App\Filament\Staff\Resources\IndicatorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditIndicator extends EditRecord
{
    protected static string $resource = IndicatorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
