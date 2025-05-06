<?php

namespace App\Filament\Resources\PurchaseOrderResource\Pages;

use App\Enums\OrderStatus;
use App\Filament\Resources\PurchaseOrderResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\URL;

class ViewPurchaseOrder extends ViewRecord
{
    protected static string $resource = PurchaseOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ActionGroup::make([
                Actions\EditAction::make(),
                Action::make('approve')
                    ->visible(fn ($record) => $record->status->value === OrderStatus::Pending->value)
                    ->icon(OrderStatus::Approved->getIcon())
                    ->color(OrderStatus::Approved->getColor())
                    ->requiresConfirmation()
                    ->action(fn ($record) => $record->approve()),
                Action::make('ship')
                    ->visible(fn ($record) => $record->status->value === OrderStatus::Approved->value)
                    ->icon(OrderStatus::Shipped->getIcon())
                    ->color(OrderStatus::Shipped->getColor())
                    ->requiresConfirmation()
                    ->action(fn ($record) => $record->ship()),
                Action::make('completed')
                    ->visible(fn ($record) => $record->status->value === OrderStatus::Shipped->value)
                    ->icon(OrderStatus::Completed->getIcon())
                    ->color(OrderStatus::Completed->getColor())
                    ->requiresConfirmation()
                    ->action(fn ($record) => $record->complete()),
                Action::make('pdf')
                    ->label('Download PDF')
                    ->color('cyan')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn ($record) => URL::signedRoute('purchaseOrder.pdf', $record), true),
            ]),
        ];
    }
}
