<?php

namespace Database\Seeders;

use App\Models\CurrencyType;
use Illuminate\Database\Seeder;

class CurrencyTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $list = [
            CurrencyType::FIAT => [
                'title' => 'Fiat Money',
                'code' => 'fiat'
            ],
            CurrencyType::CRYPTO => [
                'title' => 'Crypto Currency',
                'code' => 'crypto'
            ],
            CurrencyType::TOKEN => [
                'title' => 'Crypto Tokens',
                'code' => 'token'
            ],
            CurrencyType::VIRTUAL => [
                'title' => 'Virtual Currency',
                'code' => 'virtual'
            ]
        ];

        foreach ($list as $item) {
            CurrencyType::create($item);
        }
    }
}
