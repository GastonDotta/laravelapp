<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Brand;
use App\Models\Category;
use App\Models\NewsletterSubscriber;
use App\Models\Order;
use App\Models\ProductReview;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Stripe\Review;

class AdminController extends Controller
{
    public function dashboard()
    {
        $todaysOrder = Order::whereDate('created_at', Carbon::today())->count();
        $todaysPendingOrder = Order::whereDate('created_at', Carbon::today())
            ->where('order_status', 'pending')->count();
        $totalOrders = Order::count();
        $totalPendingOrders = Order::where('order_status', 'pending')->count();
        $totalCanceledOrders = Order::where('order_status', 'canceled')->count();
        $totalCompleteOrders = Order::where('order_status', 'delivered')->count();

        $todaysEarnings = Order::where('order_status','!=', 'canceled')
            ->where('payment_status',1)
            ->whereDate('created_at', Carbon::today())
            ->sum('sub_total');        

        $weekStart = now()->subWeek();
        $weekEnd = now();

        $monthStart = Carbon::now()->subMonth()->startOfMonth();
        $monthEnd = Carbon::now()->subMonth()->endOfMonth();

        $yearStart = Carbon::now()->subYear()->startOfYear();
        $yearEnd = Carbon::now()->subYear()->endOfYear();

        $weekEarnings = Order::where('order_status', '!=', 'canceled')
            ->where('payment_status', 1)
            ->whereBetween('created_at', [$weekStart, $weekEnd])
            ->sum('sub_total');

        $monthEarnings = Order::where('order_status','!=', 'canceled')
            ->where('payment_status',1)
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('sub_total');

        $yearEarnings = Order::where('order_status','!=', 'canceled')
            ->where('payment_status',1)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('sub_total');

        $totalOrdersAmount = Order::where('order_status','!=','canceled')
            ->where('payment_status', 1)
            ->sum('sub_total');

        $totalReview = ProductReview::count();

        $totalBrands = Brand::count();
        $totalCategories = Category::count();
        $totalBlogs = Blog::count();
        $totalSubscriber = NewsletterSubscriber::count();
        $totalVendors = User::where('role', 'vendor')->count();
        $totalUsers = User::where('role', 'user')->count();

        // Períodos anteriores para comparar

        $yesterdayEarnings = Order::where('order_status', '!=', 'canceled')
            ->where('payment_status', 1)
            ->whereDate('created_at', Carbon::now()->subDays(1)->toDateString())
            ->sum('sub_total'); 

        $lastWeekEarnings = Order::where('order_status', '!=', 'canceled')
            ->where('payment_status', 1)
            ->whereBetween('created_at', [$weekStart->copy()->subWeek(), $weekEnd->copy()->subWeek()])
            ->sum('sub_total');

        $lastMonthEarnings = Order::where('order_status', '!=', 'canceled')
            ->where('payment_status', 1)
            ->whereBetween('created_at', [$monthStart->copy()->subMonth(), $monthEnd->copy()->subMonth()])
            ->sum('sub_total');

        $lastYearEarnings = Order::where('order_status', '!=', 'canceled')
            ->where('payment_status', 1)
            ->whereBetween('created_at', [$yearStart->copy()->subYear(), $yearEnd->copy()->subYear()])
            ->sum('sub_total');

        
        
        $salesData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $formattedDate = $date->format('j/n'); // Formato "30/6"
            $totalEarnings = Order::whereDate('created_at', $date->toDateString())
                ->where('order_status', '!=', 'canceled')
                ->where('payment_status', 1)
                ->sum('sub_total');
    
            $salesData[$formattedDate] = $totalEarnings;
        }   

        $previousWeekSalesData = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays(7 + $i); // Ajuste para obtener datos de una semana atrás
            $formattedDate = $date->format('j/n'); // Formato "30/6"
            $totalEarnings = Order::whereDate('created_at', $date->toDateString())
                ->where('order_status', '!=', 'canceled')
                ->where('payment_status', 1)
                ->sum('sub_total');

            $previousWeekSalesData[$formattedDate] = $totalEarnings;
}
    

        return view('admin.dashboard', compact(
            'todaysOrder',
            'todaysPendingOrder',
            'totalOrders',
            'totalOrdersAmount',
            'totalPendingOrders',
            'totalCanceledOrders',
            'totalCompleteOrders',
            'todaysEarnings',
            'monthEarnings',
            'yearEarnings',
            'totalReview',
            'totalBrands',
            'totalCategories',
            'totalBlogs',
            'totalSubscriber',
            'totalVendors',
            'totalUsers',
            'weekEarnings',
            'yesterdayEarnings',
            'lastWeekEarnings',
            'lastMonthEarnings',
            'lastYearEarnings',
            'salesData',
            'previousWeekSalesData'
        ));
    }

    public function login()
    {
        return view('admin.auth.login');
    }
}
