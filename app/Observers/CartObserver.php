<?php

namespace App\Observers;

use App\Models\Cart;
use Illuminate\Support\Facades\Cache;

class CartObserver
{
    /**
     * Handle the Cart "created" event.
     */
    public function created(Cart $cart): void
    {
        // Cache::forget('carts');
    }

    /**
     * Handle the Cart "updated" event.
     */
    public function updated(Cart $cart): void
    {
        // Cache::forget('carts');
    }

    /**
     * Handle the Cart "deleted" event.
     */
    public function deleted(Cart $cart): void
    {
        // Cache::forget('carts');
    }

    /**
     * Handle the Cart "restored" event.
     */
    public function restored(Cart $cart): void
    {
        //
    }

    /**
     * Handle the Cart "force deleted" event.
     */
    public function forceDeleted(Cart $cart): void
    {
        //
    }
}
