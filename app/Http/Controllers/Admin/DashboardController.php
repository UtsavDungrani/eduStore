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
        $isInstructor = auth()->user()->hasRole('Instructor');
        $userId = auth()->id();

        $stats = [
            'users' => $isInstructor ? 0 : User::count(),
            'products' => $isInstructor ? Product::where('user_id', $userId)->count() : Product::count(),
            'categories' => $isInstructor ? Category::count() : Category::count(),
            'views' => $isInstructor 
                ? AccessLog::where('action_type', 'view')->whereHas('product', fn($q) => $q->where('user_id', $userId))->count() 
                : AccessLog::where('action_type', 'view')->count(),
            'downloads' => $isInstructor 
                ? AccessLog::where('action_type', 'download')->whereHas('product', fn($q) => $q->where('user_id', $userId))->count() 
                : AccessLog::where('action_type', 'download')->count(),
        ];

        $logsQuery = AccessLog::with(['user', 'product'])->latest()->take(10);
        if ($isInstructor) {
            $logsQuery->whereHas('product', fn($q) => $q->where('user_id', $userId));
        }
        $recentLogs = $logsQuery->get();

        return view('admin.dashboard', compact('stats', 'recentLogs'));
    }

    public function logs()
    {
        $logs = AccessLog::with(['user', 'product'])->latest()->paginate(25);
        return view('admin.logs.index', compact('logs'));
    }
}
