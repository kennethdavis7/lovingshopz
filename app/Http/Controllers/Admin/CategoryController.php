<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;


class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate([
            'per_page' => ['sometimes', 'nullable', 'numeric', 'min:1'],
        ]);

        $per_page = $request->query('per_page') ?? 10;
        $search = strtolower($request->query('search'));
        $categories = Category::when($search, function ($query) use ($search) {
            $query->where(DB::raw('LOWER(categories.name)'), 'LIKE', '%' . $search . '%');
        });

        $total_categories = $categories->count();

        $categories = $categories->latest()->paginate($per_page)->appends(
            [
                'search' => $search,
                'per_page' => $per_page
            ]
        )->onEachSide(3);


        return Inertia::render('Admin/Categories/Index', [
            'categories' => $categories,
            'total_categories' => $total_categories
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/Categories/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated_data = $request->validate([
            'name' => 'required',
            'status' => 'required'
        ]);

        Category::create($validated_data);
        return redirect()->route('categories.index')->with('message', $validated_data['name'] . ' has been successfuly added!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Category $category)
    {
        if ($category->status == 0) abort(404);

        $request->validate([
            'sort_by' => ['sometimes', 'nullable', 'string', Rule::in('created_at', 'price')],
            'sort_direction' => ['sometimes', 'nullable', 'string', Rule::in('asc', 'desc')],
        ]);

        $sort_direction = $request->query('sort_direction') ?? "desc";
        $sort_by = $request->query('sort_by') ?? "created_at";

        $category_products = Product::with(['images'])
            ->join('categories', 'categories.id', 'products.category_id')
            ->where('products.category_id', $category->id)
            ->where('products.status', 1)
            ->where('categories.status', 1)
            ->orderBy('products.' . $sort_by, $sort_direction)
            ->select('products.*')
            ->paginate(20)
            ->appends(
                [
                    'sort_by' => $sort_by,
                    'sort_direction' => $sort_direction,
                ]
            )
            ->onEachSide(3);

        // dd($category_products);

        return Inertia::render('Categories/Show', [
            'category' => $category,
            'sort_direction' => $sort_direction,
            'sort_by' => $sort_by,
            'products' => $category_products
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        $category->status = $category->status === 1;

        return Inertia::render('Admin/Categories/Edit', [
            'category' => $category,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validated_data = $request->validate([
            'name' => 'required',
            'status' => 'required'
        ]);

        if ($validated_data['status'] == 0) {
            $category->product()->update(['products.status' => 0]);
        } else {
            $category->product()->update(['products.status' => 1]);
        }

        $category->update($validated_data);
        return redirect()->route('categories.index')->with('message', $validated_data['name'] . ' has been successfuly edited!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('message', $category->name . ' has been successfuly deleted');
    }
}
