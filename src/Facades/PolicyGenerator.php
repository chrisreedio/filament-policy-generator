<?php

namespace ChrisReedIO\PolicyGenerator\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \ChrisReedIO\PolicyGenerator\PolicyGenerator
 */
class PolicyGenerator extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \ChrisReedIO\PolicyGenerator\PolicyGenerator::class;
    }
}
