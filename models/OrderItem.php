<?php namespace Macrobit\Horeca\Models;

use Model;

/**
 * OrderItem Model
 */
class OrderItem extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'macrobit_horeca_order_items';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [
        'price' => ['Macrobit\Horeca\Models\Price']
    ];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

}