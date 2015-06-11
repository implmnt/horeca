<?php namespace Macrobit\Horeca\Models;

use Model, BackendAuth, ApplicationException;
use Macrobit\Horeca\Models\Tag as TagModel;

/**
 * Firm Model
 */
class Firm extends Model
{    
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'macrobit_horeca_firms';

    /**
     * Validation rules
     */
    public $rules = [
        'name' => 'required|between:2,64|unique:macrobit_horeca_firms'
    ];

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'name',
        'url',
        'address',
        'phone',
        'day_activity_period',
        'is_active'
    ];

    /**
     * @var array Jsonable fields
     */
    protected $jsonable = [
        'day_activity_period',
        'day_break_period',
        'holydays'
    ];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [
        'users' => ['Backend\Models\User'],
        'nodes' => ['Macrobit\Horeca\Models\Node'],
        'prices' => ['Macrobit\Horeca\Models\Price'],
        'placements' => ['Macrobit\Horeca\Models\Placement'],
        'events' => ['Macrobit\Horeca\Models\Event'],
        'comments' => ['Macrobit\Horeca\Models\Comment']
    ];
    public $belongsTo = [];
    public $belongsToMany = [
        'tags' => ['Macrobit\Horeca\Models\Tag', 'table' => 'macrobit_horeca_firm_tags']
    ];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [
        'images' => ['System\Models\File']
    ];

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

    public function afterDelete()
    {
        $this->users()->delete();
        $this->nodes()->delete();
        $this->prices()->delete();
        $this->placements()->delete();
        $this->events()->delete();
        $this->comments()->delete();
        $this->images()->delete();
    }

    public function getTagOptions()
    {
        return TagModel::where('type', '=', 'firm')->get();
    }

}