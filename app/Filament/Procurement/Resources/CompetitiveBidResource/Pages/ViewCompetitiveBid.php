<?php

namespace App\Filament\Procurement\Resources\CompetitiveBidResource\Pages;

use App\Filament\Procurement\Resources\CompetitiveBidResource;
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
