<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductVersion;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use App\Http\Resources\ProductVersionResource;
use App\Http\Resources\ProductVersionDetailResource;

class ProductHistoryController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    public function index(Product $product)
    {
        $this->authorize('view', $product);

        $history = $product->versions()
            ->with('user:id,name')
            ->orderBy('created_at', 'desc')
            ->get();

        return ProductVersionResource::collection($history);
    }

    public function show(Product $product, ProductVersion $version)
    {
        $this->authorize('view', $product);

        if ($version->product_id !== $product->id) {
            abort(404, 'Version not found for this product');
        }

        return new ProductVersionDetailResource($version);
    }
}
