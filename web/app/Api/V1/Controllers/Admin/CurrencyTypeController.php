<?php

namespace App\Api\V1\Controllers\Admin;

use App\Api\V1\Controllers\Controller;

use App\Models\CurrencyType;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * Class CurrencyController
 *
 * @package App\Api\V1\Controllers\Admin
 */
class CurrencyTypeController extends Controller
{
    /**
     * Method to delete currency type
     *
     * @OA\delete(
     *     path="/admin/currencyType",
     *     summary="Show list of currencies",
     *     description="Show list of currencies",
     *     tags={"Admin / Currency type"},
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
    public function destroy($id)
    {
        try {
            $type = CurrencyType::findOrFail($id);
            $type->delete();
            return response()->jsonApi([
                'type' => 'success',
                'title' => 'Delete a currency type',
                'message' => 'Currency type Deleted'
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
     * Method for create currency type
     *
     * @OA\Post(
     *     path="/admin/currencyType",
     *     summary="Create currency",
     *     description="Create currency",
     *     tags={"Admin / Currency type"},
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
     *              type="string"
     *         )
     *     ),

     *     @OA\RequestBody(
    *            @OA\JsonContent(
    *                type="object",
    *                @OA\Property(
    *                    property="title",
    *                    type="string",
    *                    description="title",
    *                    example="title"
    *                ),
    *                @OA\Property(
    *                    property="code",
    *                    type="string",
    *                    description="code",
    *                    example="code"
    *                ),
    *           ),
    *       ),
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
                'title'     => 'required|string',
                'code'      => 'required|string',
            ]);
            if ($validator->fails()) {
                throw new Exception($validator->errors()->first());
            }
            // Create currency model
            $currencyType = CurrencyType::create($request->all());
            $resp['type']       = "Success";
            $resp['title']      = "Create currency";
            $resp['message']    = "Create currecy";
            $resp['data']       = $currencyType;
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
     * Method for update of currency type
     *
     * @OA\Put(
     *     path="/admin/currencyType/{id}/update-status",
     *     summary="Update status of currency",
     *     description="Update status of currency",
     *     tags={"Admin / Currency type"},
     *
     *     security={{
     *         "default": {
     *             "ManagerRead",
     *             "User",
     *             "ManagerWrite"
     *         }
     *     }},

     *     @OA\Parameter(
     *         name="status",
     *         description="Currency status. Types of statuses: Active = 1, Inactive = 0",
     *         in="query",
     *         required=true,
     *         @OA\Schema(
     *              type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
    *            @OA\JsonContent(
    *                type="object",
    *                @OA\Property(
    *                    property="title",
    *                    type="string",
    *                    description="title",
    *                    example="title"
    *                ),
    *                @OA\Property(
    *                    property="code",
    *                    type="string",
    *                    description="code",
    *                    example="code"
    *                ),
    *           ),
    *       ),
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
    public function update(Request $request, $id)
    {

        try {

            // Validate input
            $this->validate($request, [
                'code' => 'required|string',
                'title' => 'required|string',
            ]);

            // Get currency model
            $currency = CurrencyType::where('id', $id)->first();


            $currency->title = $request->title;
            $currency->code = $request->code;
            $currency->save();

            return response()->jsonApi([
                'type' => 'success',
                'title' => 'Status update',
                'message' => "Status has been successful updated",
                'data' => $currency
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
