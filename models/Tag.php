<?php namespace Macrobit\Horeca\Models;

use Model;

/**
 * Tag Model
 */
class Tag extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'macrobit_horeca_tags';

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
        'prices' => ['Macrobit\Horeca\Models\Price', 'table' => 'macrobit_horeca_price_tags'],
        'nodes' => ['Macrobit\Horeca\Models\Node', 'table' => 'macrobit_horeca_node_tags'],
        'firms' => ['Macrobit\Horeca\Models\Firm', 'table' => 'macrobit_horeca_firm_tags']
    ];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

}