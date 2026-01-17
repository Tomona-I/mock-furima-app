<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Models\Product;

class ProfileController extends Controller
{
    public function edit(Product $product = null)
    {
        $user = auth()->user();
        
        return view('profile_edit', ['user' => $user, 'product' => $product]);
    }

    public function update(ProfileRequest $request, Product $product = null)
    {
        $user = auth()->user();
        $validated = $request->validated();

        if ($request->hasFile('profile_image')) {
            $validated['profile_image'] = $request->file('profile_image')->store('profile_images', 'public');
        }

        $user->update($validated);

        // productパラメータで戻り先を判定
        if ($product) {
            return redirect()->route('purchase.show', $product);
        }

        return redirect('/profile_edit');
    }
}
