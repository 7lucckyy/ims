<?php

namespace App\Jobs;

use App\Models\PurchaseRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class PRPdf implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private readonly PurchaseRequest $purchaseRequest)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        File::ensureDirectoryExists(storage_path('app/public/purchaseRequests'));

        $pdf = Pdf::loadView('pdf.pr', ['purchaseRequest' => $this->purchaseRequest]);

        $filename = Str::uuid() . '.pdf';

        $pdf->save($filename, 'pr');

        $this->purchaseRequest->update(['filename' => $filename]);
    }
}
