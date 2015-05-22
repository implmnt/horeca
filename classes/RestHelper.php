<?php namespace Macrobit\FoodCatalog\Classes;

use Str;
use Route;

class RestHelper
{

    public static $PREFIX = 'rest';

    public static function init($restables = [])
    {
        Route::group(['prefix' => self::$PREFIX], function() use ($restables) {

            foreach ($restables as $restable) {
                $class = Str::normalizeClassName($restable);
                $class::makeRestable();
            }

        });
    }

}