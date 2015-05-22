<?php namespace Macrobit\FoodCatalog\Controllers;

use BackendMenu, Redirect, Flash, View, Response;
use Backend\Classes\Controller;
use Macrobit\FoodCatalog\Models\Firm;
use Macrobit\FoodCatalog\Classes\AccessService;

/**
 * Firms Back-end Controller
 */
class Firms extends Controller
{

    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        'Backend.Behaviors.RelationController'
    ];

    public $requiredPermissions = ['macrobit.foodcatalog.access_manage_firms', 'macrobit.foodcatalog.access_firms'];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';
    public $relationConfig = 'config_relation.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Macrobit.FoodCatalog', 'foodcatalog', 'firms');
    }

    public function index()
    {
        if (AccessService::noFirmsAssigned()) 
            return Response::make(View::make('macrobit.foodcatalog::no_firm_assigned'), 403);
        else if (!$this->user->hasAnyAccess(['macrobit.foodcatalog.access_manage_firms']))
            return Redirect::to('backend/macrobit/foodcatalog/firms/update/' . $this->user->firm->id);

        $this->asExtension('ListController')->index();
    }

    public function listExtendQuery($query)
    {
       if (!$this->user->hasAnyAccess(['macrobit.foodcatalog.access_manage_firms'])) {
            $query->whereHas('users', function($q)
            {
                $q->where('id', '=', $this->user->id);
            });  
       }
    }    

    public function formExtendQuery($query)
    {
       if (!$this->user->hasAnyAccess(['macrobit.foodcatalog.access_manage_firms'])) {
            $query->whereHas('users', function($q)
            {
                $q->where('id', '=', $this->user->id);
            });  
       }
    }

}