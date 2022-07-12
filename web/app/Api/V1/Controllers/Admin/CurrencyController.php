<?php

namespace App\Api\V1\Controllers\Admin;

use App\Api\V1\Controllers\Controller;
use App\Models\Currency;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Sumra\SDK\Traits\Collection\CollectionItemsData;
use Illuminate\Validation\ValidationException;

/**
 * Class CurrencyController
 *
 * @package App\Api\V1\Controllers\Admin
 */
class CurrencyController extends Controller
{

    /**
     * Method for list of currencies
     *
     * @OA\Get(
     *     path="/admin/currencies",
     *     summary="Show list of currencies",
     *     description="Show list of currencies",
     *     tags={"Admin / Currencies"},
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
     *     @OA\Parameter(
     *         name="page",
     *         description="page of list",
     *         in="query",
     *         required=false,
     *         @OA\Schema(
     *              type="integer",
     *              default=1,
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
    public function index(Request $request): JsonResponse
    {
        try {
            $columnsMap = [
                'id' => 'ID',
                'title' => 'Title',
                'code' => 'Code',
                'symbol' => 'Symbol',
                'rate' => 'Currency Rate',
                'type' => 'Currency Type',
                'sort' => 'Sort',
                'status' => 'Status'
            ];

            $data = Currency::select(array_keys($columnsMap))
                ->orderBy('title', 'asc')
                ->paginate($request->get('limit', 20));

            $response = CollectionItemsData::transform($data, $columnsMap);
            return response()->jsonApi(array_merge(['success' => true], json_decode($data->toJson(), true)));
        } catch (\Exception $e) {
            return response()->jsonApi([
                'type' => 'danger',
                'title' => 'Display a listing of currencies',
                'message' => $e->getMessage()
            ], 404);
        }
    }

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
     *
     *     @OA\Parameter(
     *         name="title",
     *         description="Currency title",
     *         required=true,
     *         in="path",
     *         @OA\Schema(
     *              type="strigng"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         description="Currency status. Types of statuses: Active = 1, Inactive = 0",
     *         required=true,
     *         in="query",
     *         @OA\Schema(
     *              type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Success"
     *     )
     * )
     *
     * @return \Sumra\SDK\
     *
     * @throws \Exception
     */
    public function store(Request $request)
    {
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'title'     => 'required|string|min:3',
                'code'      => 'required|string|size:3',
                'symbol'    => 'required|string|between:1,2',
                'status'    => 'sometimes|required|boolean'
            ]);
            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            }
            // Create currency model
            $currency = Currency::create($request->all());
            $resp['type']       = "Success";
            $resp['title']      = "Create currency";
            $resp['message']    = "Create currecy";
            $resp['data']       = $currency;
            return response()->jsonApi($resp, 200);
        } catch (ValidationException $e) {
            return response()->jsonApi([
                'type'      => 'warning',
                'title'     => 'Create currency',
                'message'   => 'Validation error',
                'data'      => $e->getMessage()
            ], 400);
        } catch (Exception $e) {
            return response()->jsonApi([
                'type'      => 'danger',
                'title'     => 'Create currency',
                'message'   => 'Error in creating currency',
                'data'      => $e->getMessage()
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
     *
     *     @OA\Parameter(
     *         name="id",
     *         description="Currency id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *              type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="status",
     *         description="Currency status. Types of statuses: Active = 1, Inactive = 0",
     *         in="query",
     *         required=true,
     *         @OA\Schema(
     *              type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
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
