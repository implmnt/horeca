<?php namespace Macrobit\Horeca\Models;

use Model;
use BackendAuth;

/**
 * Comment Model
 */
class Comment extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'macrobit_horeca_comments';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'date',
        'content',
        'rating',
        'state'
    ];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [
        'user' => ['RainLab\User\Models\User'],
        'price' => ['Macrobit\Horeca\Models\Price'],
        'firm' => ['Macrobit\Horeca\Models\Firm']
    ];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

}