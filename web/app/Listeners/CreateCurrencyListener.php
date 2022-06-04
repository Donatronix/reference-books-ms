<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Models\Currency;

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
        Log::info($inputData);
        try {

                $data = (object) $inputData;

                // create notification
                $this->createCurrency($data);

        } catch (\Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Create Notification
     *
     * @param $request
     * @return mixed
     */
    private function createCurrency($data): mixed
    {
        try {
            
            Currency::create([
                "order_id" => $data->id,
                "product_id" => $data->product_id,
                "currency_code" => $data->currency_code,
            ]);

            return true;
        } catch (ModelNotFoundException $e) {
            Log::info($e->getMessage());
        }
    }
}
