<?php

namespace App\Api\V1\Controllers;

use App\Api\V1\Controllers\Controller;
use App\Models\Currency;
use App\Traits\ResponseItemsData;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class CurrenciesController
 *
 * @package App\Api\V1\Controllers
 */
class CurrencyController extends Controller
{
    /**
     * Method for list of currencies
     *
     * @OA\Get(
     *     path="/currencies",
     *     summary="Show list of currencies",
     *     description="Show list of currencies",
     *     tags={"Currencies"},
     *
     *     security={{
     *         "default": {
     *             "ManagerRead",
     *             "User",
     *             "ManagerWrite"
     *         }
     *     }},
     *     x={
     *         "auth-type": "Application & Application User",
     *         "throttling-tier": "Unlimited",
     *         "wso2-application-security": {
     *             "security-types": {"oauth2"},
     *             "optional": "false"
     *         }
     *     },
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
     *         response=200,
     *         description="Success",
     *     )
     * )
     *
     * @param Request $request
     *
     * @return \Sumra\JsonApi\
     *
     * @throws \Exception
     */
    public function index(Request $request) : JsonResponse
    {
        try {
            $columnsMap = [
                'id' => 'ID',
                'name' => 'Name',
                'code' => 'Code',
                'symbol' => 'Symbol',
                'status' => 'Status'
            ];

            $data = Currency::select(array_keys($columnsMap))
                ->orderBy('name', 'asc')
                ->paginate($request->get('limit', 20));

            $response = ResponseItemsData::transform($data, $columnsMap);

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
     * Get / Check current currencies
     *
     * @param $id
     *
     * @return mixed
     */
//    public function show($id){
//        try {
//            return Currency::findOrFail($id);
//        } catch (ModelNotFoundException $e) {
//            response()->jsonApi([
//                'type' => 'danger',
//                'title' => 'Currency not found',
//                'message' => "Currency with #{$id} not found"
//            ], 404);
//        }
//    }

    /**
     * Method for update status of currency
     *
     * @OA\Post(
     *     path="/currencies/{id}/update-status",
     *     summary="Update status of currency",
     *     description="Update status of currency",
     *     tags={"Currencies"},
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

    /**
     * Method for list currencies for References
     *
     * @OA\Get(
     *     path="/reference/currencies",
     *     description="Get list currencies for References",
     *     tags={"References"},
     *
     *     security={{
     *         "default": {
     *             "ManagerRead",
     *             "User",
     *             "ManagerWrite"
     *         }
     *     }},
     *     x={
     *         "auth-type": "Application & Application User",
     *         "throttling-tier": "Unlimited",
     *         "wso2-application-security": {
     *             "security-types": {"oauth2"},
     *             "optional": "false"
     *         }
     *     },
     *
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *     )
     * )
     *
     * @return \Sumra\JsonApi\
     *
     * @throws \Exception
     */
    public function reference()
    {
        try {
            $list = Currency::orderBy('name', 'asc')->get();

            $items = [];
            foreach ($list as $item) {
                $items[] = (object)[
                    'value' => $item->id,
                    'label' => $item->symbol . ' - ' . $item->name
                ];
            }

            return response()->jsonApi($items);
        } catch (\Exception $e) {
            return response()->jsonApi([
                'type' => 'danger',
                'title' => 'Display a listing of currencies',
                'message' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Method for get codes of currencies
     *
     * @OA\Get(
     *     path="/v1/currencies/codes",
     *     description="Get codes of currencies",
     *     tags={"Currencies"},
     *
     *     security={{
     *         "default": {
     *             "ManagerRead",
     *             "User",
     *             "ManagerWrite"
     *         }
     *     }},
     *     x={
     *         "auth-type": "Application & Application User",
     *         "throttling-tier": "Unlimited",
     *         "wso2-application-security": {
     *             "security-types": {"oauth2"},
     *             "optional": "false"
     *         }
     *     },
     *
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *     )
     * )
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function codes()
    {
        return response()->json([
            'success' => true,
            'codes' => Currency::codes()
        ], 200);
    }

}
