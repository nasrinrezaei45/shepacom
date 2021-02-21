<?php


namespace NasrinRezaei45\Shepacom;


use Illuminate\Support\Facades\Facade;

class ShepaFacade extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'shepa';
    }
}