<?php

namespace App\Api\V1\Controllers\Public;

use App\Api\V1\Controllers\Controller;
use App\Models\CurrencyType;
use Exception;
use Illuminate\Http\Request;

/**
 * Class CurrencyTypeController
 *
 * @package App\Api\V1\Controllers\Public
 */
class CurrencyTypeController extends Controller
{
    /**
     * Method getting currency types list
     *
     * @OA\Get(
     *     path="/currency-types",
     *     summary="Get currency types list",
     *     description="Get currency types list",
     *     tags={"Public | Currency types"},
     *
     *     @OA\Response(
     *         response="200",
     *         description="Success",
     *     )
     * )
     *
     * @throws \Exception
     */
    public function __invoke()
    {
        try {
            $type = CurrencyType::all();

            return response()->jsonApi([
                'title' => 'Get currency types',
                'message' => 'List of currency types read successfully',
                'data' => $type
            ]);
        } catch (\Exception $e) {
            return response()->jsonApi([
                'title' => 'Get currency types',
                'message' => $e->getMessage()
            ], 404);
        }
    }
}
