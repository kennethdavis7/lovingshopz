<?php

namespace App\Http\Controllers\Shopper;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Inertia\Inertia;
use App\Models\Cart;
use App\Models\Product;

class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user_id = auth()->user()->id;

        $request->validate([
            'qty' => ['required', 'numeric', function ($attribute, $value, $fail) use ($request, $user_id) {
                $product = Product::where('id', $request->product_id)->first();
                $cart_item = Cart::where('product_id', $request->product_id)->where('user_id', $user_id)->first();

                $current_qty = $cart_item->qty ?? 0;
                $stock = $product->stock ?? 10000;
                $max = $stock - $current_qty;
                $min = $product->min_order ?? 1;

                if ($value < $min) return $fail("Kuantitas minimal " . $min);
                if ($current_qty >= $stock) return $fail('Kuantitas di dalam keranjang telah penuh');
                if ($value > $max) return $fail('Kuantitas maximal ' . $max);
            }],
            'product_id' => ['exists:products,id']
        ]);

        if (Cart::where('product_id', $request->product_id)->count() > 0) {
            $cart_item = Cart::where('product_id', $request->product_id)->first();

            $old_qty = $cart_item->qty;
            $new_qty = $old_qty + $request->qty;

            $cart_item->update(['qty' => $new_qty]);
        } else {
            Cart::create([
                'qty' => $request->qty,
                'product_id' => $request->product_id,
                'user_id' => $user_id,
            ]);
        }
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,  Cart $cart)
    {
        $validatedData = $request->validate([
            'qty' => ['required', 'numeric', 'min:1', function ($attribute, $value, $fail) use ($cart) {
                $max = $cart->product()->first()->stock ?? 10000;
                $min = $cart->product()->first()->min_order ?? 1;
                // $current_qty = $cart->qty ?? 0;
                // $stock = Product::where('id', $cart->id)->first()->stock;

                if ($value < $min) return $fail("Kuantitas minimal " . $min);
                // if ($current_qty >= $stock) return $fail('Kuantitas di dalam keranjang telah penuh');
                if ($value > $max) return $fail("Kuantitas maximal " . $max);
            }]
        ]);

        $cart->update($validatedData);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cart $cart)
    {
        $cart->delete();
    }
}
