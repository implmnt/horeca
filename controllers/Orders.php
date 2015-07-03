<?php namespace Macrobit\Horeca\Controllers;

use Lang;
use Flash;
use BackendMenu;
use Backend\Classes\Controller;
use Macrobit\Horeca\Models\Order;

/**
 * Orders Back-end Controller
 */
class Orders extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        'Backend.Behaviors.RelationController'
    ];


    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';
    public $relationConfig = 'config_relation.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Macrobit.Horeca', 'horeca', 'orders');
    }

    /**
     * Deleted checked records.
     */
    public function index_onDelete()
    {
        if (($checkedIds = post('checked')) && is_array($checkedIds) && count($checkedIds)) {

            foreach ($checkedIds as $recordId) {
                if (!$record = Order::find($recordId)) continue;
                $record->delete();
            }

            Flash::success(Lang::get('macrobit.horeca::lang.nodes.delete_selected_success'));
        }
        else {
            Flash::error(Lang::get('macrobit.horeca::lang.nodes.delete_selected_empty'));
        }

        return $this->listRefresh();
    }

    public function update_onPerform($recordId)
    {
        $this->asExtension('FormController')->update_onSave($recordId);
        $model = Order::find($recordId);
        $model->perform();
    }
}