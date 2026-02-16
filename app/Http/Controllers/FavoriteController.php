<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function toggle(Request $request)
    {
        $validated = $request->validate([
            'favoritable_id' => 'required|integer',
            'favoritable_type' => 'required|string',
        ]);

        $user = auth()->user();
        
        $favorite = Favorite::where('user_id', $user->id)
            ->where('favoritable_id', $validated['favoritable_id'])
            ->where('favoritable_type', $validated['favoritable_type'])
            ->first();

        if ($favorite) {
            $favorite->delete();
            return response()->json(['status' => 'removed']);
        } else {
            Favorite::create([
                'user_id' => $user->id,
                'favoritable_id' => $validated['favoritable_id'],
                'favoritable_type' => $validated['favoritable_type'],
            ]);
            return response()->json(['status' => 'added']);
        }
    }
}
