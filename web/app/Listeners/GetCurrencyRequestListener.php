<?php

namespace App\Listeners;

use App\Events\ExampleEvent;

class GetCurrencyRequestListener
{
    /**
     * @var string
     */
    private const RECEIVER_LISTENER = 'GetCurrencyResponse';
    
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
     * @param  array  $queryData
     * @return void
     */
    public function handle(array $queryData)
    {
        // Validate input
        $validation = Validator::make($inputData, [
            'replay_to' => 'string|required',
            'user_id' => 'integer|required',
        ]);

        //Send feedback if validation fails
        if ($validation->fails()) {
            \PubSub::transaction(function () {})->publish(self::RECEIVER_LISTENER, [
                'status' => 'error',
                'message' => $validation->errors()
            ], $queryData['replay_to']);
            
            exit;
        }

        //Fetch currencies
         try{
            $list = Currency::select(['title as label','code','icon','type_id','rate'])
                    ->with('type:id,code')
                    ->when(($request->has('type') && !empty($request->get('type'))), function ($q) use ($request) {
                        return $q->whereHas('type', function ($q) use ($request) {
                            return $q->where('code', $request->get('type'));
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
            \PubSub::transaction(function () {})->publish(self::RECEIVER_LISTENER, array_merge($list->toArray(),[
                'status' => 'success'
            ]), $queryData['replay_to']);

         } catch(\Exception $e){
            \PubSub::transaction(function () {})->publish(self::RECEIVER_LISTENER, [
                'status' => 'error',
                'message' => $e->errors()
            ], $queryData['replay_to']);

            exit;
         }





    }
}
