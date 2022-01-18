<?php

namespace App\Model\Repositories;

use App\Model\Locations\Currency;
use Illuminate\Database\Eloquent\Collection;

/**
 *
 * @author Mauricio
 *
 */
interface CurrencyContract
{
    /**
     *
     * @return CurrencyContract
     */
    public static function getInstance(): CurrencyContract;

    /**
     *
     * @return static[]|Collection
     */
    public function all();

    /**
     *
     * @param integer $id
     * @return null|Currency
     */
    public function find($id);

    /**
     *
     * @param array $codes
     * @return static[] | Collection
     */
    public function findByCodes(array $codes);

    /**
     *
     * @param string $code
     * @return Currency|null
     */
    public function findByCode($code);

    /**
     *
     * @param Currency $currency
     * @return Currency
     */
    public function save($currency);

    /**
     *
     * @return Collection
     */
    public function getDefaultCurrencies();

    /**
     *
     * @return Collection
     */
    public function getMinorCurrencies();

    /**
     * @return string
     */
    public function getUserCurrency(): Currency;

    /**
     *
     * @param string $code
     * @return bool
     */
    public function isActive($code): bool;

    /**
     *
     * @return Currency
     */
    public function getDefaultCurrency(): Currency;

    /**
     *
     * @param string $currency
     */
    public function setUserCurrency($currency);
}
