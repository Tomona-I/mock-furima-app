<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Requests\StorePurchaseRequest;

class PurchaseController extends Controller
{
    public function show(Product $product)
    {
        $user = auth()->user();

        return view('purchase', [
            'product' => $product,
            'user' => $user,
        ]);
    }

    public function store(StorePurchaseRequest $request, Product $product)
    {
        $validated = $request->validated();
        $user = auth()->user();

        $postal_code = $validated['postal_code'] ?? $user->postal_code;
        $address = $validated['address'] ?? $user->address;
        $building = $validated['building'] ?? $user->building;

        $order = $product->orders()->create([
            'user_id' => auth()->id(),
            'price' => $product->price,
            'postal_code' => $postal_code,
            'address' => $address,
            'building' => $building ?? '',
            'payment_method' => $validated['payment_method'],
            'condition' => 'completed',
        ]);

        $product->update(['is_sold' => true]);

        return redirect('/index');
    }
}
