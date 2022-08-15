<?php

namespace App\Listeners;

use App\Models\Currency;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

/**
 * Get Currency List Request Listener
 *
 * Class GetCurrencyRequestListener
 *
 * @package App\Listeners
 */
class GetCurrencyRequestListener
{
    /**
     * Handle the event.
     *
     * @param array $queryData
     * @return void
     */
    public function handle(array $queryData)
    {
        // Validate input
        $validation = Validator::make($queryData, [
            'replay_to' => 'required|string',
            'type' => 'sometimes|string',
        ]);

        // Send feedback if validation fails
        if ($validation->fails()) {
            Log::error('Request from: ' . $queryData['replay_to']);
            Log::error($validation->errors());

            exit;
        }

        // Try Fetch currencies list
        try {
            $list = Currency::query()
                ->select([
                    'title',
                    'code',
                    'icon',
                    'type_id',
                    'rate'
                ])
                ->with('type:id,code')
                ->when($queryData['type'], function ($q) use ($queryData) {
                    return $q->whereHas('type', function ($q) use ($queryData) {
                        return $q->where('code', $queryData['type']);
                    });
                })
                ->where('status', true)
                ->orderBy('sort', 'asc')
                ->get();

            // Transform currency object
            $list->map(function ($object) {
                //
                $code = $object->type->code;

                unset($object->type);

                $object->setAttribute('type', $code);

                return $object;
            });

            // Send currency list response to wallet microservice
            \PubSub::publish('GetCurrencyResponse', $list->toArray(), $queryData['replay_to']);
        } catch (\Exception $e) {
            Log::error('Exception error for request: ' . $queryData['replay_to']);
            Log::error($e->errors());

            exit;
        }
    }
}
