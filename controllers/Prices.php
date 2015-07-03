<?php namespace Macrobit\Horeca\Controllers;

use BackendMenu, View, Response;
use Backend\Classes\Controller;

/**
 * Prices Back-end Controller
 */
class Prices extends Controller
{
    use \Macrobit\Horeca\Traits\Security;

    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        'Backend.Behaviors.RelationController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';
    public $relationConfig = 'config_relation.yaml';
    
    public $bodyClass = 'compact-container';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Macrobit.Horeca', 'horeca', 'prices');
        $this->addCss('/plugins/macrobit/horeca/assets/css/macrobit.horeca.css', 'Macrobit.Horeca');
    }

    public function index()
    {
        if (!$this->hasBusiness()) {
            BackendAuth::logout();
            return Response::make(View::make('backend::access_denied'), 403);
        }

        $this->asExtension('ListController')->index();
    }

    public function update($recordId = null, $context = null)
    {
        if (!$this->hasBusiness()) {
            BackendAuth::logout();
            return Response::make(View::make('backend::access_denied'), 403);
        }

        $this->asExtension('FormController')->update($recordId, $context);
    }

    private function hasBusiness()
    {
        if (!$this->user->hasAnyAccess(['macrobit.horeca.manager']) 
            && $this->user->firm == null) return false;
        return true;
    }

}