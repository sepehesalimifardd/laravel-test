<?php

namespace App\Observers;

use App\Models\Product;
use App\Models\ProductVersion;
use Illuminate\Support\Facades\Auth;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product)
    {
        $changes = $product->getChanges();

        unset($changes['updated_at']);

        if (empty($changes)) {
            return;
        }

        $originalData = [];
        foreach ($changes as $key => $value) {
            $originalData[$key] = $product->getOriginal($key);
        }

        ProductVersion::create([
            'product_id' => $product->id,
            'user_id' => auth()->id(),
            'old_data' => $originalData,
            'new_data' => $changes,
            'changed_fields' => array_keys($changes)
        ]);
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "restored" event.
     */
    public function restored(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "force deleted" event.
     */
    public function forceDeleted(Product $product): void
    {
        //
    }
}
