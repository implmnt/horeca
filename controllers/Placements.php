<?php namespace Macrobit\Horeca\Controllers;

use BackendMenu, Response, View;
use Backend\Classes\Controller;
use Macrobit\Horeca\Models\Placement as PlacementModel;
use Macrobit\Horeca\Models\Table as TableModel;


/**
 * Placements Back-end Controller
 */
class Placements extends Controller
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


    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Macrobit.Horeca', 'horeca', 'placements');

        $this->loadAssets();
    }

    public function loadAssets()
    {
        $this->addCss('/plugins/macrobit/horeca/assets/css/macrobit.tablesarranger.css');
        $this->addJs('/plugins/macrobit/horeca/assets/vendor/jquery-ui/jquery-ui.min.js');
        $this->addJs('/plugins/macrobit/horeca/assets/js/macrobit.tablesarranger.js');
    }

    public function update($recordId, $context = null)
    {
        if (!$this->hasBusiness()) {
            BackendAuth::logout();
            return Response::make(View::make('backend::access_denied'), 403);
        }

        $this->vars['placement'] = ($placement = PlacementModel::find($recordId));
        $this->vars['tables'] = $placement->tables;

        return $this->asExtension('FormController')->update($recordId, $context);
    }

    public function index()
    {
        if (!$this->hasBusiness()) {
            BackendAuth::logout();
            return Response::make(View::make('backend::access_denied'), 403);
        }
        $this->asExtension('ListController')->index();
    }

    public function update_onSave($recordId = null)
    {
        $tablesData = post('tables');
        if (is_array($tablesData))
        {
            foreach ($tablesData as $tableJson) {
                $tableData = json_decode($tableJson);
                $table = TableModel::find($tableData->id);
                $table->position = $tableData->position;
                $table->save();
            }
        }
        $result = $this->asExtension('FormController')->update_onSave($recordId);
        if (!$redirect = $this->makeRedirect('update', ($placement = PlacementModel::find($recordId)))) {
            $result['#tables-arranger-container'] = $this->makePartial('tables_arranger', 
                ['placement' => $placement, 'tables' => $placement->tables]);

        }
        return $result;
    }

    /**
     * Update tables_arranger partial after the record has been deleted.
     */
    public function update_onRelationButtonDelete($recordId, $context = null)
    {
        $result = $this->asExtension('RelationController')->
            onRelationButtonDelete($recordId, $context);
        $result['#tables-arranger-container'] = $this->makePartial('tables_arranger', 
            ['placement' => ($placement = PlacementModel::find($recordId)), 'tables' => $placement->tables]);
        return $result;
    }    

    /**
     * Update tables_arranger partial after the record has been created.
     */
    public function update_onRelationManageCreate($recordId, $context = null)
    {
        $result = $this->asExtension('RelationController')->
            onRelationManageCreate($recordId, $context);
        $result['#tables-arranger-container'] = $this->makePartial('tables_arranger', 
            ['placement' => ($placement = PlacementModel::find($recordId)), 'tables' => $placement->tables]);
        return $result;
    }

    /**
     * Update tables_arranger partial after the record has been updated.
     */
    public function update_onRelationManageUpdate($recordId, $context = null)
    {
        $result = $this->asExtension('RelationController')->
            onRelationManageUpdate($recordId, $context);
        $result['#tables-arranger-container'] = $this->makePartial('tables_arranger', 
            ['placement' => ($placement = PlacementModel::find($recordId)), 'tables' => $placement->tables]);
        return $result;
    }

    private function hasBusiness()
    {
        if (!$this->user->hasAnyAccess(['macrobit.horeca.manager']) 
            && $this->user->firm == null) return false;
        return true;
    }
}