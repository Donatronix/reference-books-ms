<?php

namespace App\Listeners;

use App\Models\Notification;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class ProductCreateListener
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
     * @param array $data
     * @return void
     */
    public function handle(array $inputData)
    {
        try {

            $data = (object)$inputData;

            // create notification
            $this->createNotification($data);

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
    private function createNotification($data): mixed
    {
        try {
            Notification::create([
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
