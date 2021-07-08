<?php

namespace App\Helpers;

use Auth;
use Illuminate\Support\Str;

class AuthHelper
{
	public static function getGuard($request) {
		$class = get_class($request->user());

		switch ($class) {
			case 'App\Models\Users\Admin':
				return 'admin';
			case 'App\Models\Users\Merchant':
				return 'merchant';
			case 'App\Models\Users\User':
				return 'web';
		}

		return false;
	}

	public function hasAnyPermission($permissions) {
		$result = false;

		if ($this->authenticated('admin')) {
			$result = auth('admin')->user()->hasAnyPermission($permissions);
		}

		return $result;
	}

	public function renderName($format = 'f l') {
		$result = null;

		if (auth()->check()) {
			$result = Str::limit(auth()->user()->renderName($format), 12, '');
		}

		return $result;
	}

	public function renderAvatar() {
		$result = null;

		if (auth()->check()) {
			$result = auth()->user()->renderFilePath();
		}

		return $result;
	}

	public function renderTheme() {
		$result = 'light';

		if (auth()->check()) {
			$user = auth()->user();
			$result = optional($user->settings)->theme;
		}

		return $result;
	}

	public function authenticated($guard = null) {
		return auth($guard)->check();
	}

	public function hasPasswordExpired() {
		$result = false;

		if (auth('admin')->check()) {
			$result = auth('admin')->user()->hasPasswordExpired();
		}

		return $result;
	}
}