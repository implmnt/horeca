<?php namespace Macrobit\Horeca\Controllers;

use BackendMenu, View, Response;
use Backend\Classes\Controller;

/**
 * Tables Back-end Controller
 */
class Tables extends Controller
{
    use \Macrobit\Horeca\Traits\Security;

    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Macrobit.Horeca', 'horeca', 'tables');
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

        $this->asExtension('ListController')->update($recordId, $context);        
    }

    private function hasBusiness()
    {
        if (!$this->user->hasAnyAccess(['macrobit.horeca.manager']) 
            && $this->user->firm == null) return false;
        return true;
    }
}