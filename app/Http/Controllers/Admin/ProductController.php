<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Product;
use App\Models\Category;
use App\Models\ImageProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Barryvdh\Snappy\Facades\SnappyPdf as pdf;
use App\Http\Controllers\Controller;
use App\Rules\BelowStockRule;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate([
            'sort_by' => ['sometimes', 'nullable', 'string', Rule::in('created_at', 'name', 'categories.name', 'stock', 'price')],
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

        foreach ($products as $product) {
            $product->status = $product->status === 1;
        }

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
            'price' => 'required|numeric|min:100',
            'category_id' => 'required',
            'description' => 'required',
            'status' => 'required|boolean',
            'min_order' => ['required', 'numeric', 'min:1', new BelowStockRule($request->stock)],
            'images' => 'required',
            'images.*' => 'required|image|file|mimes:jpeg,png,jpg|max:8192',
            'stock' => 'nullable|numeric|min:1'
        ], [
            'images.*.image' => 'All of the files must be an image type'
        ]);

        $product = Product::create([
            "name" => $request->name,
            "price" => $request->price,
            "stock" => $request->stock,
            "description" => $request->description,
            "status" => $request->status,
            "min_order" => $request->min_order,
            "category_id" => $request->category_id,
        ]);


        $files = $request->file('images');

        foreach ($files as $file) {
            ImageProduct::create([
                'product_id' => $product->id,
                'url' => 'storage/' . $file->store('images'),
                'alt' => '',
            ]);
        }

        return redirect()->route('products.index')->with('message', $product['name'] . ' has been succesfully added!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        return Inertia::render('Products/Show', [
            'product' => $product->load('images', 'category')
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $product->status = $product->status === 1;
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
            'price' => 'required|numeric|min:100',
            'category_id' => 'required',
            'description' => 'required',
            'status' => 'required|boolean',
            'stock' => 'nullable|numeric|min:1',
            'images_to_delete' => 'array',
            'min_order' => ['required', 'numeric', 'min:1', new BelowStockRule($request->stock)],
            'new_images.*' => 'file|image|mimes:jpeg,png,jpg|max:8192',
            'images_to_delete.*' => 'string',
            'total_images' => ['required', 'numeric', function ($attribute, $value, $fail) use ($request, $product) {
                $images_to_delete = $request->images_to_delete == null ? 0 : count($request->images_to_delete);
                $total_images = count($request->new_images) - $images_to_delete + $product->images()->count();
                if ($total_images <= 0) {
                    $fail('There must be at least 1 image');
                }
            }],
        ], [
            'new_images.*.image' => 'All of the files must be an image type',
            'total_images.min' => 'There must be at least 1 image'
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
            unlink($image);
        }

        $product->update([
            "name" => $request->name,
            "price" => $request->price,
            "stock" => $request->stock,
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
        $product_images = $product->images;

        foreach ($product_images as $image) {
            unlink($image->url);
        }

        $product->delete();

        return redirect()->route('products.index')->with('message', $product['name'] . ' has been succesfully deleted!');
    }
}
