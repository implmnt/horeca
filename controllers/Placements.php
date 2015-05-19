<?php namespace Macrobit\FoodCatalog\Controllers;

use BackendMenu, Response, View;
use Backend\Classes\Controller;
use Macrobit\FoodCatalog\Models\Placement;
use Macrobit\FoodCatalog\Models\Table;
use Macrobit\FoodCatalog\Classes\AccessService;


/**
 * Placements Back-end Controller
 */
class Placements extends Controller
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

        BackendMenu::setContext('Macrobit.FoodCatalog', 'foodcatalog', 'placements');

        $this->loadAssets();
    }

    public function loadAssets()
    {
        $this->addCss('/plugins/macrobit/foodcatalog/assets/css/macrobit.tablesarranger.css');
        $this->addJs('/plugins/macrobit/foodcatalog/assets/vendor/jquery-ui/jquery-ui.min.js');
        $this->addJs('/plugins/macrobit/foodcatalog/assets/js/macrobit.tablesarranger.js');
    }

    public function update($recordId, $context = null)
    {
        if (AccessService::noFirmsAssigned()) 
            return Response::make(View::make('macrobit.foodcatalog::no_firm_assigned'), 403);

        $this->vars['placement'] = ($placement = Placement::find($recordId));
        $this->vars['tables'] = $placement->tables;

        return $this->asExtension('FormController')->update($recordId, $context);
    }

    public function index()
    {
        if (AccessService::noFirmsAssigned()) 
            return Response::make(View::make('macrobit.foodcatalog::no_firm_assigned'), 403);

        $this->asExtension('ListController')->index();
    }

    public function update_onSave($recordId = null)
    {
        $tablesData = post('tables');
        if (is_array($tablesData))
        {
            foreach ($tablesData as $tableJson) {
                $tableData = json_decode($tableJson);
                $table = Table::find($tableData->id);
                $table->position = $tableData->position;
                $table->save();
            }
        }
        $result = $this->asExtension('FormController')->update_onSave($recordId);
        if (!$redirect = $this->makeRedirect('update', ($placement = Placement::find($recordId)))) {
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
            ['placement' => ($placement = Placement::find($recordId)), 'tables' => $placement->tables]);
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
            ['placement' => ($placement = Placement::find($recordId)), 'tables' => $placement->tables]);
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
            ['placement' => ($placement = Placement::find($recordId)), 'tables' => $placement->tables]);
        return $result;
    }
}