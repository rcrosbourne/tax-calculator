<?php


namespace App\Facades;


use Illuminate\Support\Facades\Facade;

class MoneyConfigurationFacade extends Facade
{
    
    protected static function getFacadeAccessor(): string
    {
        return 'money';
    }
}
