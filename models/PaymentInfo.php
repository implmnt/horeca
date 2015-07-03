<?php namespace Macrobit\Horeca\Models;

use Model;

/**
 * PaymentInfo Model
 */
class PaymentInfo extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'macrobit_horeca_payment_infos';

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
        'order' => ['Macrobit\Horeca\Models\Order'],
        'payment_method' => ['Macrobit\Horeca\Models\PaymentMethod']
    ];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

}