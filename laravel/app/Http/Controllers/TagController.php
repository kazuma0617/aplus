<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tag;

class TagController extends Controller
{
    public function suggest(Request $request)
    {
        $keyword = $request->input('keyword');

        if (!$keyword) {
            return response()->json([]);
        }

        $tags = Tag::where('name', 'LIKE', $keyword . '%')
                    ->limit(10)
                    ->get(['id', 'name']);

        return response()->json($tags);
    }
}
