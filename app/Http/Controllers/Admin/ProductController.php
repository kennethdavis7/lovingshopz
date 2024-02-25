<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Product;
use App\Models\Category;
use App\Models\ImageProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Barryvdh\Snappy\Facades\SnappyPdf as pdf;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate([
            'sort_by' => ['sometimes', 'nullable', 'string', Rule::in('created_at', 'name', 'categories.name', 'qty', 'price')],
            'sort_direction' => ['sometimes', 'nullable', 'string', Rule::in('asc', 'desc')],
            'per_page' => ['sometimes', 'nullable', 'numeric', 'min:1'],
            'category_id' => ['sometimes', 'nullable', 'numeric'],
        ]);

        $search = strtolower($request->query('search'));
        $sort_by = $request->query('sort_by') ?? 'created_at';
        $sort_direction = $request->query('sort_direction') ?? 'desc';
        $per_page = $request->query('per_page') ?? 10;
        $category_id = intval($request->query('category_id') ?? -1);

        $products = Product::with('category', 'images')
            ->when($search, function ($query) use ($search) {
                $query->where(DB::raw('LOWER(products.name)'), 'LIKE', '%' . $search . '%');
            })
            ->join('categories', 'categories.id', '=', 'products.category_id')
            ->when($category_id, function ($query) use ($category_id) {
                if ($category_id !== -1) {
                    $query->where('categories.id', $category_id);
                }
            })
            ->orderBy($sort_by, $sort_direction)
            ->select('products.*');

        $total_products = $products->count();

        $products = $products->paginate($per_page)->appends(
            [
                'search' => $search,
                'sort_by' => $sort_by,
                'sort_direction' => $sort_direction,
                'category_id' => $category_id,
            ]
        )->onEachSide(3);

        $categories = Category::all();

        return Inertia::render('Admin/Products/Index', [
            'products' => $products,
            'sort_by' => $sort_by,
            'sort_direction' => $sort_direction,
            'total_products' => $total_products,
            'categories' => $categories,
            'category_id' => $category_id,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return Inertia::render('Admin/Products/Create', [
            'categories' => $categories
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'category_id' => 'required',
            'description' => 'required',
            'status' => 'required',
            'min_order' => 'required|numeric',
            // 'images' => 'image|file|mimes:jpeg,png,jpg',
        ]);

        if (is_string($request->qty) && $request->qty <= 0) {
            return redirect()->route('products.create');
        }

        $product = Product::create([
            "name" => $request->name,
            "price" => $request->price,
            "qty" => $request->qty,
            "description" => $request->description,
            "status" => $request->status,
            "min_order" => $request->min_order,
            "category_id" => $request->category_id,
        ]);

        if ($request->hasFile('images')) {
            $files = $request->file('images');

            // Decoration-only images => alt=""

            foreach ($files as $file) {
                ImageProduct::create([
                    'product_id' => $product->id,
                    'url' => 'storage/' . $file->store('images'),
                    'alt' => '',
                ]);
            }
        }

        return redirect()->route('products.index')->with('message', $product['name'] . ' has been succesfully added!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return Inertia::render('Admin/Products/Edit', [
            'product' => $product->load('category', 'images'),
            'categories' => Category::all()
        ]);
    }

    public function report()
    {
        $products = Product::with('category', 'images')->get();

        $pdf = pdf::loadView('reports.products', ['products' => $products]);

        $pdf->setOption('enable-local-file-access', true);

        return $pdf->inline('Products.pdf');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'category_id' => 'required',
            'description' => 'required',
            'status' => 'required',
            'min_order' => 'required|numeric',
            'new_images.*' => 'file|image|mimes:jpeg,png,jpg',
            'images_to_delete.*' => 'string',
        ]);

        $files = $request->file('new_images') ?? [];
        foreach ($files as $file) {
            ImageProduct::create([
                'product_id' => $product->id,
                'url' => 'storage/' . $file->store('images'),
                'alt' => '',
            ]);
        }

        $images_to_delete = $request->get('images_to_delete') ?? [];
        foreach ($images_to_delete as $image) {
            ImageProduct::where('url', $image)->delete();
            Storage::delete('public/' . $image);
        }

        $product->update([
            "name" => $request->name,
            "price" => $request->price,
            "qty" => $request->qty,
            "description" => $request->description,
            "status" => $request->status,
            "min_order" => $request->min_order,
            "category_id" => $request->category_id,
        ]);

        return redirect()->route('products.index')->with('message', $product['name'] . ' has been succesfully updated!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        Product::destroy($product->id);

        return redirect()->route('products.index')->with('message', $product['name'] . ' has been succesfully deleted!');
    }
}
