<?php

namespace App\Actions;

use App\Models\Stock;

class StockAction
{
    public static function updateRecordAction(array $data): void
    {
        $nameOfItem = $data['name_of_items'];
        $quantity = $data['quantity'];

        $itemFromStock = Stock::where([
            'ITEM_DESCRIPTION' => $nameOfItem,
        ]);

        $itemFromStock->decrement('STOCK_BALANCE');
        $itemFromStock->increment('QTY_OUT');
    }
}
