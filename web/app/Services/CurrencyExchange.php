<?php

namespace App\Services;

use Exception;
use ReflectionClass;
use ReflectionException;

class CurrencyExchange
{
    /**
     * @param $provider
     *
     * @return object
     * @throws ReflectionException
     * @throws Exception
     */
    public static function getInstance($provider): object
    {
        $class = '\App\Services\CurrencyExchange\\' . $provider . 'Exchange';
        $reflector = new ReflectionClass($class);

        if (!$reflector->isInstantiable()) {
            throw new Exception("Currency Exchange service [$class] is not instantiable.");
        }

        return $reflector->newInstance();
    }
}
