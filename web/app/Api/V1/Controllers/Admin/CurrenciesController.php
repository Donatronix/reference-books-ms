<?php

namespace App\Api\V1\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Class CurrenciesController
 *
 * @package App\Api\V1\Controllers
 */
class CurrenciesController extends Controller
{
    /**
     * Method for create currency
     *
     * @OA\Post(
     *     path="/admin/currencies/",
     *     summary="Create currency",
     *     description="Create currency",
     *     tags={"Admin / Currencies"},
     *
     *     security={{
     *         "default": {
     *             "ManagerRead",
     *             "User",
     *             "ManagerWrite"
     *         }
     *     }},
     *     x={
     *         "auth-type": "Manager Read & Manager Write",
     *         "throttling-tier": "Unlimited",
     *         "wso2-application-security": {
     *             "security-types": {"oauth2"},
     *             "optional": "false"
     *         }
     *     },
     *     @OA\Parameter(
     *         name="name",
     *         description="Currency name",
     *         required=true,
     *         in="path",
     *          @OA\Schema (
     *              type="strigng"
     *          )
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         description="Currency status. Types of statuses: Active = 1, Inactive = 0",
     *         required=true,
     *         in="query",
     *          @OA\Schema (
     *              type="integer"
     *          )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Success"
     *     )
     * )
     *
     * @return \Sumra\JsonApi\
     * @throws Exception
     */
    public function store(Request $request)
    {
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|min:3',
                'code' => 'required|string|size:3',
                'symbol' => 'required|string|between:1,2',
                'status' => 'sometimes|required|boolean'
            ]);
            if ($validator->fails())
                throw new Exception($validator->errors()->first());


            // Create currency model
            $currency = Currency::create($request->all());

            return response()->jsonApi([
                'success' => true,
                'data' => $currency
            ]);
        } catch (Exception $e) {
            return response()->jsonApi([
                'success' => false,
                'error' => [
                    'title' => 'Currencies',
                    'message' => $e->getMessage()
                ]
            ], 412);
        }
    }
}
