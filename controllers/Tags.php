<?php namespace Macrobit\Horeca\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Macrobit\Horeca\Models\Tag as TagModel;

/**
 * Tags Back-end Controller
 */
class Tags extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $requiredPermissions = ['macrobit.horeca.manager'];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Macrobit.Horeca', 'horeca', 'tags');

        $this->addCss('/plugins/macrobit/horeca/controllers/tags/assets/css/styles.css', 'Macrobit.Horeca');
    }

    public function lightedit()
    {
        BackendMenu::setContext('Macrobit.Horeca', 'horeca', 'lightedit');

        $this->pageTitle = 'Tags';

        $toolbarConfig = $this->makeConfig();
        $toolbarConfig->buttons = '@/plugins/macrobit/horeca/controllers/tags/_lightedit_toolbar.htm';

        $this->vars['toolbar'] = $this->makeWidget('Backend\Widgets\Toolbar', $toolbarConfig);
        
        $this->vars['records'] = TagModel::all();
    }

    public function lightedit_onDelete()
    {
        $recordId = post('id');
        $tag = TagModel::find($recordId);
        $tag->delete();

        $this->vars['records'] = TagModel::all();

        return [
            '#records' => $this->makePartial('lightedit_records', $this->vars['records'])
        ];
    }
}