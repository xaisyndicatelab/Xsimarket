<?php

namespace Xsimarket\Facades;

use Illuminate\Support\Facades\Facade;

class Shop extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'shop';
    }
}
