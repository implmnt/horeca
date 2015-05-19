<?php namespace Macrobit\FoodCatalog\Controllers;

use BackendMenu, View, Response;
use Backend\Classes\Controller;
use Macrobit\FoodCatalog\Classes\AccessService;

/**
 * Prices Back-end Controller
 */
class Prices extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Macrobit.FoodCatalog', 'foodcatalog', 'prices');
    }

    public function index()
    {
        if (AccessService::noFirmsAssigned()) 
            return Response::make(View::make('macrobit.foodcatalog::no_firm_assigned'), 403);

        $this->asExtension('ListController')->index();
    }

    public function update($recordId = null, $context = null)
    {
        if (AccessService::noFirmsAssigned()) 
            return Response::make(View::make('macrobit.foodcatalog::no_firm_assigned'), 403);

        $this->asExtension('FormController')->update($recordId, $context);        
    }

    public function listExtendQuery($query)
    {
       if (!$this->user->hasAnyAccess(['macrobit.foodcatalog.access_manage_firms'])) {
            $query->whereHas('firm', function($q)
            {
                $q->where('id', '=', $this->user->firm->id);   
            });
       }
    }
    
}