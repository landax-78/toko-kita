<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'totalProducts' => Product::count(),
            'totalCategories' => Category::count(),
            'totalCustomers' => User::where('role', 'customer')->count(),
            'totalOrders' => Order::count(),
            'paidTotal' => Order::where('status', 'paid')->sum('total'),
            'recentOrders' => Order::with('user')->latest()->take(6)->get(),
        ]);
    }
}
