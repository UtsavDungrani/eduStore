<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentRequest;
use Illuminate\Http\Request;

class PaymentRequestController extends Controller
{
    public function index()
    {
        $query = PaymentRequest::with(['user', 'product']);

        if (auth()->user()->hasRole('Instructor')) {
            $query->whereHas('product', function ($q) {
                $q->where('user_id', auth()->id());
            });
        }

        $requests = $query->latest()->paginate(15);
        return view('admin.payment_requests.index', compact('requests'));
    }

    public function show(PaymentRequest $payment_request)
    {
        if (auth()->user()->hasRole('Instructor') && $payment_request->product->user_id !== auth()->id()) {
            abort(403);
        }

        $payment_request->load(['user', 'product']);
        return view('admin.payment_requests.show', compact('payment_request'));
    }

    public function update(Request $request, PaymentRequest $payment_request)
    {
        if (!auth()->user()->hasRole('Super Admin')) {
            abort(403);
        }
        $request->validate([
            'status' => 'required|in:pending,approved,rejected,completed',
            'admin_note' => 'nullable|string'
        ]);

        $payment_request->update([
            'status' => $request->status,
            'admin_note' => $request->admin_note
        ]);

        if ($request->status == 'approved' || $request->status == 'completed') {
            $payment_request->user->clearPurchasedProductsCache();
        }

        return back()->with('success', 'Payment request updated successfully.');
    }
}
