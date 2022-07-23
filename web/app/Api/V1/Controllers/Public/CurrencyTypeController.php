<?php

namespace App\Api\V1\Controllers\Public;

use App\Api\V1\Controllers\Controller;
use App\Models\CurrencyType;
use Exception;
use Illuminate\Http\Request;

/**
 * Class CurrencyController
 *
 * @package App\Api\V1\Controllers\Admin
 */
class CurrencyTypeController extends Controller
{

    /**
     * Method for list of currencies type
     *
     * @OA\Get(
     *     path="currencyType",
     *     summary="Show list of currencies",
     *     description="Show list of currencies",
     *     tags={"Currency type"},
     *
     *     security={{
     *         "default": {
     *             "ManagerRead",
     *             "User",
     *             "ManagerWrite"
     *         }
     *     }},
     *
     *     @OA\Parameter(
     *         name="limit",
     *         description="count of currencies in return",
     *         in="query",
     *         required=false,
     *         @OA\Schema(
     *              type="integer",
     *              default = 20,
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Success",
     *     )
     * )
     *
     * @param Request $request
     *
     * @return \Sumra\SDK\
     *
     * @throws \Exception
     */
    public function index(Request $request)
    {
        try {
            $type = CurrencyType::paginate($request->get('limit', config('settings.pagination_limit')));
            return response()->jsonApi([
                'type' => 'success',
                'title' => 'Get list of currency type',
                'message' => 'List of currency type',
                'data' => $type
            ], 200);
        } catch (\Exception $e) {
            return response()->jsonApi([
                'type' => 'danger',
                'title' => 'Display a listing of currencies',
                'message' => $e->getMessage()
            ], 404);
        }
    }


    /**
     * Method for list of currencies type
     *
     * @OA\Get(
     *     path="currencyType/{id}",
     *     summary="get for list of currencies",
     *     description="get from list of currencies",
     *     tags={"Currency type"},
     *
     *     security={{
     *         "default": {
     *             "ManagerRead",
     *             "User",
     *             "ManagerWrite"
     *         }
     *     }},
     *
     *     @OA\Parameter(
     *         name="limit",
     *         description="count of currencies in return",
     *         in="query",
     *         required=false,
     *         @OA\Schema(
     *              type="integer",
     *              default = 20,
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Success",
     *     )
     * )
     *
     * @param Request $request
     *
     * @return \Sumra\SDK\
     *
     * @throws \Exception
     */
    public function show($id)
    {
        try {
            $type = CurrencyType::findOrFail($id);
            return response()->jsonApi([
                'type' => 'success',
                'title' => 'Get list of currency type',
                'message' => 'List of currency type',
                'data' => $type
            ], 200);
        } catch (\Exception $e) {
            return response()->jsonApi([
                'type' => 'danger',
                'title' => 'Display a listing of currencies',
                'message' => $e->getMessage()
            ], 404);
        }
    }
}
