@extends('layouts.admin')

@section('content')
<div class="mb-8">
    <h1 class="text-3xl font-bold text-gray-900">Sales & Revenue Analytics</h1>
    <p class="text-gray-500">Track your store's performance over time.</p>
</div>

<!-- Filters -->
<div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-8">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Date Range</label>
            <input type="text" id="date_range" class="w-full bg-gray-50 border-gray-100 rounded-xl px-4 py-2.5 text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Select dates">
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Product</label>
            <select id="product_id" class="w-full bg-gray-50 border-gray-100 rounded-xl px-4 py-2.5 text-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">All Products</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}">{{ $product->title }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Category</label>
            <select id="category_id" class="w-full bg-gray-50 border-gray-100 rounded-xl px-4 py-2.5 text-sm focus:ring-blue-500 focus:border-blue-500">
                <option value="">All Categories</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <button onclick="fetchAnalytics()" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2.5 rounded-xl transition-all shadow-lg shadow-blue-200">
                Apply Filters
            </button>
        </div>
    </div>
</div>

<!-- Key Metrics -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div class="text-blue-500 mb-2"><i class="fas fa-shopping-cart text-2xl"></i></div>
        <div class="text-gray-500 text-xs font-bold uppercase tracking-wider">Total Sales</div>
        <div id="metric_total_sales" class="text-2xl font-black text-gray-900">0</div>
    </div>
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div class="text-green-500 mb-2"><i class="fas fa-dollar-sign text-2xl"></i></div>
        <div class="text-gray-500 text-xs font-bold uppercase tracking-wider">Total Revenue</div>
        <div class="text-2xl font-black text-gray-900">₹<span id="metric_total_revenue">0.00</span></div>
    </div>
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div class="text-purple-500 mb-2"><i class="fas fa-chart-line text-2xl"></i></div>
        <div class="text-gray-500 text-xs font-bold uppercase tracking-wider">Avg Order Value</div>
        <div class="text-2xl font-black text-gray-900">₹<span id="metric_avg_order_value">0.00</span></div>
    </div>
</div>

<!-- Charts -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <h3 class="font-bold text-gray-900 mb-6">Sales Trend</h3>
        <canvas id="salesChart" height="200"></canvas>
    </div>
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <h3 class="font-bold text-gray-900 mb-6">Revenue Trend</h3>
        <canvas id="revenueChart" height="200"></canvas>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    let salesChart, revenueChart;

    document.addEventListener('DOMContentLoaded', function() {
        flatpickr("#date_range", {
            mode: "range",
            dateFormat: "Y-m-d",
        });

        initCharts();
        fetchAnalytics();
    });

    function initCharts() {
        const salesCtx = document.getElementById('salesChart').getContext('2d');
        salesChart = new Chart(salesCtx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Sales',
                    data: [],
                    borderColor: '#3B82F6',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
            }
        });

        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        revenueChart = new Chart(revenueCtx, {
            type: 'bar',
            data: {
                labels: [],
                datasets: [{
                    label: 'Revenue',
                    data: [],
                    backgroundColor: '#10B981',
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });
    }

    async function fetchAnalytics() {
        const dateRange = document.getElementById('date_range').value;
        const productId = document.getElementById('product_id').value;
        const categoryId = document.getElementById('category_id').value;

        try {
            const response = await fetch(`/api/admin/analytics?date_range=${dateRange}&product_id=${productId}&category_id=${categoryId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            const data = await response.json();

            document.getElementById('metric_total_sales').innerText = data.metrics.total_sales;
            document.getElementById('metric_total_revenue').innerText = data.metrics.total_revenue;
            document.getElementById('metric_avg_order_value').innerText = data.metrics.avg_order_value;

            salesChart.data.labels = data.charts.labels;
            salesChart.data.datasets[0].data = data.charts.sales;
            salesChart.update();

            revenueChart.data.labels = data.charts.labels;
            revenueChart.data.datasets[0].data = data.charts.revenue;
            revenueChart.update();
        } catch (error) {
            console.error('Error fetching analytics:', error);
        }
    }
</script>
@endsection
