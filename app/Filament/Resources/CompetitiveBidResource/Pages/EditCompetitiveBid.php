<?php

namespace App\Filament\Resources\CompetitiveBidResource\Pages;

use App\Filament\Resources\CompetitiveBidResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCompetitiveBid extends EditRecord
{
    protected static string $resource = CompetitiveBidResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
