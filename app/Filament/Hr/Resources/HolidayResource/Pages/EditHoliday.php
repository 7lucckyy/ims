<?php

namespace App\Filament\Hr\Resources\HolidayResource\Pages;

use App\Filament\Hr\Resources\HolidayResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditHoliday extends EditRecord
{
    protected static string $resource = HolidayResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
