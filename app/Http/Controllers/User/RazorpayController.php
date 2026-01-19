<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\PaymentRequest;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Razorpay\Api\Api;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class RazorpayController extends Controller
{
    private $api;

    public function __construct()
    {
        $this->api = new Api(config('services.razorpay.key_id'), config('services.razorpay.key_secret'));
    }

    public function createOrder(Request $request, Product $product)
    {
        if (!config('services.razorpay.key_id') || !config('services.razorpay.key_secret')) {
            return response()->json(['error' => 'Razorpay API keys are not configured in .env file.'], 500);
        }
        try {
            $orderData = [
                'receipt'         => 'rcpt_' . auth()->id() . '_' . $product->id . '_' . time(),
                'amount'          => $product->selling_price * 100, // in paise
                'currency'        => 'INR',
                'payment_capture' => 1 // auto capture
            ];

            $razorpayOrder = $this->api->order->create($orderData);

            PaymentRequest::create([
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'amount' => $product->selling_price,
                'razorpay_order_id' => $razorpayOrder['id'],
                'status' => 'pending',
            ]);

            return response()->json([
                'order_id' => $razorpayOrder['id'],
                'amount' => $orderData['amount'],
                'key' => config('services.razorpay.key_id'),
                'product_name' => $product->name,
                'user_name' => auth()->user()->name,
                'user_email' => auth()->user()->email,
            ]);
        } catch (\Exception $e) {
            Log::error('Razorpay Order Creation Error: ' . $e->getMessage());
            return response()->json(['error' => 'Could not create order.'], 500);
        }
    }

    public function createCartOrder(Request $request)
    {
        if (!config('services.razorpay.key_id') || !config('services.razorpay.key_secret')) {
            return response()->json(['error' => 'Razorpay API keys are not configured in .env file.'], 500);
        }
        $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:products,id',
            'items.*.price' => 'required|numeric',
        ]);

        try {
            return DB::transaction(function () use ($request) {
                $totalAmount = collect($request->items)->sum(function($item) {
                    return $item['price'];
                });

                $orderData = [
                    'receipt'         => 'cart_rcpt_' . auth()->id() . '_' . time(),
                    'amount'          => $totalAmount * 100, // in paise
                    'currency'        => 'INR',
                    'payment_capture' => 1
                ];

                $razorpayOrder = $this->api->order->create($orderData);

                $order = Order::create([
                    'user_id' => auth()->id(),
                    'amount' => $totalAmount,
                    'razorpay_order_id' => $razorpayOrder['id'],
                    'status' => 'pending',
                ]);

                foreach ($request->items as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item['id'],
                        'price' => $item['price'],
                    ]);
                }

                return response()->json([
                    'order_id' => $razorpayOrder['id'],
                    'amount' => $orderData['amount'],
                    'key' => config('services.razorpay.key_id'),
                    'user_name' => auth()->user()->name,
                    'user_email' => auth()->user()->email,
                ]);
            });
        } catch (\Exception $e) {
            Log::error('Razorpay Cart Order Creation Error: ' . $e->getMessage());
            return response()->json(['error' => 'Could not create cart order.'], 500);
        }
    }

    public function verifyPayment(Request $request)
    {
        $success = true;
        $error = "Payment Failed";

        if (!empty($request->razorpay_payment_id)) {
            try {
                $attributes = [
                    'razorpay_order_id' => $request->razorpay_order_id,
                    'razorpay_payment_id' => $request->razorpay_payment_id,
                    'razorpay_signature' => $request->razorpay_signature
                ];

                $this->api->utility->verifyPaymentSignature($attributes);
            } catch (\Exception $e) {
                $success = false;
                $error = 'Razorpay Error : ' . $e->getMessage();
            }
        }

        if ($success === true) {
            // Check if it's a single product payment
            $paymentRequest = PaymentRequest::where('razorpay_order_id', $request->razorpay_order_id)->first();
            if ($paymentRequest) {
                $paymentRequest->update([
                    'razorpay_payment_id' => $request->razorpay_payment_id,
                    'razorpay_signature' => $request->razorpay_signature,
                    'status' => 'completed',
                ]);
                auth()->user()->clearPurchasedProductsCache();
            }

            // Check if it's a cart order
            $order = Order::where('razorpay_order_id', $request->razorpay_order_id)->first();
            if ($order) {
                $order->update([
                    'razorpay_payment_id' => $request->razorpay_payment_id,
                    'razorpay_signature' => $request->razorpay_signature,
                    'status' => 'completed',
                ]);
                auth()->user()->clearPurchasedProductsCache();
            }

            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'error' => $error], 400);
        }
    }
}
