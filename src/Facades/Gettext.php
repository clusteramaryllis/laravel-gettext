<?php

namespace Clusteramaryllis\Gettext\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Clusteramaryllis\Gettext\Gettext
 */
class Gettext extends Facade
{
    /**
     * {@inheritDoc}
     */
    protected static function getFacadeAccessor()
    {
        return 'gettext';
    }
}
