<?php

namespace App\Services\CurrencyExchange;

use Exception;
use ReflectionClass;
use ReflectionException;

class CurrencyExchange
{
    /**
     * @param $gateway
     *
     * @return object
     * @throws ReflectionException
     * @throws Exception
     */
    public static function getInstance($gateway): object
    {
        $class = '\App\Services\CurrencyExchange\\' . $gateway . 'Exchange';
        $reflector = new ReflectionClass($class);

        if (!$reflector->isInstantiable()) {
            throw new Exception("Currency Exchange gateway [$class] is not instantiable.");
        }

        return $reflector->newInstance();
    }
}
