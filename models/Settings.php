<?php namespace Macrobit\Horeca\Models;

use Lang;
use Model;
use Macrobit\Horeca\Models\PaymentMethod;

class Settings extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'macrobit_horeca_settings';

    public $fillable = [
        'payments'
    ];

    public $belongsToMany = [
        'payments'  => [
            'Macrobit\Horeca\Models\PaymentMethod', 
            'table'     => 'macrobit_horeca_settings_paymentmethods', 
            'key'       => 'settings', 
            'otherKey'  => 'payment'
        ],
        'events'    => [
            'Macrobit\Horeca\Models\Event',
            'table'     => 'macrobit_horeca_settings_events',
            'key'       => 'settings',
            'otherKey'  => 'event'
        ],
        'slider'    => [
            'Macrobit\Horeca\Models\Event',
            'table'     => 'macrobit_horeca_settings_slider',
            'key'       => 'settings',
            'otherKey'  => 'event'
        ]
    ];

    public function getPaymentsOptions()
    {
        $options = [];
        foreach (PaymentMethod::all() as $pm) {
            $options[$pm->id] = $pm->name;
        }
        return $options;
    }

    public function getDefaultPaymentOptions()
    {
        $options = [];
        foreach ($this->payments as $pm) {
            $options[$pm->id] = $pm->name;
        }
        return $options;
    }

    public function get($property)
    {
        return $this[$property];
    }

    public function set($property, $value)
    {
        $this[$property] = $value;
    }
}