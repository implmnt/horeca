<?php namespace Macrobit\Horeca\Components;

use Lang;
use Auth;
use Redirect;
use Cms\Classes\ComponentBase;
use Macrobit\Horeca\Models\Price as PriceModel;
use Macrobit\Horeca\Models\Firm as FirmModel;
use Macrobit\Horeca\Models\Order as OrderModel;
use Macrobit\Horeca\Models\Basket as BasketModel;

class Basket extends ComponentBase
{

    public $summ = null;

    public $firms = null;

    public $items = null;

    /**
     * {@inheritDoc}
     */
    public function componentDetails()
    {
        return [
            'name'        =>    Lang::get('macrobit.horeca::lang.basket.label'),
            'description' =>    Lang::get('macrobit.horeca::lang.basket.desc')
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function defineProperties()
    {
        return [];
    }

    /**
     * {@inheritDoc}
     */
    public function onRun()
    {
        if (!Auth::check()) {
            return;
        }

        $user =Auth::getUser();

        if (!$user->basket) {
            BasketModel::getFromUser($user);
        }

        $this->summ = $this->getSumm();

        $this->firms = $this->getFirms();

        $this->items = $user->basket->prices;
    }

    public function onClear()
    {
        Auth::getUser()->basket->prices()->detach();
        $this->summ = 0;
    }

    public function onAddItem()
    {
        $itemId = post('id');
        $basket = Auth::getUser()->basket;
        $price = $basket->prices()->where('id', 
            $itemId)->get()->first();
        if ($price) {
            $basket->prices()->updateExistingPivot($itemId, 
                ['amount' => $price->pivot->amount + 1]);
        } else {
            $basket->prices()->attach($itemId, ['amount' => 1]);
        }
        $basket->save();
        $this->summ = $this->getSumm();
    }

    public function onCalculate()
    {
        $goods = post('goods');
        $basket = Auth::getUser()->basket;
        foreach ($goods as $goodsId => $amount) {
            $basket->prices()->updateExistingPivot($goodsId, 
                ['amount' => $amount]);
        }
        $basket->save();
        $this->summ = $this->getSumm();
        $this->firms = $this->getFirms();
        $this->items = $basket->prices;
    }

    public function onOrder()
    {
        $goods = post('goods');
        $user = Auth::getUser();
        $basket = $user->basket;
        foreach ($goods as $goodsId => $amount) {
            $basket->prices()->updateExistingPivot($goodsId, 
                ['amount' => $amount]);
        }
        $basket->save();
    }

    private function getSumm()
    {
        $basket = Auth::getUser()->basket;
        return $basket->getSumm();
    }

    private function getFirms()
    {
        $basket = Auth::getUser()->basket;
        $firmIds = $basket->getFirmIds();

        return FirmModel::findMany($firmIds);
    }

}