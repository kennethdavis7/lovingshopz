<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
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

        return Inertia::render('Admin/Dashboard/Index', [
            'total_published_products' => $total_published_products,
            'total_unpublished_products' => $total_unpublished_products,
            'total_active_users' => $total_active_users,
        ]);
    }
}
