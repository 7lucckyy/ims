<?php

namespace App\Filament\Logistics\Pages;

use App\Enums\UserRole;
use Filament\Actions\Action;
use Filament\Pages\Page;

class Dashboard extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.logistics.pages.dashboard';

    protected function getHeaderActions(): array
    {
        $check = auth()->user()->role === UserRole::Admin->value;
        if ($check) {
            return [
                Action::make('Admin Page')
                    ->url('/admin'),
            ];
        }

        return [];
    }
}
