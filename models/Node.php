<?php namespace Macrobit\Horeca\Models;

use Model, BackendAuth;
use Macrobit\Horeca\Models\Tag as TagModel;

/**
 * Node Model
 */
class Node extends Model
{
    use \October\Rain\Database\Traits\NestedTree;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'macrobit_horeca_nodes';

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
        'firm' => ['Macrobit\Horeca\Models\Firm']
    ];
    public $belongsToMany = [
        'tags' => ['Macrobit\Horeca\Models\Tag', 'table' => 'macrobit_horeca_node_tags']
    ];
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

    public function getTagOptions()
    {
        return TagModel::where('type', '=', 'price')->get();
    }
}