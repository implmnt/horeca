<?php namespace Macrobit\Horeca\Models;

use Model;

/**
 * Basket Model
 */
class Basket extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'macrobit_horeca_baskets';

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
        'user' => ['RainLab\User\Models\User']
    ];
    public $belongsToMany = [
        'prices' => ['Macrobit\Horeca\Models\Price', 'table' => 'macrobit_horeca_basket_prices', 'pivot' => ['amount']]
    ];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

    public static function getFromUser($user)
    {
        if ($user->basket) {
            return $user->basket;
        }

        $basket = new static;
        $basket->user = $user;
        $basket->save();

        $user->basket = $basket;

        return $basket;
    }

    public function getSumm()
    {
        $result = 0;
        foreach ($this->prices as $price) {
            $result += $price->cost * $price->pivot->amount;
        }
        return $result;
    }

    public function getFirmIds()
    {
        $firmIds = [];
        foreach ($this->prices as $price) {
            array_push($firmIds, $price->firm->id);
        }
        return array_unique($firmIds);
    }

}