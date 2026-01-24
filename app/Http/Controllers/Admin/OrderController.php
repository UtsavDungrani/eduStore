<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $query = Order::with(['user', 'items.product']);

        if (auth()->user()->hasRole('Instructor')) {
            $query->whereHas('items.product', function ($q) {
                $q->where('user_id', auth()->id());
            });
        }

        $orders = $query->latest()->paginate(15);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        if (auth()->user()->hasRole('Instructor')) {
            $hasOwnProduct = $order->items()->whereHas('product', function ($q) {
                $q->where('user_id', auth()->id());
            })->exists();

            if (!$hasOwnProduct) {
                abort(403);
            }
        }

        $order->load(['user', 'items.product']);
        return view('admin.orders.show', compact('order'));
    }
}
