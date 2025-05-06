<?php

namespace App\Http\Controllers;

use App\Enums\PurchaseStatus;
use App\Models\PurchaseRequest;
use Illuminate\Http\Request;
use LaravelDaily\Invoices\Classes\Buyer;
use LaravelDaily\Invoices\Classes\InvoiceItem;
use LaravelDaily\Invoices\Classes\Party;
use LaravelDaily\Invoices\Invoice;

class PRPdfController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(PurchaseRequest $purchaseRequest, Request $request)
    {

        $customer = new Buyer([
            'name' => $purchaseRequest->department->name,
            'custom_fields' => [
                'Requested By' => $purchaseRequest->requestedBy->name,
                'Approved By' => $purchaseRequest->status->value === PurchaseStatus::Approved->value ? $purchaseRequest->approvedBy->name : $purchaseRequest->status->name,
            ],
        ]);

        $seller = new Party([
            'name' => '-',
            'email' => '-',
            'phone' => '-',
        ]);

        $items = [];

        foreach ($purchaseRequest->items as $item) {
            $items[] = (new InvoiceItem)
                ->title($item['name'])
                ->description($item['description'])
                ->pricePerUnit($item['unit_cost'])
                ->subTotalPrice($item['unit_cost'] * $item['quantity'])
                ->quantity($item['quantity']);
        }

        $pr = Invoice::make()
            ->name('Purchase Request')
            ->buyer($customer)
            ->seller($seller)
            ->status($purchaseRequest->status->name)
            ->filename($purchaseRequest->id)
            ->template('pr')
            ->sequence($purchaseRequest->id)
            ->logo(public_path('gponicon.png'))
            ->delimiter('-')
            ->currencyCode($purchaseRequest->currency->abbr)
            ->currencySymbol($purchaseRequest->currency->symbol)
            ->currencyDecimals($purchaseRequest->currency->precision)
            ->currencyDecimalPoint($purchaseRequest->currency->decimal_mark)
            ->currencyThousandsSeparator($purchaseRequest->currency->thousands_separator)
            ->currencyFormat($purchaseRequest->currency->symbol_first == true ? $purchaseRequest->currency->symbol.' '.'{VALUE}' : '{VALUE}'.' '.$purchaseRequest->currency->symbol)
            ->notes($purchaseRequest->notes ?: '')
            ->currencyFraction($purchaseRequest->currency->subunit_name)
            ->addItems($items);

        if ($request->has('preview')) {
            return $pr->stream();
        }

        return $pr->download();
    }
}
