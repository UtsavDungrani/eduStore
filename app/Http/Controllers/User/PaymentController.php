<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\PaymentRequest;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'transaction_id' => 'required|string|max:255',
            'payment_proof' => 'nullable|image|max:2048',
        ]);

        $proofPath = null;
        if ($request->hasFile('payment_proof')) {
            $proofPath = $request->file('payment_proof')->store('payments', 'public');
        }

        PaymentRequest::create([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
            'amount' => $product->price,
            'transaction_id' => $request->transaction_id,
            'payment_proof' => $proofPath,
        ]);

        return back()->with('success', 'Your payment request has been submitted. Please wait for admin approval.');
    }
}
