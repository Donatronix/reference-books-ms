<?php

namespace App\Api\V1\Controllers\Admin;

use App\Api\V1\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\TokenService;

class CurrencySettingController extends Controller
{
    /**
     *
     * @property TokenService
     */
    private $service;

    public function __construct(TokenService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the users
     *
     * @OA\Get(
     *     path="/currencies/settings",
     *     description="Get all CS",
     *     tags={"Admin | Currencies Setting "},
     *
     *     @OA\Response(
     *         response="200",
     *         description="Success"
     *     ),
     *     @OA\Response(
     *         response="401",
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Invalid request"
     *     ),
     *
     *     @OA\Response(
     *         response="404",
     *         description="Not found"
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Unknown error"
     *     )
     * )
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index()
    {
        $tokens = $this->service->get();

        // Return response
        return response()->jsonApi([
            'type' => 'success',
            'title' => 'Token list',
            'message' => 'List of Tokens',
            'data' => $tokens
        ], 200);
    }

    /**
     * Admin adding of Token
     *
     * @OA\Post(
     *     path="/tokens",
     *     description="Add a new Token",
     *     tags={"Admin | Ultainfinity Tokens"},
     *
     *     security={{
     *         "passport": {
     *             "ManagerRead",
     *             "User",
     *             "ManagerWrite"
     *         }
     *     }},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="name",
     *                 type="string",
     *                 description="Token name",
     *                 required={"true"},
     *                 example="UTTA"
     *             ),
     *             @OA\Property(
     *                 property="icon",
     *                 type="string",
     *                 description="Token Icon",
     *                 required={"false"},
     *                 example="utta.jpg"
     *             ),
     *             @OA\Property(
     *                 property="symbol",
     *                 type="string",
     *                 description="Token symbol",
     *                 required={"true"},
     *                 example="UTA"
     *             ),
     *             @OA\Property(
     *                 property="address",
     *                 type="string",
     *                 description="Token mint address",
     *                 required={"true"},
     *                 example="22222222222222222222222222222"
     *             ),
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response="200",
     *         description="Successfully save"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Not found"
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Unknown error"
     *     )
     * )
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $this->validate($request, [
                'name' => 'required|string|unique:tokens,name',
                'symbol' => 'required|string|unique:tokens,symbol',
                'address' => 'required|string|min:15'
            ]);

            $input = $request->all();
            $token = $this->service->create($input);

            // Return response
            return response()->jsonApi([
                'type' => 'success',
                'title' => 'Add Token',
                'message' => 'Token added successfully',
                'data' => $token
            ], 200);
        }
        catch (\Exception $e) {
            return response()->jsonApi([
                'type' => 'danger',
                'title' => 'Add Token',
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Admin update of Token
     *
     * @OA\Put(
     *     path="/tokens/{id}",
     *     description="Update Token",
     *     tags={"Admin | Ultainfinity Tokens"},
     *
     *     security={{
     *         "passport": {
     *             "ManagerRead",
     *             "User",
     *             "ManagerWrite"
     *         }
     *     }},
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="ID of the Token to update",
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="name",
     *                 type="string",
     *                 description="Token name",
     *                 required={"true"},
     *                 example="UTTA"
     *             ),
     *             @OA\Property(
     *                 property="icon",
     *                 type="string",
     *                 description="Token Icon",
     *                 required={"false"},
     *                 example="utta.jpg"
     *             ),
     *             @OA\Property(
     *                 property="symbol",
     *                 type="string",
     *                 description="Token symbol",
     *                 required={"true"},
     *                 example="UTA"
     *             ),
     *             @OA\Property(
     *                 property="address",
     *                 type="string",
     *                 description="Token mint address",
     *                 required={"true"},
     *                 example="22222222222222222222222222222"
     *             ),
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response="200",
     *         description="Updated"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Not found"
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Unknown error"
     *     )
     * )
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $id
     * @return JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $this->validate($request, [
                'name' => 'required|string',
                'symbol' => 'required|string',
                'address' => 'required|string|min:15'
            ]);

            $input = $request->all();
            $token = $this->service->update($input, $id);

            // Return response
            return response()->jsonApi([
                'type' => 'success',
                'title' => 'Update Token',
                'message' => 'Token updated successfully',
                'data' => $token
            ], 200);
        }
        catch (\Exception $e) {
            return response()->jsonApi([
                'type' => 'danger',
                'title' => 'Update Token',
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Admin Delete of Token
     *
     * @OA\Delete(
     *     path="/tokens/{id}",
     *     description="Update Token",
     *     tags={"Admin | Ultainfinity Tokens"},
     *
     *     security={{
     *         "passport": {
     *             "ManagerRead",
     *             "User",
     *             "ManagerWrite"
     *         }
     *     }},
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="ID of the Token to Delete",
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Deleted"
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Not found"
     *     ),
     *     @OA\Response(
     *         response="500",
     *         description="Unknown error"
     *     )
     * )
     * @param  string  $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        try {
            $this->service->delete($id);
            return response()->jsonApi([
                'type' => 'success',
                'title' => 'Delete Token',
                'message' => 'Token deleted successfully',
            ], 200);
        }
        catch (\Exception $e) {
            return response()->jsonApi([
                'type' => 'danger',
                'title' => 'Delete Token',
                'message' => $e->getMessage(),
            ], 400);
        }
    }
}
