<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // Seede for all
        $this->call([
            CurrencyTypesTableSeeder::class,
            CurrenciesTableSeeder::class
        ]);

        // Seeds for local and staging
        if (App::environment(['local', 'staging'])) {
            $this->call([
                //
            ]);
        }
    }
}
