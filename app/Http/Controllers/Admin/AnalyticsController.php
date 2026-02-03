<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PaymentRequest;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function index()
    {
        $productsQuery = Product::select('id', 'title');
        $categories = Category::select('id', 'name')->get();

        if (auth()->user()->hasRole('Instructor')) {
            $productsQuery->where('user_id', auth()->id());
        }

        $products = $productsQuery->get();
        return view('admin.analytics', compact('products', 'categories'));
    }

    public function apiData(Request $request)
    {
        // 1. Query for Cart Orders (OrderItem)
        $cartQuery = OrderItem::whereHas('order', function ($q) {
            $q->where('status', 'completed');
        });

        // 2. Query for Single Payment Requests
        $singleQuery = PaymentRequest::whereIn('status', ['completed', 'approved']);

        // Apply Instructor Filter
        if (auth()->user()->hasRole('Instructor')) {
            $instructorProductIds = Product::where('user_id', auth()->id())->pluck('id');
            $cartQuery->whereIn('product_id', $instructorProductIds);
            $singleQuery->whereIn('product_id', $instructorProductIds);
        }

        // Apply Date Range Filter
        if ($request->date_range) {
            $dates = explode(' to ', $request->date_range);
            if (count($dates) == 2) {
                $startDate = Carbon::parse($dates[0])->startOfDay();
                $endDate = Carbon::parse($dates[1])->endOfDay();

                $cartQuery->whereBetween('created_at', [$startDate, $endDate]);
                $singleQuery->whereBetween('created_at', [$startDate, $endDate]);
            }
        }

        // Apply Product Filter
        if ($request->product_id) {
            $cartQuery->where('product_id', $request->product_id);
            $singleQuery->where('product_id', $request->product_id);
        }

        // Apply Category Filter
        if ($request->category_id) {
            $cartQuery->whereHas('product', function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
            $singleQuery->whereHas('product', function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        // Calculate Aggregate Metrics
        $cartCount = $cartQuery->count();
        $cartRevenue = $cartQuery->sum('price');

        $singleCount = $singleQuery->count();
        $singleRevenue = $singleQuery->sum('amount');

        $totalSalesCount = $cartCount + $singleCount;
        $totalRevenue = $cartRevenue + $singleRevenue;
        $avgOrderValue = $totalSalesCount > 0 ? $totalRevenue / $totalSalesCount : 0;

        // Fetch Trends Data
        $cartTrends = $cartQuery->select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as sales'),
            DB::raw('SUM(price) as revenue')
        )
        ->groupBy('date')
        ->get();

        $singleTrends = $singleQuery->select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as sales'),
            DB::raw('SUM(amount) as revenue')
        )
        ->groupBy('date')
        ->get();

        // Merge Trends Data
        $mergedTrends = [];

        foreach ($cartTrends as $item) {
            $date = $item->date;
            if (!isset($mergedTrends[$date])) {
                $mergedTrends[$date] = ['sales' => 0, 'revenue' => 0];
            }
            $mergedTrends[$date]['sales'] += $item->sales;
            $mergedTrends[$date]['revenue'] += $item->revenue;
        }

        foreach ($singleTrends as $item) {
            $date = $item->date;
            if (!isset($mergedTrends[$date])) {
                $mergedTrends[$date] = ['sales' => 0, 'revenue' => 0];
            }
            $mergedTrends[$date]['sales'] += $item->sales;
            $mergedTrends[$date]['revenue'] += $item->revenue;
        }

        // Sort by Date
        ksort($mergedTrends);

        return response()->json([
            'metrics' => [
                'total_sales' => $totalSalesCount,
                'total_revenue' => number_format($totalRevenue, 2),
                'avg_order_value' => number_format($avgOrderValue, 2),
            ],
            'charts' => [
                'labels' => array_keys($mergedTrends),
                'sales' => array_column($mergedTrends, 'sales'),
                'revenue' => array_column($mergedTrends, 'revenue'),
            ]
        ]);
    }
}
