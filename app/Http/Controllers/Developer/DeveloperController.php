<?php

namespace App\Http\Controllers\Developer;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Developer\DeveloperChangeAccountRequest;

use App\Models\Users\Admin;
use App\Models\Users\User;

use Auth;

class DeveloperController extends Controller
{
	public function changeAccount(DeveloperChangeAccountRequest $request)
	{
		switch ($request->input('guard')) {
			case 'admin':
					$user = Admin::findOrFail($request->input('user'));
				break;
			
			case 'web':
					$user = User::findOrFail($request->input('user'));
				break;
		}

		Auth::guard($request->input('guard'))->login($user);

		return response()->json([
			'redirect' => url()->previous(),
		]);
	}

    public function fetchUsers()
    {
    	$admins = Admin::get();
    	$users = User::get();

    	return response()->json([
    		'admins' => $admins,
    		'users' => $users,
    	]);
    }
}
