<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use App\Models\Cart;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;


class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {

        return [
            ...parent::share($request),
            'snap_token' => session('snap_token'),
            'flash' => [
                'message' => session('message')
            ],
            'auth' => [
                'user' => auth()->user(),
            ],
            'carts' => function () {
                if (!auth()->check()) {
                    return null;
                }

                // return DB::select('
                //     SELECT
                //         carts.*,
                //         products.name AS product_name,
                //         image_products.url AS product_url,
                //         products.price AS product_price
                //     FROM
                //         carts
                //     WHERE
                //         carts.user_id = ?
                //     LEFT JOIN
                //         (
                //             SELECT
                //                 carts.product_id AS product_id,
                //                 carts.user_id AS user_id,
                //                 MIN(image_products.id) AS first_image_id
                //             FROM
                //                 carts
                //             LEFT JOIN
                //                 image_products ON image_products.product_id = carts.product_id
                //             GROUP BY
                //                 carts.product_id, carts.user_id
                //         ) AS first_images first_images.product_id = products.id AND first_images.user_id = carts.user_id
                //     LEFT JOIN
                //         image_products ON image_products.id = first_images.first_image_id
                //     LEFT JOIN
                //         products ON products.id = carts.product_id
                //     GROUP BY
                //         carts.product_id, carts.user_id, image_products.id
                // ', [auth()->user()->id]);

                // return Cache::rememberForever('carts', function () {
                //     return Cart::where('carts.user_id', auth()->user()->id)
                //         ->join('products', 'products.id', '=', 'carts.product_id')
                //         ->join(DB::raw('(
                //                 SELECT
                //                     carts.product_id AS product_id,
                //                     carts.user_id AS user_id,
                //                     MIN(image_products.id) AS first_image_id
                //                 FROM
                //                     carts
                //                 LEFT JOIN
                //                     image_products ON image_products.product_id = carts.product_id
                //                 GROUP BY
                //                     carts.product_id, carts.user_id
                //             ) AS first_images
                //         '), function ($join) {
                //             $join->on('first_images.product_id', '=', 'products.id');
                //             $join->on('first_images.user_id', '=', 'carts.user_id');
                //         })
                //         ->join('image_products', 'image_products.id', '=', 'first_images.first_image_id')
                //         ->select(
                //             'carts.*',
                //             DB::raw('`products`.`name` AS product_name'),
                //             DB::raw('`image_products`.`url` AS product_url'),
                //             DB::raw('`products`.`id` AS product_id'),
                //             DB::raw('`products`.`price` AS product_price'),
                //             DB::raw('products.stock AS product_stock'),
                //             DB::raw('products.min_order AS product_min_order')
                //         )
                //         ->get();
                // });

                return Cart::where('carts.user_id', auth()->user()->id)
                    ->join('products', 'products.id', '=', 'carts.product_id')
                    ->join(DB::raw('(
                                SELECT
                                    carts.product_id AS product_id,
                                    carts.user_id AS user_id,
                                    MIN(image_products.id) AS first_image_id
                                FROM
                                    carts
                                LEFT JOIN
                                    image_products ON image_products.product_id = carts.product_id
                                GROUP BY
                                    carts.product_id, carts.user_id
                            ) AS first_images
                        '), function ($join) {
                        $join->on('first_images.product_id', '=', 'products.id');
                        $join->on('first_images.user_id', '=', 'carts.user_id');
                    })
                    ->join('image_products', 'image_products.id', '=', 'first_images.first_image_id')
                    ->select(
                        'carts.*',
                        DB::raw('`products`.`name` AS product_name'),
                        DB::raw('`image_products`.`url` AS product_url'),
                        DB::raw('`products`.`id` AS product_id'),
                        DB::raw('`products`.`price` AS product_price'),
                        DB::raw('products.stock AS product_stock'),
                        DB::raw('products.min_order AS product_min_order')
                    )
                    ->get();
            },
            'categories' => function () {
                return Cache::rememberForever('categories', function () {
                    return Category::where('status', 1)->get();
                });
            },
        ];
    }
}
