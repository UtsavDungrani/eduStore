<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        return view('user.cart');
    }

    public function library()
    {
        $user = auth()->user();
        $productIds = $user->getPurchasedProductIds();
        $products = \App\Models\Product::whereIn('id', $productIds)->with('category')->get();

        return view('user.library', compact('products'));
    }
}
