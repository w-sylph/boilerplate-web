<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;

use App\Rules\Image;

class DropzoneController extends Controller
{
    public function store(Request $request)
    {
		$response = [ 'success' => 'File has successfully been upload' ];
    	$status = 200;
    	$validator = Validator::make(['file' => $request->file('file')], [
    		'file' => new Image
    	]);

    	if ($validator->fails()) {
    		$response = [ 'error' => $validator->errors()->first() ];
			$status = 422;
    	}

    	return response()->json($response, $status);
    }
}
