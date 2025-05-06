<?php

namespace App\Filament\Imports;

use App\Models\Vendor;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class VendorImporter extends Importer
{
    protected static ?string $model = Vendor::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('category')
                ->relationship(),
            ImportColumn::make('name')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('email')
                ->rules(['email', 'max:255']),
            ImportColumn::make('phone')
                ->rules(['max:255']),
            ImportColumn::make('address'),
            ImportColumn::make('location')
                ->rules(['max:255']),
            ImportColumn::make('recommendation')
                ->rules(['max:255']),
            ImportColumn::make('remarks')
                ->rules(['max:255']),
        ];
    }

    public function resolveRecord(): ?Vendor
    {
        // return Vendor::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new Vendor;
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your vendor import has completed and '.number_format($import->successful_rows).' '.str('row')->plural($import->successful_rows).' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to import.';
        }

        return $body;
    }
}
