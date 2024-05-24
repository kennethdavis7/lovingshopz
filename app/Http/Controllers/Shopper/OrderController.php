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
        $orders = Order::with('orderItem')->where('user_id', auth()->user()->id)->get();

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
            // TODO: For credit card transaction, we need to check whether transaction is challenge by FDS or not
            if ($type === 'credit_card') {
                if ($fraud === 'deny') {
                    // TODO set payment status in merchant's database to 'Challenge by FDS'
                    // TODO merchant should decide whether this transaction is authorized or not in MAP
                    echo "Transaction order_id: " . $order_id . " is denied by FDS";
                } else {
                    // TODO set payment status in merchant's database to 'Success'
                    echo "Transaction order_id: " . $order_id . " successfully captured using " . $type;
                }
            }
        } else if ($transaction === 'settlement') {
            // TODO set payment status in merchant's database to 'Settlement'
            Order::where('id', $request->order_id)->update(['status' => 'settlement']);
            echo "Transaction order_id: " . $order_id . " successfully transfered using " . $type;
        } else if ($transaction === 'pending') {
            // TODO set payment status in merchant's database to 'Pending'
            echo "Waiting customer to finish transaction order_id: " . $order_id . " using " . $type;
        } else if ($transaction === 'deny') {
            // TODO set payment status in merchant's database to 'Denied'
            Order::where('id', $request->order_id)->update(['status' => 'deny']);
            echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is denied.";
        } else if ($transaction === 'expire') {
            // TODO set payment status in merchant's database to 'expire'
            Order::where('id', $request->order_id)->update(['status' => 'expire']);
            echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is expired.";
        } else if ($transaction === 'cancel') {
            // TODO set payment status in merchant's database to 'Denied'
            Order::where('id', $request->order_id)->update(['status' => 'cancel']);
            echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is canceled.";
        }
    }

    public function cancel($orderId)
    {
        $response = Http::withBasicAuth(Config::$serverKey, '')
            ->post('https://api.sandbox.midtrans.com/v2/' . urlencode($orderId) . '/cancel');

        return $response;
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
}
