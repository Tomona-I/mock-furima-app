<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Http\Requests\StoreProductRequest;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'recommended');
        $keyword = $request->query('keyword');
        
        if ($tab === 'mylist' && auth()->check()) {
            $query = auth()->user()->favorites();
            
            if ($keyword) {
                $query->whereHas('product', function ($q) use ($keyword) {
                    $q->where('name', 'like', '%' . $keyword . '%');
                });
            }
            
            $favorites = $query->orderBy('created_at', 'desc')->get();
            $products = $favorites->pluck('product');
        } else {
            $query = Product::query();
            
            if (auth()->check()) {
                $query->where('user_id', '!=', auth()->id());
            }
            
            if ($keyword) {
                $query->where('name', 'like', '%' . $keyword . '%');
            }
            
            $products = $query->get();
        }
        
        return view('index', ['products' => $products, 'tab' => $tab, 'keyword' => $keyword]);
    }

    public function create()
    {
        $categories = Category::all();
        return view('sell', ['categories' => $categories]);
    }

    public function store(StoreProductRequest $request)
    {
        $validated = $request->validated();
        
        $validated['product_image'] = $request->file('product_image')->store('products', 'public');
        
        $validated['user_id'] = auth()->id();
        
        $product = Product::create($validated);
        
        $product->categories()->attach($request->categories);
        
        return redirect('/mypage');
    }

    public function show(Product $product)
    {
        $comments = $product->comments()->with('user')->orderBy('created_at', 'desc')->get();
        
        $favoriteCount = $product->favorites()->count();
        
        $commentCount = $comments->count();
        
        $isFavorited = auth()->check() ? $product->favorites()->where('user_id', auth()->id())->exists() : false;
        
        return view('item', [
            'product' => $product,
            'comments' => $comments,
            'favoriteCount' => $favoriteCount,
            'commentCount' => $commentCount,
            'isFavorited' => $isFavorited,
        ]);
    }
}