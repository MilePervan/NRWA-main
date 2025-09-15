<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\manager; 

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');
        $results = manager::where('name', 'LIKE', "%{$query}%")->get();
        return response()->json($results);
    }
}