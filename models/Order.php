<?php namespace Macrobit\Horeca\Models;

use Mail;
use Model;
use Macrobit\Horeca\Models\Firm as FirmModel;

/**
 * Order Model
 */
class Order extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'macrobit_horeca_orders';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'fullname'
    ];

    protected $dates = [
        'date'
    ];

    public $description = null;

    /**
     * @var array Relations
     */
    public $hasOne = [
        'payment_info' => ['Macrobit\Horeca\Models\PaymentInfo']
    ];
    public $hasMany = [];
    public $belongsTo = [
        'firm' => ['Macrobit\Horeca\Models\Firm'],
        'user' => ['RainLab\User\Models\User'],
        'payment_method' => ['Macrobit\Horeca\Models\PaymentMethod']
    ];
    public $belongsToMany = [
        'prices' => ['Macrobit\Horeca\Models\Price', 'table' => 'macrobit_horeca_order_prices', 'pivot' => ['amount']]
    ];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

    public function afterFetch()
    {
        $this->presetFields();
    }

    private function presetFields()
    {
        /**
         * description field
         */
        $description = '';
        if (gettype($this->payment_method) === 'object') {
            $description .= $this->payment_method->name . ';';
        }
        else {
            $description .= $this->payment_method . ';';
        }
        $description .= $this->address . ';';
        if (gettype($this->prices) === 'object') {
            $description .= implode(', ', $this->prices()->lists('name')) . ';';
        }
        else {
            $description .= $this->prices . ';';
        }
        $this->description = $description;
    }

    public function perform($basket = null)
    {
        if ($this->state != 0) {
            return;
        }

        $firms = null;

        if ($basket) {
            $firms = FirmModel::findMany($basket->getFirmIds());
            foreach ($basket->prices as $price) {
                $this->prices()->attach($price, ['amount' => $price->pivot->amount]);
            }
        } else {
            $firms = FirmModel::findMany($this->getFirmIds());
        }

        foreach ($firms as $firm) {

            $data = [
                'fullname' => $this->fullname,
                'phone'   => $this->phone,
                'address' => $this->address,
                'comment' => $this->comment,
                'prices' => $this->prices()->where('firm_id', $firm->id)->get()
            ];


            Mail::send('macrobit.horeca::mail.order', $data, 
                    function($message) use ($firm, $data) {
                $message->to($firm->email, $firm->name);
                $message->attach($this->makeCsv($data['prices']));
            });
        }
        
        if ($basket) {
            $basket->prices()->detach();
        }

        $this->state = 1;
        $this->save();
    }

    public function cancel()
    {
        $this->state = 3;
        $this->save();
    }

    public function makeCsv($prices = null)
    {
        if ($prices === null) {
            $prices = $this->prices;
        }

        $path = 'storage/temp/orders/' . uniqid() . '.csv';
        $fp = fopen($path, 'w');
        
        fputcsv($fp, ['Название блюда', 'Порция', 'Кол-во', 'Цена', 'Сумма']);
        foreach ($prices as $price) {
            fputcsv($fp, [$price->name, $price->portion, $price->pivot->amount, $price->cost . 'р.', 
                $price->pivot->amount * $price->cost . 'р.']);
        }
        fputcsv($fp, ['Итого', '', '', '', $this->getSumm($prices) . 'р.']);
        fclose($fp);
        return $path;
    }

    public function getSumm($prices = null)
    {
        if ($prices === null) {
            $prices = $this->prices;
        }

        $result = 0;
        foreach ($prices as $price) {
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