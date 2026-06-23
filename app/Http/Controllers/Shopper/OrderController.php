<?php

namespace App\Http\Controllers\Shopper;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Midtrans\Config;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\City;
use App\Models\Province;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Midtrans\Snap;
use Midtrans\Transaction;
use Inertia\Inertia;

class OrderController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = config('services.midtrans.serverKey');
        Config::$isProduction = config('services.midtrans.isProduction');
        Config::$isSanitized  = config('services.midtrans.isSanitized');
        Config::$is3ds = config('services.midtrans.is3ds');
    }

    public function index()
    {
        $orders = Order::with('orderItem')->where('user_id', auth()->user()->id)->latest()->get();

        return Inertia::render('Shopper/Orders/Index', [
            'orders' => $orders,
        ]);
    }

    public function checkout()
    {
        $user = auth()->user();

        $snap_token = DB::transaction(function () use ($user) {
            $total_price = (int) Cart::where('user_id', $user->id)
                ->join('products', 'products.id', 'carts.product_id')
                ->sum(DB::raw('products.price * carts.qty'));

            $order = Order::create([
                'id' => Str::uuid(),
                'user_id' => $user->id,
                'payment_type' => 'Belum dipilih',
                'transaction_time' => Carbon::now()
            ]);

            $user_carts = Cart::with('product')->where('user_id', $user->id)->get();

            foreach ($user_carts as $user_cart) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $user_cart->product_id,
                    'price' => $user_cart->product->price,
                    'qty' => $user_cart->qty,
                    'total_price' => $user_cart->product->price * $user_cart->qty,
                ]);
            }

            $payload = [
                'transaction_details' => [
                    'order_id' => $order->id,
                    'gross_amount' => $total_price,
                ],
                'customer_details' => [
                    'name' => $user->name,
                    'email' => $user->email,
                ],
                'item_details' => Cart::where('user_id', $user->id)
                    ->join('products', 'products.id', 'carts.product_id')
                    ->select(DB::raw('products.id as id'), DB::raw('products.name as name'), DB::raw('products.price as price'), DB::raw('carts.qty as quantity'))
                    ->get()->toArray()
            ];

            $snap_token = Snap::getSnapToken($payload);
            $order->snap_token = $snap_token;
            $order->save();

            return $snap_token;
        });

        return response()->json([
            'snap_token' => $snap_token,
        ]);
    }

    public function finish(Request $request)
    {
        $is_finished = Order::where('id', $request->order_id)->first()->is_finished;
        if ($is_finished) return redirect()->back();
        Order::where('id', $request->order_id)->update(['is_finished' => 1]);
        return Inertia::render('Shopper/Orders/Finish');
    }

    public function notification(Request $request)
    {
        $order_id = $request->order_id;

        $response = Http::withBasicAuth(Config::$serverKey, '')
            ->get('https://api.sandbox.midtrans.com/v2/' . urlencode($request->order_id) . '/status');

        $transaction = $response['transaction_status'];
        $type = $response['payment_type'];
        $fraud = $response['fraud_status'];

        Order::where('id', $order_id)->update([
            'payment_type' => $type,
            'transaction_time' => $response['transaction_time']
        ]);

        if ($transaction === 'capture') {
            if ($type === 'credit_card' && $fraud === 'deny') {
                Order::where('id', $order_id)->update(['status' => 'deny']);
            } else {
                Order::where('id', $order_id)->update(['status' => 'settlement']);
            }
        } else if ($transaction === 'settlement') {
            Order::where('id', $order_id)->update(['status' => 'settlement']);
        } else if ($transaction === 'pending') {
            Order::where('id', $order_id)->update(['status' => 'pending']);
        } else if ($transaction === 'deny') {
            Order::where('id', $order_id)->update(['status' => 'deny']);
        } else if ($transaction === 'expire') {
            Order::where('id', $order_id)->update(['status' => 'expire']);
        } else if ($transaction === 'cancel') {
            Order::where('id', $order_id)->update(['status' => 'cancel']);
        }
    }

    public function cancel($orderId)
    {
        $cancel = Transaction::cancel($orderId);

        if (($cancel->status_code ?? null) === '200') {
            Order::where('id', $orderId)->update([
                'status' => 'cancel',
            ]);
        }

        return redirect()->route('orders.index');
    }

    public function shipping()
    {
        $cities = json_decode(Http::withHeaders([
            'key' => config('services.rajaongkir.apiKey')
        ])->get('https://api.rajaongkir.com/starter/city'), true);

        $cities = $cities['rajaongkir']['results'];

        // return $cities[0]['province_id'];

        foreach ($cities as $city) {
            // echo $city['province_id'];
            City::create([
                'name' => $city['city_name'],
                'province_id' => $city['province_id'],
                'postal_code' => $city['postal_code']
            ]);
        }

        return response()->json();
    }

    public function buyNow(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'qty' => ['nullable', 'integer', 'min:1'],
        ]);

        $user = auth()->user();
        $qty = $request->qty ?? 1;

        $snap_token = DB::transaction(function () use ($user, $request, $qty) {
            $product = \App\Models\Product::findOrFail($request->product_id);

            $total_price = $product->price * $qty;

            $order = Order::create([
                'id' => Str::uuid(),
                'user_id' => $user->id,
                'payment_type' => 'Belum dipilih',
                'transaction_time' => Carbon::now(),
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'price' => $product->price,
                'qty' => $qty,
                'total_price' => $total_price,
            ]);

            $payload = [
                'transaction_details' => [
                    'order_id' => $order->id,
                    'gross_amount' => $total_price,
                ],
                'customer_details' => [
                    'name' => $user->name,
                    'email' => $user->email,
                ],
                'item_details' => [[
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->price,
                    'quantity' => $qty,
                ]],
            ];

            $snap_token = Snap::getSnapToken($payload);

            $order->snap_token = $snap_token;
            $order->save();

            return $snap_token;
        });

        return response()->json([
            'snap_token' => $snap_token,
        ]);
    }
}
