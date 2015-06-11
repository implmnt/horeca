<?php namespace Macrobit\Horeca\Models;

use Model, BackendAuth;
use Macrobit\Horeca\Models\Tag as TagModel;

/**
 * Price Model
 */
class Price extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'macrobit_horeca_prices';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'name',
        'description',
        'tags',
        'ingredients',
        'portion',
        'cost',
        'is_new',
        'is_sale',
        'firm_id',
        'id'
    ];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [
        'comments' => ['Macrobit\Horeca\Models\Comment']
    ];
    public $belongsTo = [
        'firm' => ['Macrobit\Horeca\Models\Firm']
    ];
    public $belongsToMany = [
        'tags' => ['Macrobit\Horeca\Models\Tag', 'table' => 'macrobit_horeca_price_tags'],
        'orders' => ['Macrobit\Horeca\Models\Order', 'table' => 'macrobit_horeca_order_prices']
    ];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [
        'images' => ['System\Models\File']
    ];

    public function getTagOptions()
    {
        return TagModel::where('type', '=', 'price')->get();
    }

}