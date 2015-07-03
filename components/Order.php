<?php namespace Macrobit\Horeca\Components;

use Auth;
use Redirect;
use DateTime;
use DateTimeZone;
use HorecaSettings;
use Cms\Classes\ComponentBase;
use Macrobit\Horeca\Models\Order as OrderModel;
use Macrobit\Horeca\Models\PaymentMethod;

class Order extends ComponentBase
{

    public $summ = 0;

    public $fullname = null;

    public $payments = [];

    public $defaultPayment = null;


    public function componentDetails()
    {
        return [
            'name'        => 'Order Component',
            'description' => 'Perform an Order'
        ];
    }

    public function defineProperties()
    {
        return [];
    }

    /**
     * {@inheritDoc}
     */
    public function onRun()
    {
        $user = Auth::getUser();

        $this->summ = $user->basket->getSumm();

        $this->fullname = $user->name . ' ' . $user->surname;

        $this->payments = HorecaSettings::get('payments');

        $this->defaultPayment = HorecaSettings::get('default_payment');

    }

    public function onPerform()
    {
        $order = new OrderModel();
        $order->user = Auth::getUser();

        $order->fullname = post('fullname');
        $order->address = post('address');
        $order->comment = post('comment');
        $order->phone = post('phone');
        $order->date = new DateTime('now', new DateTimeZone('Europe/Moscow'));
        $order->payment_method = PaymentMethod::find(post('payment'));
        $order->save();
        $order->perform(Auth::getUser()->basket);
    }

}