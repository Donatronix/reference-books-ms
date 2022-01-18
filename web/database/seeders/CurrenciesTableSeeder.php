<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Currency;

class CurrenciesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $list = [
            [
                'name' => 'US Dollar',
                'code' => 'USD',
                'symbol' => '$',
                'type' => Currency::TYPE_FIAT,
            ],
            [
                'name' => 'Euro',
                'code' => 'EUR',
                'symbol' => '€',
                'type' => Currency::TYPE_FIAT,
            ],
            [
                'name' => 'British pound',
                'code' => 'GBP',
                'symbol' => '£',
                'type' => Currency::TYPE_FIAT,
            ],
            [
                'name' => 'Bitcoin',
                'code' => 'BTC',
                'symbol' => 'BTC',
                'type' => Currency::TYPE_CRYPTO,
            ],
            [
                'name' => 'Etherium',
                'code' => 'ETH',
                'symbol' => 'ETH',
                'type' => Currency::TYPE_CRYPTO,
            ],
            [
                'name' => 'Solana',
                'code' => 'SOL',
                'symbol' => 'SOL',
                'type' => Currency::TYPE_CRYPTO,
            ],
            [
                'name' => 'Binance Coin',
                'code' => 'BNB',
                'symbol' => 'BNB',
                'type' => Currency::TYPE_CRYPTO,
            ],
            [
                'name' => 'Cardana',
                'code' => 'ADA',
                'symbol' => 'ADA',
                'type' => Currency::TYPE_CRYPTO,
            ],
        ];

        foreach ($list as $item){
            Currency::create($item);
        }
    }
}

//    [
//        'name' => 'British Pound',
//        'code' => 'GBP',
//        'symbol' => '£'
//    ],
//    [
//        'name' => 'Divits Credit',
//        'code' => 'DVC',
//        'symbol' => '¤'
//    ],
// (1,'Sumra DIVITS','DVT','DVT','1.00',
