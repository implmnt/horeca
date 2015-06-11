<?php namespace Macrobit\Horeca\Models;

use Model, BackendAuth;

/**
 * Placement Model
 */
class Placement extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'macrobit_horeca_placements';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Jsonable fields
     */
    protected $jsonable = [
        'tables'
    ];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [
        'tables' => ['Macrobit\Horeca\Models\Table', 'key' => 'placement_id']
    ];
    public $belongsTo = [
        'firm' => ['Macrobit\Horeca\Models\Firm']
    ];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

    public function beforeCreate()
    {
        $user = BackendAuth::getUser();
        if (!$user->hasAnyAccess(['macrobit.horeca.manager']))
        {
            ($firm = $user->firm) != null && $this->firm_id = $user->firm->id;
        }
    }

    public function afterDelete()
    {
        $this->tables()->delete();
    }

}