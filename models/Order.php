<?php namespace Macrobit\Horeca\Models;

use Model;

/**
 * Order Model
 */
class Order extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'macrobit_horeca_orders';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    public $description = null;

    /**
     * @var array Relations
     */
    public $hasOne = [
        'payment_info' => ['Macrobit\Horeca\Models\PaymentInfo']
    ];
    public $hasMany = [];
    public $belongsTo = [
        'firm' => ['Macrobit\Horeca\Models\Firm'],
        'user' => ['RainLab\User\Models\User'],
        'payment_method' => ['Macrobit\Horeca\Models\PaymentMethod']
    ];
    public $belongsToMany = [
        'prices' => ['Macrobit\Horeca\Models\Price', 'table' => 'macrobit_horeca_order_prices']
    ];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

    public function afterFetch()
    {
        $this->presetFields();
    }

    private function presetFields()
    {
        /**
         * description field
         */
        $description = '';
        traceLog($this->prices);
        if (gettype($this->payment_method) === 'object') {
            $description .= $this->payment_method->name . ';';
        }
        else {
            $description .= $this->payment_method . ';';
        }
        $description .= $this->address . ';';
        if (gettype($this->prices) === 'object') {
            $description .= implode(', ', $this->prices()->lists('name')) . ';';
        }
        else {
            $description .= $this->prices . ';';
        }
        $this->description = $description;
    }

}