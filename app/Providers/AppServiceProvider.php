<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Models\Product;
use App\Models\Category;
use App\Models\Cart;
use App\Observers\ProductObserver;
use App\Observers\CartObserver;
use App\Observers\CategoryObserver;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::directive('money', function ($amount) {
            return "<?php echo 'Rp' . number_format($amount, 2,',','.'); ?>";
        });

        Product::observe(ProductObserver::class);
        Category::observe(CategoryObserver::class);
        Cart::observe(CartObserver::class);

        URL::forceScheme('https');
    }
}
