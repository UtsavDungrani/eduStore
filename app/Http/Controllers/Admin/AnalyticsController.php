<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
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
        $query = OrderItem::whereHas('order', function ($q) {
            $q->where('status', 'completed');
        });

        if (auth()->user()->hasRole('Instructor')) {
            $instructorProductIds = Product::where('user_id', auth()->id())->pluck('id');
            $query->whereIn('product_id', $instructorProductIds);
        }

        if ($request->date_range) {
            $dates = explode(' to ', $request->date_range);
            if (count($dates) == 2) {
                $query->whereBetween('created_at', [Carbon::parse($dates[0])->startOfDay(), Carbon::parse($dates[1])->endOfDay()]);
            }
        }

        if ($request->product_id) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->category_id) {
            $query->whereHas('product', function ($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        $totalSalesCount = $query->count();
        $totalRevenue = $query->sum('price');
        $avgOrderValue = $totalSalesCount > 0 ? $totalRevenue / $totalSalesCount : 0;

        // Trends
        $trends = $query->select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as sales'),
            DB::raw('SUM(price) as revenue')
        )
        ->groupBy('date')
        ->orderBy('date')
        ->get();

        return response()->json([
            'metrics' => [
                'total_sales' => $totalSalesCount,
                'total_revenue' => number_format($totalRevenue, 2),
                'avg_order_value' => number_format($avgOrderValue, 2),
            ],
            'charts' => [
                'labels' => $trends->pluck('date'),
                'sales' => $trends->pluck('sales'),
                'revenue' => $trends->pluck('revenue'),
            ]
        ]);
    }
}
