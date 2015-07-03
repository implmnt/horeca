<?php namespace Macrobit\Horeca\Facades;

use October\Rain\Support\Facade;

class HorecaSettings extends Facade
{
    /**
     * Get the registered name of the component.
     * @return string
     */
    protected static function getFacadeAccessor() { return 'horeca.settings'; }
}
