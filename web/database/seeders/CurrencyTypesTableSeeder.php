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
        //
        $list = [
           CurrencyType::FIAT => [
                'title' => 'Fiat money',
                'code' => 'fiat'
            ],
           CurrencyType::CRYPTO => [
                'title' => 'Cryptocurrency',
                'code' => 'crypto'
            ],
           CurrencyType::VIRTUAL => [
                'title' => 'Virtual Currency',
                'code' => 'virtual'
            ]
        ];

        foreach ($list as $item){
            CurrencyType::create($item);
        }
    }
}
