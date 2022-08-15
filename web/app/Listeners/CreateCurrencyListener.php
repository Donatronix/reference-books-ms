<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Currency;
use App\Models\CurrencyType;

class CreateCurrencyListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  array $data
     * @return void
     */
    public function handle(array $inputData)
    {
        try {

            $data = (object) $inputData;

            // create currency
            $this->createCurrency($data);
        } catch (\Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Create Currency
     *
     * @param $request
     * @return mixed
     */
    private function createCurrency($data): mixed
    {
        try {
            // check if currency already exists
            $currency = Currency::where("code", $data->currency_code)->first();

            if (!$currency) {
                // create currency if it does not exist
                Currency::create([
                    "title" => $data->title,
                    "code" => $data->currency_code,
                    'symbol' => '',
                    'rate' => 1,
                    'type_id' => CurrencyType::CRYPTO,
                ]);
            } else {
                Log::info("Currency already exists");
            }

            return true;
        } catch (ModelNotFoundException $e) {
            Log::info($e->getMessage());
        }
    }
}
