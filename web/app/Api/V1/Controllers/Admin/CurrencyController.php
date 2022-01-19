<?php

namespace App\Api\V1\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Class CurrencyController
 *
 * @package App\Api\V1\Controllers
 */
class CurrencyController extends Controller
{
    /**
     * Method for create currency
     *
     * @OA\Post(
     *     path="/admin/currencies",
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

    /**
     * Method for update status of currency
     *
     * @OA\Patch(
     *     path="/admin/currencies/{id}/update-status",
     *     summary="Update status of currency",
     *     description="Update status of currency",
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
     *         name="id",
     *         description="Currency id",
     *         required=true,
     *         in="path",
     *          @OA\Schema (
     *              type="integer"
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
     * @param \Illuminate\Http\Request $request
     * @param                          $id
     *
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function updateStatus(Request $request, $id)
    {
        // Validate input
        $this->validate($request, [
            'status' => 'boolean'
        ]);

        // Get currency model
        try {
            $currency = Currency::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response()->jsonApi([
                'type' => 'danger',
                'title' => 'Currency not found',
                'message' => "Currency #{$id} not found"
            ], 404);
        }

        // Update currency data
        try {
            $currency->status = $request->get('status', Currency::STATUS_ACTIVE);
            $currency->save();

            // Return response
            return response()->jsonApi([
                'type' => 'success',
                'title' => 'Status update',
                'message' => "Status has been successful updated"
            ], 200);
        } catch (Exception $e) {
            return response()->jsonApi([
                'type' => 'danger',
                'title' => 'Status update',
                'message' => $e->getMessage()
            ], $e->getCode());
        }
    }
}
