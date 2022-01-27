<?php

namespace App\Api\V1\Controllers;

use App\Contracts\CurrencyRepositoryContract;
use App\Models\Currency;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
    private CurrencyRepositoryContract $repository;

    /**
     * @param CurrencyRepositoryContract $repository
     */
    public function __construct(CurrencyRepositoryContract $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Method for list currencies
     *
     * @OA\Get(
     *     path="/currencies",
     *     summary="Get list currencies",
     *     description="Get list currencies",
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
     * @param Request $request
     *
     * @return \Sumra\JsonApi\
     *
     * @throws \Exception
     */
    public function index(Request $request): JsonResponse
    {
//            $mayorCurrencies = $this->repository->getDefaultCurrencies();
//            $minorCurrencies = $this->repository->getMinorCurrencies();
//            dd($mayorCurrencies, $minorCurrencies);
//            return $mayorCurrencies->merge($minorCurrencies);

        try {
            $list = Currency::select(['title as label', 'code', 'symbol', 'icon'])
                ->orderBy('title', 'asc')
                ->get();

            $items = [];
            foreach ($list as $item) {
                $items[] = (object)[
                    'value' => $item->code,
                    'label' => $item->symbol . ' - ' . $item->title,
                    'icon' => $item->icon
                ];
            }

            //array_merge(['success' => true], json_decode($data->toJson(), true))

            // Return response
            return response()->jsonApi([
                'type' => 'success',
                'title' => 'Getting currencies collection',
                'message' => 'Currencies collection was received successfully',
                'data' => $items
            ], 200);
        } catch (\Exception $e) {
            return response()->jsonApi([
                'type' => 'danger',
                'title' => 'Getting currencies collection',
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
