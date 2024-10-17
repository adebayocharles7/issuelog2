<?php

namespace App\Http\Controllers;

use App\Models\SanctionList;
use Illuminate\Http\Request;
use App\Models\WatchlistedBvn;
use Illuminate\Support\Facades\Validator;

class SearchController extends Controller
{
    
       // Handle the search query
       public function searchData(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'query' => 'required|string|min:3|max:50|regex:/^[A-Za-z0-9\s]+$/',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        // Sanitize the query (e.g., trim spaces)
        $query = trim($request->input('query'));

        // Search users by name or email
        $watchlisted_persons = WatchlistedBvn::where('first_name', 'like', "%{$query}%")
                     ->orWhere('middle_name', 'like', "%{$query}%")
                     ->orWhere('surname', 'like', "%{$query}%")
                     ->orWhere('requesting_bank_id', 'like', "%{$query}%")
                     ->orWhere('bvn', 'like', "%{$query}%")
                     ->get();

        // Search posts by title or content
        $sanctioned_persons = SanctionList::where('bvn', 'like', "%{$query}%")
                     ->orWhere('name', 'like', "%{$query}%")
                     ->get();

        
       
        return view('search_results', compact('watchlisted_persons','sanctioned_persons','query'));
    }

    /*public function searchData */
}
