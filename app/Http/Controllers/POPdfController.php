<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use Illuminate\Http\Request;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Invoice;

class POPdfController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(PurchaseOrder $purchaseOrder, Request $request)
    {
        $customer = new Buyer([
            'name' => $purchaseOrder->department->name,
        ]);

        $seller = new Party([
            'name' => $purchaseOrder->vendor->name,
            'email' => $purchaseOrder->vendor->email,
            'phone' => $purchaseOrder->vendor->phone,
        ]);

        $items = [];

        foreach ($purchaseOrder->items as $item) {
            $items[] = (new InvoiceItem)
                ->title($item['name'])
                ->description($item['description'])
                ->pricePerUnit($item['unit_cost'])
                ->subTotalPrice($item['unit_cost'] * $item['quantity'])
                ->quantity($item['quantity']);
        }

        $pr = Invoice::make()
            ->name('Purchase Order')
            ->buyer($customer)
            ->seller($seller)
            ->status($purchaseOrder->status->name)
            ->filename($purchaseOrder->id)
            ->template('po')
            ->sequence($purchaseOrder->id)
            ->logo(public_path('gponicon.png'))
            ->delimiter('-')
            ->currencyCode($purchaseOrder->currency->abbr)
            ->currencySymbol($purchaseOrder->currency->symbol)
            ->currencyDecimals($purchaseOrder->currency->precision)
            ->currencyDecimalPoint($purchaseOrder->currency->decimal_mark)
            ->currencyThousandsSeparator($purchaseOrder->currency->thousands_separator)
            ->currencyFormat($purchaseOrder->currency->symbol_first == true ? $purchaseOrder->currency->symbol.' '.'{VALUE}' : '{VALUE}'.' '.$purchaseOrder->currency->symbol)
            ->notes($purchaseOrder->notes)
            ->currencyFraction($purchaseOrder->currency->subunit_name)
            ->addItems($items);

        if ($request->has('preview')) {
            return $pr->stream();
        }

        return $pr->download();
    }
}
