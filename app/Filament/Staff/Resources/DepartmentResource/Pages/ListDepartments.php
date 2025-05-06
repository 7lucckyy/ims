<?php

namespace App\Filament\Staff\Resources\DepartmentResource\Pages;

use App\Filament\Staff\Resources\DepartmentResource;
use Filament\Resources\Pages\ListRecords;

class ListDepartments extends ListRecords
{
    protected static string $resource = DepartmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
