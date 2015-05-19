<?php namespace Macrobit\FoodCatalog\Models;

use Model, BackendAuth;

/**
 * Placement Model
 */
class Placement extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'macrobit_foodcatalog_placements';

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
        'tables' => ['Macrobit\FoodCatalog\Models\Table', 'key' => 'placement_id']
    ];
    public $belongsTo = [
        'firm' => ['Macrobit\FoodCatalog\Models\Firm']        
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
        if (!$user->hasAnyAccess(['macrobit.foodcatalog.access_manage_firms']))
        {
            ($firm = $user->firm) != null && $this->firm_id = $user->firm->id;
        }
    }

}