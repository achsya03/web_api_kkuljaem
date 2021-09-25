<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubsController extends Controller
{
    /**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();

		$this->middleware('auth');
	}

	
	/**
	 * Checkout process and saving order data
	 *
	 * @param OrderRequest $request order data
	 *
	 * @return void
	 */
	public function doCheckout(OrderRequest $request)
	{
		$params = $request->except('_token');

		$order = \DB::transaction(
			function () use ($params) {
				$order = $this->_saveOrder($params);
				$this->_saveOrderItems($order);
				$this->_generatePaymentToken($order);
				$this->_saveShipment($order, $params);
	
				return $order;
			}
		);

		if ($order) {
			\Cart::clear();
			$this->_sendEmailOrderReceived($order);

			\Session::flash('success', 'Thank you. Your order has been received!');
			return redirect('orders/received/'. $order->id);
		}

		return redirect('orders/checkout');
	}

	/**
	 * Generate payment token
	 *
	 * @param Order $order order data
	 *
	 * @return void
	 */
	private function _generatePaymentToken($order)
	{
		$this->initPaymentGateway();

		$customerDetails = [
			'first_name' => $order->customer_first_name,
			'last_name' => $order->customer_last_name,
			'email' => $order->customer_email,
			'phone' => $order->customer_phone,
		];

		$params = [
			'enable_payments' => \App\Models\Payment::PAYMENT_CHANNELS,
			'transaction_details' => [
				'order_id' => $order->code,
				'gross_amount' => $order->grand_total,
			],
			'customer_details' => $customerDetails,
			'expiry' => [
				'start_time' => date('Y-m-d H:i:s T'),
				'unit' => \App\Models\Payment::EXPIRY_UNIT,
				'duration' => \App\Models\Payment::EXPIRY_DURATION,
			],
		];

		$snap = \Midtrans\Snap::createTransaction($params);
		
		if ($snap->token) {
			$order->payment_token = $snap->token;
			$order->payment_url = $snap->redirect_url;
			$order->save();
		}
	}

	/**
	 * Save order data
	 *
	 * @param array $params checkout params
	 *
	 * @return Order
	 */
	private function _saveOrder($params)
	{
		$destination = isset($params['ship_to']) ? $params['shipping_city_id'] : $params['city_id'];
		$selectedShipping = $this->_getSelectedShipping($destination, $this->_getTotalWeight(), $params['shipping_service']);
		

		$baseTotalPrice = \Cart::getSubTotal();
		$taxAmount = \Cart::getCondition('TAX 10%')->getCalculatedValue(\Cart::getSubTotal());
		$taxPercent = (float)\Cart::getCondition('TAX 10%')->getValue();
		$shippingCost = $selectedShipping['cost'];
		$discountAmount = 0;
		$discountPercent = 0;
		$grandTotal = ($baseTotalPrice + $taxAmount + $shippingCost) - $discountAmount;

		$orderDate = date('Y-m-d H:i:s');
		$paymentDue = (new \DateTime($orderDate))->modify('+7 day')->format('Y-m-d H:i:s');

		$orderParams = [
			'id_user' => \Auth::user()->id,
			'id_packet' => \Auth::user()->id,
			'harga' => Order::generateCode(),
			'diskon' => Order::generateCode(),
			'tgl_subs' => $orderDate,
			'tgl_akhir_bayar' => $paymentDue,
			'subs_status' => Order::UNPAID,
			'snap_token' => $baseTotalPrice,
			'snap_url' => $taxAmount,
		];

		return Order::create($orderParams);
	}

	/**
	 * Send email order detail to current user
	 *
	 * @param Order $order order object
	 *
	 * @return void
	 */
	private function _sendEmailOrderReceived($order)
	{
		\App\Jobs\SendMailOrderReceived::dispatch($order, \Auth::user());
	}

	/**
	 * Show the received page for success checkout
	 *
	 * @param int $orderId order id
	 *
	 * @return void
	 */
	public function received($orderId)
	{
		$this->data['order'] = Order::where('id', $orderId)
			->where('user_id', \Auth::user()->id)
			->firstOrFail();

		return $this->loadTheme('orders/received', $this->data);
	}
}
