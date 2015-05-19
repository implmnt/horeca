<?php namespace Macrobit\FoodCatalog\Controllers;

use BackendMenu, Lang, Flash, Backend, Response, View;
use Backend\Classes\Controller;
use Macrobit\FoodCatalog\Models\Node;
use Macrobit\FoodCatalog\Classes\AccessService;

/**
 * Nodes Back-end Controller
 */
class Nodes extends Controller
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

        BackendMenu::setContext('Macrobit.FoodCatalog', 'foodcatalog', 'nodes');

        $this->addJs('/plugins/macrobit/foodcatalog/assets/js/macrobit.nodemanager.js');
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

    public function formExtendQuery($query)
    {
       if (!$this->user->hasAnyAccess(['macrobit.foodcatalog.access_manage_firms'])) {
            $query->whereHas('firm', function($q)
            {
                $q->where('id', '=', $this->user->firm->id);   
            });
       }
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
    
    /**
     * Deleted checked nodes.
     */
    public function index_onDelete()
    {
        if (($checkedIds = post('checked')) && is_array($checkedIds) && count($checkedIds)) {

            foreach ($checkedIds as $nodeId) {
                if (!$node = Node::find($nodeId)) continue;
                $node->delete();
            }

            Flash::success(Lang::get('macrobit.foodcatalog::lang.nodes.delete_selected_success'));
        }
        else {
            Flash::error(Lang::get('macrobit.foodcatalog::lang.nodes.delete_selected_empty'));
        }

        return $this->listRefresh();
    }

    /**
     * Displays the node items in a tree list view so they can be reordered
     */
    public function reorder()
    {
        if (AccessService::noFirmsAssigned()) 
            return Response::make(View::make('macrobit.foodcatalog::no_firm_assigned'), 403);

        // Ensure the correct sidemenu is active
        BackendMenu::setContext('Macrobit.FoodCatalog', 'foodcatalog', 'reorder');

        $this->pageTitle = Lang::get('macrobit.foodcatalog::lang.nodes.reordernodes');

        $toolbarConfig = $this->makeConfig();
        $toolbarConfig->buttons = '@/plugins/macrobit/foodcatalog/controllers/nodes/_reorder_toolbar.htm';

        $this->vars['toolbar'] = $this->makeWidget('Backend\Widgets\Toolbar', $toolbarConfig);
        $rootNodes = Node::make()->getEagerRoot();
        if (!$this->user->hasAnyAccess(['macrobit.foodcatalog.access_manage_firms'])) {
            $userNodes = [];
            foreach ($rootNodes as $key => $node) {
                if ($node->firm && $node->firm->id === $this->user->firm->id)
                    array_push($userNodes, $node);
            }
            $this->vars['records'] = $userNodes;
        } else {
            $this->vars['records'] = $rootNodes;
        }
    }

    /**
     * Update the node item position
     */
    public function reorder_onMove()
    {
        $sourceNode = Node::find(post('sourceNode'));
        $targetNode = post('targetNode') ? Node::find(post('targetNode')) : null;

        if ($sourceNode == $targetNode) {
            return;
        }

        switch (post('position')) {
            case 'before':
                $sourceNode->moveBefore($targetNode);
                break;
            case 'after':
                $sourceNode->moveAfter($targetNode);
                break;
            case 'child':
                $sourceNode->makeChildOf($targetNode);
                break;
            default:
                $sourceNode->makeRoot();
                break;
        }
    }

}