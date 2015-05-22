<?php namespace Macrobit\FoodCatalog\Models;

use Model, BackendAuth;
use Macrobit\FoodCatalog\Models\Tag as TagModel;

/**
 * Node Model
 */
class Node extends Model
{
    use \October\Rain\Database\Traits\NestedTree;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'macrobit_foodcatalog_nodes';

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
        'firm' => ['Macrobit\FoodCatalog\Models\Firm']
    ];
    public $belongsToMany = [
        'tags' => ['Macrobit\FoodCatalog\Models\Tag', 'table' => 'macrobit_foodcatalog_node_tags']
    ];
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

    public function getTagOptions()
    {
        return TagModel::where('type', '=', 'price')->get();
    }
}