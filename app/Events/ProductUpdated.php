<?php

namespace App\Events;

use App\Models\Product;
use App\Models\ProductVersion;
use Illuminate\Foundation\Events\Dispatchable;

class ProductUpdated
{
    use Dispatchable;

    public $product;
    public $version;

    public function __construct(Product $product, ProductVersion $version)
    {
        $this->product = $product;
        $this->version = $version;
    }
}
