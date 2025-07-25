<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Attribute;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class ProductController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;
    public function index()
    {
        $products = Product::currentUser()
            ->with('attributes')
            ->orderByRaw('stock > 0 DESC')
            ->orderBy('price')
            ->get();

        return response()->json($products);
    }

    public function show(Product $product)
    {
        $this->authorize('view', $product);


        return response()->json($product->load('attributes'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'attributes' => 'array',
            'attributes.*.id' => 'required|exists:attributes,id',
            'attributes.*.value' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $product = auth()->user()->products()->create($request->only([
            'title', 'content', 'price', 'stock', 'category_id'
        ]));

        foreach ($request->input('attributes', []) as $attribute) {
            $product->attributes()->attach($attribute['id'], [
                'value' => $attribute['value']
            ]);
        }

        return response()->json($product->load('attributes'), 201);
    }

    public function update(Request $request, Product $product)
    {
        $this->authorize('update', $product);


        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'content' => 'nullable|string',
            'price' => 'sometimes|numeric|min:0',
            'stock' => 'sometimes|integer|min:0',
            'attributes' => 'array',
            'attributes.*.id' => 'required|exists:attributes,id',
            'attributes.*.value' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $product->update($request->only([
            'title', 'content', 'price', 'stock'
        ]));

        $attributesSync = [];
        foreach ($request->input('attributes', []) as $attribute) {
            $attributesSync[$attribute['id']] = ['value' => $attribute['value']];
        }
        $product->attributes()->sync($attributesSync);

        return response()->json($product->load('attributes'));
    }
}
