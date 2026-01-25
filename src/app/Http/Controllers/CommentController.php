<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Comment;
use App\Http\Requests\CommentRequest;

class CommentController extends Controller
{
    public function store(CommentRequest $request, Item $product)
    {
        Comment::create([
            'user_id' => auth()->id(),
            'item_id' => $product->id,
            'content' => $request->content,
        ]);

        return redirect('/item/' . $product->id);
    }
}
