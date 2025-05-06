<?php

namespace App\Filament\Procurement\Resources\CompetitiveBidResource\Pages;

use App\Filament\Procurement\Resources\CompetitiveBidResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCompetitiveBids extends ListRecords
{
    protected static string $resource = CompetitiveBidResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
