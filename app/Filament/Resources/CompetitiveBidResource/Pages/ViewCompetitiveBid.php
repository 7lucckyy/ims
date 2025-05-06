<?php

namespace App\Filament\Resources\CompetitiveBidResource\Pages;

use App\Filament\Resources\CompetitiveBidResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewCompetitiveBid extends ViewRecord
{
    protected static string $resource = CompetitiveBidResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
