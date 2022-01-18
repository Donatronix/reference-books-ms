<?php

namespace App\Http\Controllers;

use App\Model\Repositories\CurrencyContract;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

/**
 *
 * @author Mauricio
 *
 */
class CurrencyController1 extends Controller
{
    const BASE_CURRENCY = 'USD';

    /**
     *
     * @var CurrencyContract
     */
    private $currencyRepository;

    /**
     */
    public function __construct(CurrencyContract $currencyRepository)
    {
        $this->currencyRepository = $currencyRepository;
    }

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
