<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Favorite;

class FavoriteController extends Controller
{
    public function toggle(Item $product)
    {
        if (!auth()->check() || !auth()->user()->hasVerifiedEmail()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = auth()->user();
        
        $favorite = $product->favorites()->where('user_id', $user->id)->first();
        
        if ($favorite) {
            $favorite->delete();
            $favorited = false;
        } else {
            Favorite::create([
                'user_id' => $user->id,
                'item_id' => $product->id,
            ]);
            $favorited = true;
        }
        
        return response()->json([
            'favorited' => $favorited,
            'count' => $product->favorites()->count(),
        ]);
    }
}
