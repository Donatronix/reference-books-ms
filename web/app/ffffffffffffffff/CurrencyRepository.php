<?php

namespace App\Model\Repositories;

use App\Model\Locations\Currency;

/**
 *
 * @author Mauricio
 *
 */
class CurrencyRepository implements CurrencyContract
{
    const DEFAULT_CURRENCIES = array(
        "GBP",
        "USD",
        "CAD",
        "EUR",
        "AUD",
        "JPY"
    );

    const MINOR_CURRENCIES = array(
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
    );

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
     */
    protected function __construct()
    {
    }

    /**
     * (non-PHPdoc)
     *
     * @see \App\Model\Repositories\CurrencyContract::getInstance()
     */
    public static function getInstance(): CurrencyContract
    {
        if (!isset(static::$instance)) {
            static::$instance = new CurrencyRepository();
        }

        return static::$instance;
    }

    /**
     * (non-PHPdoc)
     *
     * @see \App\Model\Repositories\CurrencyContract::all()
     */
    public function all()
    {
        return Currency::all();
    }

    public function getDefaultCurrencies()
    {
        return $this->findByCodes(self::DEFAULT_CURRENCIES);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \App\Model\Repositories\CurrencyContract::findByCodes()
     */
    public function findByCodes(array $codes)
    {
        return Currency::whereIn('code', $codes)->orderBy('name')->get();
    }

    public function getMinorCurrencies()
    {
        return $this->findByCodes(self::MINOR_CURRENCIES);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \App\Model\Repositories\CurrencyContract::find()
     */
    public function find($id)
    {
        return Currency::find($id);
    }

    /**
     * (non-PHPdoc)
     *
     * @see \App\Model\Repositories\CurrencyContract::save()
     */
    public function save($currency)
    {
        $currency->save();

        return $currency;
    }

    /**
     *
     * {@inheritdoc}
     * @see \App\Model\Repositories\CurrencyContract::isActive()
     */
    public function isActive($code): bool
    {
        $code = $code ?: $this->getUserCurrency();

        return $code === $this->getUserCurrency()->code;
    }

    /**
     *
     * {@inheritdoc}
     * @see \App\Model\Repositories\CurrencyContract::getUserCurrency()
     */
    public function getUserCurrency(): Currency
    {
        if (is_null($this->userCurrency)) {
            return $this->getDefaultCurrency();
        }

        return $this->userCurrency;
    }

    /**
     *
     * {@inheritdoc}
     * @see \App\Model\Repositories\CurrencyContract::setUserCurrency()
     */
    public function setUserCurrency($currency)
    {
        $this->userCurrency = $currency;
    }

    /**
     *
     * {@inheritdoc}
     * @see \App\Model\Repositories\CurrencyContract::getDefaultCurrency()
     */
    public function getDefaultCurrency(): Currency
    {
        return $this->findByCode(config('app.location.currency'));
    }

    /**
     *
     * {@inheritdoc}
     * @see \App\Model\Repositories\CurrencyContract::findByCode()
     */
    public function findByCode($code)
    {
        return Currency::where('code', $code)->first();
    }

    protected function __clone()
    {
    }
}
