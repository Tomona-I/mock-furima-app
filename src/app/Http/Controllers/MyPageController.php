<?php

namespace App\Http\Controllers;

class MyPageController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        $page = request()->query('page', 'sell');
        
        if ($page === 'buy') {
            $purchasedOrders = $user->orders()->with('product')->latest()->get();
            return view('mypage', [
                'user' => $user,
                'purchasedOrders' => $purchasedOrders,
                'page' => $page,
            ]);
        }
        
        $listedProducts = $user->products()->latest()->get();
        return view('mypage', [
            'user' => $user,
            'listedProducts' => $listedProducts,
            'page' => $page,
        ]);
    }
}
