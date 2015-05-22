<?php namespace Macrobit\FoodCatalog\Models;

use Model;

/**
 * Tag Model
 */
class Tag extends Model
{
    use \Macrobit\FoodCatalog\Traits\Restable;

    public static $REST_PATH_NAME = 'tags';

    /**
     * @var string The database table used by the model.
     */
    public $table = 'macrobit_foodcatalog_tags';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'name',
        'type'
    ];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [];
    public $belongsToMany = [
        'prices' => ['Macrobit\FoodCatalog\Models\Price', 'table' => 'macrobit_foodcatalog_price_tags'],
        'nodes' => ['Macrobit\FoodCatalog\Models\Node', 'table' => 'macrobit_foodcatalog_node_tags'],
        'firms' => ['Macrobit\FoodCatalog\Models\Firm', 'table' => 'macrobit_foodcatalog_firm_tags']
    ];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

}