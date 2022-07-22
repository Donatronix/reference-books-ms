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
     * @OA\Response(
     *          response="200",
     *          description="Success",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(
     *
     *             @OA\Property(
     *                  property="id",
     *                  type="string",
     *                  description="id",
     *                  example="96772fb4-30e9-4e71-9b0c-deb8e1efe8b5"
     *              ),
     *              @OA\Property(
     *                  property="title",
     *                  type="string",
     *                  description="title",
     *                  example="title"
     *              ),
     *              @OA\Property(
     *                  property="code",
     *                  type="string",
     *                  description="code",
     *                  example="code"
     *              ),
     *              @OA\Property(
     *                  property="created_at",
     *                  type="string",
     *                  description="timestamp of data entry",
     *                  example="2022-05-09T12:45:46.000000Z"
     *              ),
     *           ),
     *        ),
     *    ),
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
     * @OA\Response(
     *          response="200",
     *          description="Success",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(
     *
     *             @OA\Property(
     *                  property="id",
     *                  type="string",
     *                  description="id",
     *                  example="96772fb4-30e9-4e71-9b0c-deb8e1efe8b5"
     *              ),
     *              @OA\Property(
     *                  property="title",
     *                  type="string",
     *                  description="title",
     *                  example="title"
     *              ),
     *              @OA\Property(
     *                  property="code",
     *                  type="string",
     *                  description="code",
     *                  example="code"
     *              ),
     *              @OA\Property(
     *                  property="created_at",
     *                  type="string",
     *                  description="timestamp of data entry",
     *                  example="2022-05-09T12:45:46.000000Z"
     *              ),
     *           ),
     *        ),
     *    ),
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
