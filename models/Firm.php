<?php namespace Macrobit\FoodCatalog\Models;

use Model, BackendAuth, ApplicationException;
use Macrobit\FoodCatalog\Models\Tag as TagModel;

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
     * @var array Jsonable fields
     */
    protected $jsonable = [
        'day_activity_period'
    ];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [
        'users' => ['Backend\Models\User'],
        'nodes' => ['Macrobit\FoodCatalog\Models\Node'],
        'prices' => ['Macrobit\FoodCatalog\Models\Price'],
        'placements' => ['Macrobit\FoodCatalog\Models\Placement']
    ];
    public $belongsTo = [];
    public $belongsToMany = [
        'tags' => ['Macrobit\FoodCatalog\Models\Tag', 'table' => 'macrobit_foodcatalog_firm_tags']
    ];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];


    public function beforeSave()
    {
        $firms = self::all();
        $modelUserIds = $this->users()->withDeferred($this->sessionKey)->lists('id');
        foreach ($firms as $key => $firm) {
            if ($this->id === $firm->id) continue;
            $firmUserIds = $firm->users()->lists('id');
            $ids = array_intersect($firmUserIds, $modelUserIds);
            if (sizeof($ids) > 0)
            {
                throw new ApplicationException('Users[' . implode(', ', $ids) . '] already have firms');
            }
        }
    }

    public function getTagOptions()
    {
        return TagModel::where('type', '=', 'firm');
    }


}