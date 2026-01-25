<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Http\Requests\StorePurchaseRequest;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PurchaseController extends Controller
{
    public function show(Item $product)
    {
        $user = auth()->user();

        return view('purchase', [
            'product' => $product,
            'user' => $user,
        ]);
    }

    public function store(StorePurchaseRequest $request, Item $product)
    {
        $validated = $request->validated();
        $user = auth()->user();

        $paymentMethod = $request->input('payment_method');
        
        $postal_code = $validated['postal_code'] ?? $user->postal_code;
        $address = $validated['address'] ?? $user->address;
        $building = $validated['building'] ?? $user->building;

        $order = $product->orders()->create([
            'user_id' => auth()->id(),
            'price' => $product->price,
            'postal_code' => $postal_code,
            'address' => $address,
            'building' => $building ?? '',
            'payment_method' => $paymentMethod,
        ]);

        $product->update(['is_sold' => true]);

        $paymentMethods = [];
        if ($paymentMethod === 'card') {
            $paymentMethods = ['card'];
        } else if ($paymentMethod === 'convenience') {
            $paymentMethods = ['konbini'];
        }

        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        if (app()->environment('testing')) {
            return redirect('/');
        }

        try {
            $session = Session::create([
                'payment_method_types' => $paymentMethods,
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'jpy',
                        'product_data' => [
                            'name' => $product->name,
                        ],
                        'unit_amount' => $product->price,
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'success_url' => route('index') . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('purchase.show', $product->id),
                'metadata' => [
                    'order_id' => $order->id,
                ],
            ]);

            return redirect($session->url);
        } catch (\Exception $e) {
            $order->delete();
            $product->update(['is_sold' => false]);
            return back()->withErrors(['stripe' => '決済ページの作成に失敗しました: ' . $e->getMessage()]);
        }
    }
}
