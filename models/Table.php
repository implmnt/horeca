<?php namespace Macrobit\Horeca\Models;

use Model;

/**
 * Table Model
 */
class Table extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'macrobit_horeca_tables';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'name'
    ];

    /**
     * @var array Jsonable fields
     */
    protected $jsonable = [
        'position'
    ];

    /**
     * @var array Visible fields
     */
    protected $visible = [
        'id', 'name', 'position'
    ];

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

    public function beforeCreate()
    {
        $this->position = ['top' => 0, 'left' => 0];
    }

    public function beforeSave()
    {
        if (is_string($this->position))
            $this->position = json_decode($this->position);
    }

}