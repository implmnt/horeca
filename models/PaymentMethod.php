<?php namespace Macrobit\Horeca\Models;

use Model;

/**
 * PaymentMethod Model
 */
class PaymentMethod extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'macrobit_horeca_payment_methods';

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
    public $hasOne = [
        'payment_info' => ['Macrobit\Horeca\Models\PaymentInfo']
    ];
    public $hasMany = [];
    public $belongsTo = [];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

}