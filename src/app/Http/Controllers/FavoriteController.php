<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Favorite;

class FavoriteController extends Controller
{
    public function toggle(Product $product)
    {
        $user = auth()->user();
        
        $favorite = $product->favorites()->where('user_id', $user->id)->first();
        
        if ($favorite) {
            $favorite->delete();
            $favorited = false;
        } else {
            Favorite::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
            ]);
            $favorited = true;
        }
        
        return response()->json([
            'favorited' => $favorited,
            'count' => $product->favorites()->count(),
        ]);
    }
}
