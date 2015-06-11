<?php namespace Macrobit\Horeca\Controllers;

use BackendMenu, BackendAuth, Redirect, Flash, View, Response;
use Backend\Classes\Controller;
use Macrobit\Horeca\Models\Firm;

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

    public $requiredPermissions = ['macrobit.horeca.manager', 'macrobit.horeca.operator'];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';
    public $relationConfig = 'config_relation.yaml';

    public $bodyClass = 'compact-container';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Macrobit.Horeca', 'horeca', 'firms');
    }

    public function index()
    {
        if (!$this->hasBusiness()) {
            BackendAuth::logout();
            return Response::make(View::make('backend::access_denied'), 403);
        }
        else if (!$this->user->hasAnyAccess(['macrobit.horeca.manager'])) {
            return Redirect::to('backend/macrobit/horeca/firms/update/' . $this->user->firm->id);
        }

        $this->asExtension('ListController')->index();
    }

    public function listExtendQuery($query)
    {
       $this->extendQuery($query);
    }    

    public function formExtendQuery($query)
    {
        $this->extendQuery($query);
    }

    private function hasBusiness()
    {
        if (!$this->user->hasAnyAccess(['macrobit.horeca.manager']) 
            && $this->user->firm == null) return false;
        return true;
    }

    private function extendQuery($query)
    {
       if (!$this->user->hasAnyAccess(['macrobit.horeca.manager'])) {
            $query->whereHas('users', function($q)
            {
                $q->where('id', '=', $this->user->id);
            });  
       }
    }

}