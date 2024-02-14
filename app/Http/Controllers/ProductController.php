<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Product;
use App\Models\Category;
use App\Models\ImageProduct;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('category', 'images')->paginate(20);
        return Inertia::render('Admin/Products/Index', [
            'products' => $products
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
        $validatedData = $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'qty' => 'required|numeric',
            'category' => 'required',
            'description' => 'required',
            'status' => 'required',
            'min_order' => 'required|numeric',
            'image' => 'image|file|mimes:jpeg,png,jpg'
        ]);

        $product = [
            "name" => $request->name,
            "price" => $request->price,
            "qty" => $request->qty,
            "description" => $request->description,
            "status" => $request->status,
            "min_order" => $request->min_order
        ];

        $image = [];

        $files = $request->file('images');

        if ($request->hasFile('images')) {
            foreach ($files as $file) {
                $image['product_id'] = Product::count() + 1;
                $image['url'] = 'storage/' . $file->store('img');
                $splitImage = explode("/", $image['url']);
                $image['alt'] = end($splitImage);

                ImageProduct::create($image);
            }
        }

        $product['category_id'] = Category::where('name', $validatedData['category'])->first()->id;

        Product::create($product);

        return redirect()->route('products.index')->with('successMessage', $product['name'] . ' has been succesfully added!');
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
            'product' => $product
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
