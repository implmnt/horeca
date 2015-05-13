<?php namespace Macrobit\FoodCatalog\Models;

use Model;

/**
 * Price Model
 */
class Price extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'macrobit_foodcatalog_prices';

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
    public $belongsTo = [];
    public $belongsToMany = [
        'tags' => ['Macrobit\FoodCatalog\Models\Tag', 'table' => 'macrobit_foodcatalog_price_tags']
    ];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [
        'images' => ['System\Models\File']
    ];

}