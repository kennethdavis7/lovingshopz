<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Inertia\Inertia;
use Illuminate\Validation\Rule;
use MeiliSearch\Endpoints\Indexes;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'sort_by' => ['sometimes', 'nullable', 'string', Rule::in('created_at', 'price')],
            'sort_direction' => ['sometimes', 'nullable', 'string', Rule::in('asc', 'desc')],
        ]);

        $search = strtolower($request->query('search'));
        $sort_direction = $request->query('sort_direction') ?? "desc";
        $sort_by = $request->query('sort_by') ?? "created_at";

        $searchedProducts = Product::search($search, function (Indexes $meiliSearch, string $query, array $options) use ($sort_by, $sort_direction) {
            $options['sort'] = [$sort_by . ':' . $sort_direction];
            return $meiliSearch->search($query, $options);
        })
            ->query(function ($query) {
                $query->with('images')->join('categories', 'categories.id', 'products.category_id')
                    ->where('categories.status', '1')->where('products.status', '1')
                    ->select('products.*');
            })
            ->paginate(20)->appends(
                [
                    'search' => $search,
                    'sort_by' => $sort_by,
                    'sort_direction' => $sort_direction,
                ]
            )->onEachSide(3);

        return Inertia::render('Search/Index', [
            'search' => $search,
            'sort_by' => $sort_by,
            'sort_direction' => $sort_direction,
            'searchedProducts' => $searchedProducts
        ]);
    }
}
