<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Comment;
use App\Http\Requests\CommentRequest;

class CommentController extends Controller
{
    public function store(CommentRequest $request, Product $product)
    {
        Comment::create([
            'user_id' => auth()->id(),
            'product_id' => $product->id,
            'content' => $request->content,
        ]);

        return redirect('/item/' . $product->id);
    }
}
