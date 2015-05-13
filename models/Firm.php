<?php namespace Macrobit\FoodCatalog\Models;

use Model;

/**
 * Firm Model
 */
class Firm extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'macrobit_foodcatalog_firms';

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
    public $hasMany = [
        'users' => ['Backend\Models\User']
    ];
    public $belongsTo = [];
    public $belongsToMany = [
    //    'users' => ['Backend\Models\User', 'table' => 'macrobit_foodcatalog_firms_users'],
        'nodes' => ['Macrobit\FoodCatalog\Models\Node', 'table' => 'macrobit_foodcatalog_node_firms']
    ];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

}