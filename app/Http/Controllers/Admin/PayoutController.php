<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Payout;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PayoutController extends Controller
{
    public function index()
    {
        return view('admin.instructors.payouts');
    }

    public function instructorEarnings()
    {
        $query = User::whereHas('products')->with(['payouts']);
        
        if (auth()->user()->hasRole('Instructor')) {
            $query->where('id', auth()->id());
        }

        // Fetch users who have products (instructors)
        $instructors = $query->get()
            ->map(function ($user) {
                // Total earnings from sales of their products (70%)
                $totalEarnings = OrderItem::whereIn('product_id', $user->products->pluck('id'))
                    ->whereHas('order', function ($q) {
                        $q->where('status', 'completed');
                    })
                    ->sum('price') * 0.7;

                $paidAmount = $user->payouts->where('status', 'paid')->sum('amount');
                $pendingPayouts = $totalEarnings - $paidAmount;
                $lastPayout = $user->payouts->where('status', 'paid')->sortByDesc('paid_at')->first();

                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'total_earnings' => number_format($totalEarnings, 2),
                    'paid_amount' => number_format($paidAmount, 2),
                    'pending_payouts' => number_format($pendingPayouts, 2),
                    'last_payout_date' => $lastPayout ? $lastPayout->paid_at->format('Y-m-d') : 'N/A',
                ];
            });

        return response()->json($instructors);
    }

    public function store(Request $request)
    {
        if (!auth()->user()->hasRole('Super Admin')) {
            abort(403);
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $payout = Payout::create([
            'user_id' => $request->user_id,
            'amount' => $request->amount,
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        return response()->json(['message' => 'Payout recorded successfully', 'payout' => $payout]);
    }
}
