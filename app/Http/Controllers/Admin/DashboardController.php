<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use App\Models\OrderItem;
use App\Models\Order;
use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $total_published_products = Product::where('status', 1)->count();
        $total_unpublished_products = Product::where('status', 0)->count();
        $total_active_users = DB::table('sessions')
            ->join('users', 'sessions.user_id', '=', 'users.id')
            ->where('users.role_id', 2)
            ->where('sessions.last_activity', '>', Carbon::now()->timestamp - 3600)
            ->count();

        $total_penghasilan = OrderItem::whereHas('order', function ($query) {
            $query->where('status', 'settlement');
        })
            ->sum('total_price');

        $total_orders = Order::where('status', 'settlement')->count();

        $orders_by_month = Order::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->where('status', 'settlement')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->pluck('total', 'month');

        $monthly_orders = collect(range(1, 12))->map(function ($month) use ($orders_by_month) {
            return [
                'month' => date('M', mktime(0, 0, 0, $month, 1)),
                'total' => $orders_by_month[$month] ?? 0,
            ];
        });

        return Inertia::render('Admin/Dashboard/Index', [
            'total_penghasilan' => $total_penghasilan,
            'total_orders' => $total_orders,
            'total_published_products' => $total_published_products,
            'total_unpublished_products' => $total_unpublished_products,
            'total_active_users' => $total_active_users,
            'monthly_orders' => $monthly_orders,
        ]);
    }
}
