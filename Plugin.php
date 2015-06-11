<?php namespace Macrobit\Horeca;

use Lang;
use Auth;
use Route;
use Backend;
use BackendAuth;
use Backend\Models\User as UserModel;
use Backend\Models\UserGroup as UserGroupModel;
use System\Classes\PluginBase;
use Macrobit\Horeca\Classes\RESTServiceProvider;

/**
 * Horeca Plugin Information File
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
            'name'        => Lang::get('macrobit.horeca::lang.plugin.label'),
            'description' => Lang::get('macrobit.horeca::lang.plugin.description'),
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
            /**
             * Tab Horeca
             */
            'macrobit.horeca.operator'  => [
                'label' => Lang::get('macrobit.horeca::lang.plugin.permissions.operator'), 
                'tab' => 'Horeca'
            ],
            'macrobit.horeca.manager'   => [
                'label' => Lang::get('macrobit.horeca::lang.plugin.permissions.manager'), 
                'tab' => 'Horeca'
            ],

            /**
             * Tab Horeca REST
             */
            'macrobit.horeca.access_rest.get'       => [
                'label' => 'GET', 
                'tab' => 'Horeca REST'
            ],
            'macrobit.horeca.access_rest.post'      => [
                'label' => 'POST', 
                'tab' => 'Horeca REST'
            ],
            'macrobit.horeca.access_rest.delete'    => [
                'label' => 'DELETE', 
                'tab' => 'Horeca REST'
            ],
            'macrobit.horeca.access_rest.put'       => [
                'label' => 'PUT', 
                'tab' => 'Horeca REST'
            ]
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
            'horeca' => [
                'label'         =>  Lang::get('macrobit.horeca::lang.plugin.label'),
                'url'           =>  Backend::url('macrobit/horeca/firms'),
                'icon'          =>  'icon-cutlery',
                'permissions'   =>  ['macrobit.horeca.operator', 'macrobit.horeca.manager'],
                
                'sideMenu'      =>  [

                    'firm'              =>  [
                        'label'             =>  Lang::get('macrobit.horeca::lang.firm.label'),
                        'url'               =>  Backend::url('macrobit/horeca/firms'),
                        'icon'              =>  'icon-building',
                        'permissions'       =>  ['macrobit.horeca.operator', 'macrobit.horeca.manager']
                    ],
                    'tag'               =>  [
                        'label'             =>  Lang::get('macrobit.horeca::lang.tag.label'),
                        'url'               =>  Backend::url('macrobit/horeca/tags'),
                        'icon'              =>  'icon-tag',
                        'permissions'       =>  ['macrobit.horeca.manager']
                    ],
                    'price'             => [
                        'label'             =>  Lang::get('macrobit.horeca::lang.price.label'),
                        'url'               =>  Backend::url('macrobit/horeca/prices'),
                        'icon'              =>  'icon-copy',
                        'permissions'       =>  ['macrobit.horeca.operator', 'macrobit.horeca.manager']
                    ],
                    'node'              => [
                        'label'             =>  Lang::get('macrobit.horeca::lang.node.label'),
                        'url'               =>  Backend::url('macrobit/horeca/nodes'),
                        'icon'              =>  'icon-list-ul',
                        'permissions'       =>  ['macrobit.horeca.operator', 'macrobit.horeca.manager']
                    ],
                    'placement'         => [
                        'label'             =>  Lang::get('macrobit.horeca::lang.placement.label'),
                        'url'               =>  Backend::url('macrobit/horeca/placements'),
                        'icon'              =>  'icon-square',
                        'permissions'       =>  ['macrobit.horeca.operator', 'macrobit.horeca.manager']
                    ],
                    'event'             => [
                        'label'             =>  Lang::get('macrobit.horeca::lang.event.label'),
                        'url'               =>  Backend::url('macrobit/horeca/events'),
                        'icon'              =>  'icon-bell',
                        'permissions'       =>  ['macrobit.horeca.operator', 'macrobit.horeca.manager']
                    ],
                    'order'             => [
                        'label'             =>  Lang::get('macrobit.horeca::lang.order.label'),
                        'url'               =>  Backend::url('macrobit/horeca/orders'),
                        'icon'              =>  'icon-shopping-cart',
                        'permissions'       =>  ['macrobit.horeca.operator', 'macrobit.horeca.manager']
                    ],
                    'payment'           => [
                        'label'             =>  Lang::get('macrobit.horeca::lang.payment.label'),
                        'url'               =>  Backend::url('macrobit/horeca/paymentinfos'),
                        'icon'              =>  'icon-money',
                        'permissions'       =>  ['macrobit.horeca.operator', 'macrobit.horeca.manager']
                    ],
                    'payment_method'    => [
                        'label'             =>  Lang::get('macrobit.horeca::lang.payment_method.label'),
                        'url'               =>  Backend::url('macrobit/horeca/paymentmethods'),
                        'icon'              =>  'icon-credit-card',
                        'permissions'       =>  ['macrobit.horeca.manager']
                    ]
                ]
            ]
        ];
    }

    public function registerFormWidgets()
    {
        return [
            'Macrobit\Horeca\FormWidgets\McTagFinder' => [
                'label' => 'Tag Finder',
                'code' => 'mc-tagfinder'
            ],
            'Macrobit\Horeca\FormWidgets\McRangeSlider' => [
                'label' => 'Range Slider',
                'code' => 'mc-rangeslider'
            ]
        ];
    }

    public function registerComponents()
    {
        return [
            '\Macrobit\Horeca\Components\Horeca' => 'horeca'
        ];
    }

    public function boot()
    {
        UserModel::extend(function($model) {
            $model->belongsTo['firm'] = ['Macrobit\Horeca\Models\Firm'];
            $model->addDynamicMethod('listGroupsForFirm', function()
            {
                $result = [];
                $groups = null;
                if (($user = BackendAuth::getUser()) 
                    && (!$user->hasAccess(['macrobit.horeca.manager']))) {
                    $groups = $user->groups;
                } else {
                    $groups = UserGroupModel::all();
                }
                foreach ($groups as $group) {
                    $result[$group->id] = [$group->name, $group->description];
                }
                return $result;
            });
        });

        $rest = RESTServiceProvider::instance();
        $rest->initialize(
        [
            [
                'tags', 'Macrobit\Horeca\Models\Tag', ['GET']
            ],
            [
                'firms', 'Macrobit\Horeca\Models\Firm', ['GET']
            ],
            [
                'comments', 'Macrobit\Horeca\Models\Comment', ['GET']
            ],
            [
                'events', 'Macrobit\Horeca\Models\Event', ['GET']
            ],
            [
                'nodes', 'Macrobit\Horeca\Models\Node', ['GET']
            ],
            [
                'placements', 'Macrobit\Horeca\Models\Placement', ['GET']
            ],
            [
                'tables', 'Macrobit\Horeca\Models\Table', ['GET']
            ],
            [
                'prices', 'Macrobit\Horeca\Models\Price', ['GET']
            ]
        ]);

    }

}
