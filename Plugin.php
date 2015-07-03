<?php namespace Macrobit\Horeca;

use App;
use Lang;
use Auth;
use Route;
use Backend;
use BackendAuth;
use Backend\Models\User as BackendUser;
use Backend\Models\UserGroup as BackendUserGroup;
use RainLab\User\Models\User;
use RainLab\User\Controllers\Users as UsersController;
use System\Classes\PluginBase;
use Illuminate\Foundation\AliasLoader;
use Macrobit\Horeca\Classes\RESTServiceProvider;
use Macrobit\Horeca\Classes\RESTManager;
use Macrobit\Horeca\Models\Basket;

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

    public function register()
    {
        $alias = AliasLoader::getInstance();
        $alias->alias('HorecaSettings', 'Macrobit\Horeca\Facades\HorecaSettings');

        App::singleton('horeca.settings', function() {
            return \Macrobit\Horeca\Models\Settings::find(1);
        });

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

    public function registerSettings()
    {
        return [
            'settings' => [
                'label'       => 'macrobit.horeca::lang.settings.menu_label',
                'description' => 'macrobit.horeca::lang.settings.menu_description',
                'category'    => 'macrobit.horeca::lang.settings.horeca',
                'icon'        => 'icon-cutlery',
                'url'         => Backend::url('macrobit/horeca/settings/update'),
                'order'       => 100,
                'permissions' => ['macrobit.horeca.manager'],
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
            '\Macrobit\Horeca\Components\Horeca'     => 'horeca',
            '\Macrobit\Horeca\Components\Basket'     => 'basket',
            '\Macrobit\Horeca\Components\FirmDetail' => 'firm',
            '\Macrobit\Horeca\Components\Order'      => 'order'
        ];
    }

    public function boot()
    {
        BackendUser::extend(function($model){
            $model->belongsTo['firm'] = ['Macrobit\Horeca\Models\Firm'];
            $model->addDynamicMethod('listGroupsForFirm', function(){
                $result = [];
                $groups = null;
                if (($user = BackendAuth::getUser())
                    && (!$user->hasAccess(['macrobit.horeca.manager']))) {
                    $groups = $user->groups;
                } else {
                    $groups = BackendUserGroup::all();
                }
                foreach ($groups as $group) {
                    $result[$group->id] = [$group->name, $group->description];
                }
                return $result;
            });
        });

        User::extend(function($model){
            $model->hasOne['basket'] = ['Macrobit\Horeca\Models\Basket'];
        });

        UsersController::extendFormFields(function($form, $model, $context){
            if (!$model instanceof User) {
                return;
            }

            if (!$model->exists) {
                return;
            }

            Basket::getFromUser($model);

            $form->addTabFields([
                'basket[prices]' => [
                    'label' => Lang::get('macrobit.horeca::lang.field.prices'),
                    'type'  => 'partial',
                    'path'  => '~/plugins/macrobit/horeca/controllers/baskets/_manage_prices.htm',
                    'tab'   => Lang::get('macrobit.horeca::lang.field.basket')
                ]
            ]);
        });

        $restManager = RESTManager::instance();
        $restManager->initialize(
        [
            'Macrobit\Horeca\Models\Firm',
            'Macrobit\Horeca\Models\Price'
        ]);
    }

}
