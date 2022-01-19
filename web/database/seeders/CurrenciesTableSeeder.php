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
                'title' => 'US Dollar',
                'code' => 'USD',
                'symbol' => '$',
                'rate' => '1',
                'type' => Currency::TYPE_FIAT,
                'status' => true
            ],
            [
                'title' => 'Euro',
                'code' => 'EUR',
                'symbol' => '€',
                'rate' => '1',
                'type' => Currency::TYPE_FIAT,
                'status' => true
            ],
            [
                'title' => 'British pound',
                'code' => 'GBP',
                'symbol' => '£',
                'rate' => '1',
                'type' => Currency::TYPE_FIAT,
                'status' => true
            ],
            [
                'title' => 'Bitcoin',
                'code' => 'BTC',
                'symbol' => 'BTC',
                'rate' => '1',
                'type' => Currency::TYPE_CRYPTO,
                'status' => true
            ],
            [
                'title' => 'Etherium',
                'code' => 'ETH',
                'symbol' => 'ETH',
                'rate' => '1',
                'type' => Currency::TYPE_CRYPTO,
                'status' => true
            ],
            [
                'title' => 'Solana',
                'code' => 'SOL',
                'symbol' => 'SOL',
                'rate' => '1',
                'type' => Currency::TYPE_CRYPTO,
                'status' => true
            ],
            [
                'title' => 'Binance Coin',
                'code' => 'BNB',
                'symbol' => 'BNB',
                'rate' => '1',
                'type' => Currency::TYPE_CRYPTO,
                'status' => true
            ],
            [
                'title' => 'Cardana',
                'code' => 'ADA',
                'symbol' => 'ADA',
                'rate' => '1',
                'type' => Currency::TYPE_CRYPTO,
                'status' => true
            ],
            [
                'title' => 'Divits Credit',
                'code' => 'DVC',
                'symbol' => '¤',
                'rate' => '1',
                'type' => Currency::TYPE_VIRTUAL,
                'status' => true
            ],
            [
                'title' => 'Sumra DIVITS',
                'code' => 'DVT',
                'symbol' => 'DVT',
                'rate' => '1',
                'type' => Currency::TYPE_VIRTUAL,
                'status' => true
            ],
        ];

        foreach ($list as $item){
            Currency::create($item);
        }
    }
}
