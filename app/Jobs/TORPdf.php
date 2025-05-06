<?php

namespace App\Jobs;

use App\Models\TermsOfReference;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class TORPdf implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(private readonly TermsOfReference $termsOfReference)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        File::ensureDirectoryExists(storage_path('app/public/termsOfReference'));

        $pdf = Pdf::loadView('pdf.tor', ['termsOfReference' => $this->termsOfReference]);

        $filename = Str::uuid() . '.pdf';

        $pdf->save($filename, 'tor');

        $this->termsOfReference->update(['filename' => $filename]);
    }
}
