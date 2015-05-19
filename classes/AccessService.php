<?php namespace Macrobit\FoodCatalog\Classes;

use BackendAuth;

class AccessService
{
    public static function noFirmsAssigned($user = null)
    {
        if ($user == null) $user = BackendAuth::getUser();
        if (!$user->hasAnyAccess(['macrobit.foodcatalog.access_manage_firms']) 
            && $user->firm == null) return true;
        return false;
    }
}