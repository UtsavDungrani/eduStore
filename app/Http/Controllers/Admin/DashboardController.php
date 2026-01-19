<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\AccessLog;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'users' => User::count(),
            'products' => Product::count(),
            'categories' => Category::count(),
            'views' => AccessLog::where('action_type', 'view')->count(),
            'downloads' => AccessLog::where('action_type', 'download')->count(),
        ];

        $recentLogs = AccessLog::with(['user', 'product'])->latest()->take(10)->get();

        return view('admin.dashboard', compact('stats', 'recentLogs'));
    }

    public function logs()
    {
        $logs = AccessLog::with(['user', 'product'])->latest()->paginate(25);
        return view('admin.logs.index', compact('logs'));
    }
}
