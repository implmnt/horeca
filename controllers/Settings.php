<?php namespace Macrobit\Horeca\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use System\Classes\SettingsManager;

/**
 * Settings Back-end Controller
 */
class Settings extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.RelationController'
    ];

    public $formConfig = 'config_form.yaml';
    public $relationConfig = 'config_relation.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('October.System', 'system', 'settings');
        SettingsManager::setContext('Macrobit.Horeca', 'settings');

    }

    public function update()
    {
        return $this->asExtension('FormController')->update(1);
    }

    public function update_onSave()
    {
        return $this->asExtension('FormController')->update_onSave(1);
    }

    public function getCount()
    {
        return $this->asExtension('FormController')->formGetModel()->events()->count();
    }
}