<?php

namespace App\Api\V1\Controllers\Application;

use App\Api\V1\Controllers\Controller;
use App\Contracts\CurrencyRepositoryContract;
use App\Models\Currency;
use App\Models\CurrencyType;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

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
     *
     *     @OA\Parameter(
     *         name="from_currency",
     *         description="from ",
     *         in="query",
     *         required=false,
     *         @OA\Schema(
     *              type="integer",
     *              default = 20,
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="to_currency",
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
    public function getRate(Request $request)
    {
        $from = $this->repository->findByCode($request->from_currency);
        $to = $this->repository->findByCode($request->to_currency);

        dd($from, $to);

        $rate = $from->repository->getRate($to);

        return [
            'from_currency' => $from->code,
            'from_symbol' => $from->symbol,
            'to_currency' => $to->code,
            'to_symbol' => $to->symbol,
            'rate' => $rate,
            'symmetric_rate' => ($rate * 1.03)
        ];
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
