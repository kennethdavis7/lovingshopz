<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;


class HomeController extends Controller
{
    public function index()
    {

        $productsQuery = Product::with('images')
            ->join('categories', 'categories.id', 'products.category_id')
            ->where('categories.status', '1')
            ->where('products.status', '1')
            ->select('products.*');

        $popularProductsQuery = clone $productsQuery;

        $newProductsQuery = clone $productsQuery;

        $popularProducts = Cache::remember('popular_products', 60 * 60 * 24, function () use ($popularProductsQuery) {
            return $popularProductsQuery->orderBy('products.click', 'desc')->limit(20)->get();
        });

        $newProducts = Cache::remember('new_products', 60 * 60 * 24, function () use ($newProductsQuery) {
            return $newProductsQuery->orderBy('products.created_at', 'desc')->limit(20)->get();
        });

        return Inertia::render('Home/Index', [
            'newProducts' => $newProducts,
            'popularProducts' => $popularProducts
        ]);
    }
}
