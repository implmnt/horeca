<?php namespace Macrobit\Horeca\Models;

use Model;
use BackendAuth;

/**
 * Event Model
 */
class Event extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'macrobit_horeca_events';

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
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [
        'images' => ['System\Models\File']
    ];

    public function beforeCreate()
    {
        $user = BackendAuth::getUser();
        if (!$user->hasAnyAccess(['macrobit.horeca.manager']))
        {
            ($firm = $user->firm) != null && $this->firm_id = $user->firm->id;
        }
    }

}