<?php namespace Macrobit\Horeca\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Payment Methods Back-end Controller
 */
class PaymentMethods extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public $requiredPermissions = ['macrobit.horeca.manager'];

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Macrobit.Horeca', 'horeca', 'paymentmethods');
    }
}