<?php namespace Macrobit\FoodCatalog;

use System\Classes\PluginBase;
use Backend;
use Lang;
use Backend\Models\User;
use Backend\Models\UserGroup;
use BackendAuth;

/**
 * FoodCatalog Plugin Information File
 */
class Plugin extends PluginBase
{

    public $require = ['RainLab.User'];

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => Lang::get('macrobit.foodcatalog::lang.plugin.name'),
            'description' => Lang::get('macrobit.foodcatalog::lang.plugin.description'),
            'author'      => 'Macrobit',
            'icon'        => 'icon-cutlery'
        ];
    }

    /**
    * Returns information about permissions.
    *
    * @return array
    */
    public function registerPermissions()
    {
        return [
            'macrobit.foodcatalog.access_tags' => ['label' => 'Tags', 'tab' => 'Manage Food Catalog'],
            'macrobit.foodcatalog.access_manage_firms' => ['label' => 'Manage Firms', 'tab' => 'Manage Food Catalog'],
            'macrobit.foodcatalog.access_firms' => ['label' => 'Firms', 'tab' => 'Manage Food Catalog']
        ];
    }

    /**
     * Return information about plugins menu in backend.
     *
     * @return array
     */
    public function registerNavigation()
    {
        return [
            'foodcatalog' => [
                'label'         =>  Lang::get('macrobit.foodcatalog::lang.plugin.name'),
                'url'           =>  Backend::url('macrobit/foodcatalog/firms'),
                'icon'          =>  'icon-cutlery',
                'permissions'   =>  ['macrobit.foodcatalog.*'],
                'order'         =>  500,

                'sideMenu'  =>  [
                    'firms' =>  [
                        'label'         =>  Lang::get('macrobit.foodcatalog::lang.plugin.controller.firms'),
                        'url'           =>  Backend::url('macrobit/foodcatalog/firms'),
                        'icon'          =>  'icon-building',
                        'permissions'   =>  ['macrobit.foodcatalog.access_manage_firms', 'macrobit.foodcatalog.access_firms']
                    ],
                    'tags' =>  [
                        'label'         =>  Lang::get('macrobit.foodcatalog::lang.plugin.controller.tags'),
                        'url'           =>  Backend::url('macrobit/foodcatalog/tags'),
                        'icon'          =>  'icon-tag',
                        'permissions'   =>  ['macrobit.foodcatalog.access_tags']
                    ],
                    'prices' => [
                        'label'         =>  Lang::get('macrobit.foodcatalog::lang.plugin.controller.prices'),
                        'url'           =>  Backend::url('macrobit/foodcatalog/prices'),
                        'icon'          =>  'icon-copy',
                        'permissions'   =>  ['macrobit.foodcatalog.*'],
                        'order'         => 500
                    ],
                    'nodes' => [
                        'label'         =>  Lang::get('macrobit.foodcatalog::lang.plugin.controller.nodes'),
                        'url'           =>  Backend::url('macrobit/foodcatalog/nodes'),
                        'icon'          =>  'icon-list-ul',
                        'permissions'   =>  ['macrobit.foodcatalog.*'],
                        'order'         => 500
                    ],
                    'placements' => [
                        'label'         =>  Lang::get('macrobit.foodcatalog::lang.plugin.controller.placements'),
                        'url'           =>  Backend::url('macrobit/foodcatalog/placements'),
                        'icon'          =>  'icon-square',
                        'permissions'   =>  ['macrobit.foodcatalog.*'],
                        'order'         => 500
                    ]
                ]
            ]
        ];
    }

    public function registerFormWidgets()
    {
        return [
            'Macrobit\FoodCatalog\FormWidgets\TagFinder' => [
                'label' => 'Tag Finder',
                'code' => 'tagfinder'
            ],
            'Macrobit\FoodCatalog\FormWidgets\McRangeSlider' => [
                'label' => 'Range Slider',
                'code' => 'mc-rangeslider'
            ]
        ];
    }

    public function boot()
    {
        User::extend(function($model) {
            $model->belongsTo['firm'] = ['Macrobit\FoodCatalog\Models\Firm'];
            $model->addDynamicMethod('listGroupsForFirm', function()
            {
                $result = [];
                $groups = null;
                if (($user = BackendAuth::getUser()) && (!$user->hasAccess(['macrobit.foodcatalog.access_manage_firms'])))
                {
                    $groups = $user->groups;
                } else {
                    $groups = UserGroup::all();
                }
                foreach ($groups as $group) {
                    $result[$group->id] = [$group->name, $group->description];
                }
                return $result;
            });
        });
    }

}
