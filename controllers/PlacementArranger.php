<?php namespace Macrobit\FoodCatalog\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Request;
use Macrobit\FoodCatalog\Models\Placement;
use Macrobit\FoodCatalog\Models\Table;

/**
 * PlacementArranger Back-end Controller
 */
class PlacementArranger extends Controller
{

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Macrobit.FoodCatalog', 'foodcatalog', 'placements');

        $this->addCss('/plugins/macrobit/foodcatalog/assets/css/placementarranger.css');
        $this->addJs('/plugins/macrobit/foodcatalog/assets/vendor/jquery-ui/jquery-ui.min.js');
        $this->addJs('/plugins/macrobit/foodcatalog/assets/vendor/placements/workspace.js');
        $this->addJs('/plugins/macrobit/foodcatalog/assets/js/placementarranger.js');
    }

    public function index()
    {
        $this->vars['value'] = $this->fetchData();
    }

    public function index_onSave()
    {
        $data = json_decode(Request::input('data'));
        foreach ($data as $key => $value) {
            $placement = new Placement;
            $placement->name = $value->name;
            $placement->properties = json_encode($value->properties);
            $placement->save();
            $this->attachTables($placement, $value->tables);
        }
    }

    public function attachTables($placement, $arr)
    {
        foreach ($arr as $key => $value) {
            $table = new Table;
            $table->name = $value->name;
            $table->properties = json_encode($value->properties);
            $table->save();
            $placement->tables()->add($table);
        }
    }

    public function fetchData()
    {
        $data = Placement::with('tables')->get();
        foreach ($data as $key => $value) {
            $value->properties = json_decode($value->properties);
            foreach ($value->tables as $key => $value) {
                $value->properties = json_decode($value->properties);
            }
        }
        return $data;
    }

}