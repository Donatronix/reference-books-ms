<?php

namespace App\Api\V1\Controllers;

use App\Contracts\CurrencyRepositoryContract;
use App\Models\Currency;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Sumra\SDK\Traits\Collection\CollectionItemsData;

/**
 * Class CurrencyController
 *
 * @package App\Api\V1\Controllers
 */
class CurrencyController extends Controller
{
    /**
     *
     * @var CurrencyRepositoryContract
     */
    private CurrencyRepositoryContract $currencyRepository;

    /**
     * @param CurrencyRepositoryContract $currencyRepository
     */
    public function __construct(CurrencyRepositoryContract $currencyRepository)
    {
        $this->currencyRepository = $currencyRepository;
    }

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
    public function index(Request $request): JsonResponse
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
     * Method for list currencies for References
     *
     * @OA\Get(
     *     path="/reference/currencies",
     *     description="Get list currencies for References",
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
    public function getRate(Request $request)
    {
        $from = $this->currencyRepository->findByCode($request->from_currency);
        $to = $this->currencyRepository->findByCode($request->to_currency);

        $rate = 1 / $from->rate;
        $rate = $rate * $to->rate;
        $symmetricRate = $rate * 1.03;

        return [
            'from_currency' => $from->code,
            'from_symbol' => $from->getSymbol(),
            'to_currency' => $to->code,
            'to_symbol' => $to->getSymbol(),
            'rate' => $rate,
            'symmetric_rate' => $symmetricRate
        ];
    }

    public function getCurrencies(Request $request)
    {
        $mayorCurrencies = $this->currencyRepository->getDefaultCurrencies();
        $minorCurrencies = $this->currencyRepository->getMinorCurrencies();

        return $mayorCurrencies->merge($minorCurrencies);
    }
}
