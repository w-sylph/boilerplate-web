<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Locations\Location;

class LocationController extends Controller
{
    public function fetch(Request $request) {
    	$query = Location::select(['parent_code', 'code', 'name']);

        if ($request->filled('parent_code')) {
            $query = $query->where('parent_code', $request->input('parent_code'));
        } else {
            $query = $query->whereNull('parent_code');
        }

    	$items = $query->orderBy('name', 'asc')->get();

    	return response()->json($items);
    }
}
