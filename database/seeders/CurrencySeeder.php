<?php

namespace Database\Seeders;

use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currencies = [

            'NGN' => [
                'name' => 'Naira',
                'abbr' => 'NGN',
                'code' => '566',
                'subunit_name' => 'kobo',
                'locale' => 'en_NG',
                'precision' => 2,
                'subunit' => 100,
                'symbol' => '₦',
                'symbol_first' => true,
                'decimal_mark' => '.',
                'thousands_separator' => ',',
            ],

            'USD' => [
                'name' => 'US Dollar',
                'abbr' => 'USD',
                'code' => '840',
                'subunit_name' => 'Cent',
                'locale' => 'en_US',
                'precision' => 2,
                'subunit' => 100,
                'symbol' => '$',
                'symbol_first' => true,
                'decimal_mark' => '.',
                'thousands_separator' => ',',
            ],

            'EUR' => [
                'name' => 'Euro',
                'abbr' => 'EUR',
                'code' => '978',
                'precision' => 2,
                'subunit' => 100,
                'symbol' => '€',
                'symbol_first' => true,
                'decimal_mark' => ',',
                'thousands_separator' => '.',
                'subunit_name' => 'Cent',
                'locale' => 'en_US',
            ],
        ];

        foreach ($currencies as $currency) {
            Currency::create($currency);
        }
    }
}
