<?php

namespace Czemu\Simplestats\Facades;

use Illuminate\Support\Facades\Facade;

class Simplestats extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'simplestats';
    }
}
