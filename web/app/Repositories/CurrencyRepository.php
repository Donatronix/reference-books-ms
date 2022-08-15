<?php

namespace App\Repositories;

use App\Contracts\CurrencyRepositoryContract;
use App\Models\Currency;
use Illuminate\Database\Eloquent\Collection;

/**
 *
 */
class CurrencyRepository implements CurrencyRepositoryContract
{
    const DEFAULT_CURRENCIES = [
        "GBP",
        "USD",
        "CAD",
        "EUR",
        "AUD",
        "JPY"
    ];

    const MINOR_CURRENCIES = [
        'CZK',
        'CLP',
        'CZQ',
        'DKK',
        'PKR',
        'NZD',
        'NOK',
        'IDR',
        'HUF',
        'INR',
        'IDR',
        'ILS',
        'RM',
        'HKD',
        'ILS',
        'MXS',
        'NOK',
        'SGD',
        'PKR',
        'CHF',
        'TWD',
        'BRL',
        'DKK',
        'HUF',
        'MYR',
        'NZD',
        'PHP',
        'SEK',
        'THB',
        'PLN',
        'CNY',
        'RUB',
        'KRW',
        'SEK',
        'THB',
        'TRY',
        'CHF'
    ];

    /**
     *
     * @var CurrencyRepository
     */
    private static $instance;

    /**
     *
     * @var Currency
     */
    private $userCurrency;

    /**
     * @see CurrencyRepository::getInstance()
     */
    public static function getInstance(): CurrencyRepository
    {
        if (!isset(static::$instance)) {
            static::$instance = new CurrencyRepository();
        }

        return static::$instance;
    }

    /**
     * @return Currency[]|CurrencyRepository[]|Collection
     */
    public function all()
    {
        return Currency::all();
    }

    /**
     * @return CurrencyRepository[]|Collection
     */
    public function getDefaultCurrencies()
    {
        return $this->findByCodes(self::DEFAULT_CURRENCIES);
    }

    public function findByCode($code)
    {
        return Currency::where('code', $code)->first();
    }

    public function find($id)
    {
        return Currency::find($id);
    }

    /**
     * @param array $codes
     * @return CurrencyRepository[]|\Illuminate\Database\Eloquent\Collection
     */
    public function findByCodes(array $codes)
    {
        return Currency::whereIn('code', $codes)
            ->orderBy('title')
            ->get();
    }

    public function getMinorCurrencies()
    {
        return $this->findByCodes(self::MINOR_CURRENCIES);
    }

    public function save($currency)
    {
        $currency->save();

        return $currency;
    }

    public function isActive($code): bool
    {
        $code = $code ?: $this->getUserCurrency();

        return $code === $this->getUserCurrency()->code;
    }

    public function getUserCurrency(): Currency
    {
        if (is_null($this->userCurrency)) {
            return $this->getDefaultCurrency();
        }

        return $this->userCurrency;
    }

    public function setUserCurrency($currency)
    {
        $this->userCurrency = $currency;
    }

    public function getDefaultCurrency(): Currency
    {
        return $this->findByCode(config('app.location.currency'));
    }

    /**
     *
     * @param Currency $currency
     */
    public function getRate($currency)
    {
        $rate = 1 / $currency->rate;
        $rate = $rate * $this->rate;

        return $rate;
    }
}
