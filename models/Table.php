<?php namespace Macrobit\FoodCatalog\Models;

use Model;

/**
 * Table Model
 */
class Table extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'macrobit_foodcatalog_tables';

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
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

}