<?php

namespace App\Api\V1\Controllers\Application;

use App\Api\V1\Controllers\Controller;
use App\Contracts\CurrencyRepositoryContract;
use App\Models\Currency;
use App\Models\CurrencyType;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\ExchangeRate as History;

/**
 * Class CurrencyController
 *
 * @package App\Api\V1\Controllers\Application
 */
class CurrencyController extends Controller
{
    /**
     *
     * @var CurrencyRepositoryContract
     */
    private CurrencyRepositoryContract $repository;

    /**
     * @param CurrencyRepositoryContract $repository
     */
    public function __construct(CurrencyRepositoryContract $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Method for getting list of currencies
     *
     * @OA\Get(
     *     path="/currencies",
     *     summary="Getting a list of currencies",
     *     description="Getting a list of currencies",
     *     tags={"Currencies"},
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
     *         name="type",
     *         description="Currency type (fiat | crypto | token | virtual)",
     *         in="query",
     *         required=false,
     *         @OA\Schema(
     *              type="string",
     *              default="fiat",
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
            $list = Currency::select([
                'title as label',
                'code',
                'icon',
                'type_id',
                'rate'
            ])
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

            // Return response
            return response()->jsonApi([
                'type' => 'success',
                'title' => 'Getting currencies collection',
                'message' => 'Currencies collection was received successfully',
                'data' => $list->toArray()
            ], 200);
        } catch (\Exception $e) {
            return response()->jsonApi([
                'type' => 'danger',
                'title' => 'Getting currencies collection',
                'message' => $e->getMessage()
            ], 404);
        }

        //$mayorCurrencies = $this->repository->getDefaultCurrencies();
        //$minorCurrencies = $this->repository->getMinorCurrencies();
        // dd($mayorCurrencies, $minorCurrencies);
        //return $mayorCurrencies->merge($minorCurrencies);
    }

    /**
     * Get / Check current currencies
     *
     * @param $id
     *
     * @return mixed
     */
    public function show($id)
    {
        try {
            return Currency::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            response()->jsonApi([
                'type' => 'danger',
                'title' => 'Currency not found',
                'message' => "Currency with #{$id} not found"
            ], 404);
        }
    }

    /**
     * Method for list of currencies
     *
     * @OA\Get(
     *     path="/currencies/rate",
     *     summary="Show list of currencies",
     *     description="Show list of currencies and rates compared dollars",
     *     tags={"Currencies"},
     *
     *     security={{
     *         "default": {
     *             "ManagerRead",
     *             "User",
     *             "ManagerWrite"
     *         }
     *     }},
     *     @OA\Response(
     *         response="200",
     *         description="Success",
     *     )
     * )
     *
     * @param Request $request
     *
     * @return $response
     *
     * @throws \Exception
     */
    public function getRates(Request $request)
    {
        try {
            $data = History::all();

            return response()->jsonApi([
                'title' => 'Get Currency Rates',
                'message' => 'Get Currency Rates',
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            return response()->jsonApi([
                'title' => 'Display a listing of currencies',
                'message' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Method for list of currencies
     *
     * @OA\Get(
     *     path="/currencies/rate/{currency}",
     *     summary="Show list of currencies",
     *     description="Show list of currencies and rates compared dollars",
     *     tags={"Currencies"},
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
     *         name="currency",
     *         description="currency to get its dollar equivalent",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *              type="string",
     *              default = "usd",
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
     * @return $response
     *
     * @throws \Exception
     */
    public function getCurrencyRate($currency)
    {
        try {
            $data = History::where("currency", $currency)->first();

            return response()->jsonApi([
                'type' => 'success',
                'title' => 'Get Currency Rate',
                'message' => 'Get Currency Rate',
                'data' => $data
            ], 200);
        } catch (\Exception $e) {
            return response()->jsonApi([
                'type' => 'danger',
                'title' => 'Get currency rate',
                'message' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Method for get codes of currencies
     *
     * @OA\Get(
     *     path="/currencies/codes",
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
     *
     *     @OA\Response(
     *         response="200",
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


    /**
     * Method for getting Tokens
     *
     * @OA\Get(
     *     path="/currencies/tokens",
     *     description="Get only Tokens",
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
     *     @OA\Response(
     *         response=200,
     *         description="Success",
     *     )
     * )
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function tokens()
    {
        $tokens = Currency::where('type', CurrencyType::TOKEN)
            ->with('setting')->get();

        return response()->jsonApi([
            'type' => 'success',
            'title' => 'Get Tokens',
            'message' => 'Tokens list',
            'data' => $tokens
        ], 200);
    }
}
