<?php

namespace App\Http\Controllers\Guest\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\Controllers\PageTrait;

use App\Models\Products\Product;
use App\Models\Products\ProductCategory;
use App\Models\Carousels\Carousel;
use App\Models\Collections\Collection;

class PageController extends Controller
{
	use PageTrait;

	/**
	 * Show Home
	 * @return [type] [description]
	 */
	public function showHome() {

        return $this->view('guest.pages.home', 'home', [
        	//
        ]);

	}

	/**
	 * Show Terms and Conditions
	 * @return [type] [description]
	 */
	public function showTerms() {

        return $this->view('guest.pages.terms', 'terms-and-conditions', [
        	//
        ]);

	}

	/**
	 * Show Privacy Policy
	 * @return [type] [description]
	 */
	public function showPrivacy() {

        return $this->view('guest.pages.privacy', 'privacy-policy', [
        	//
        ]);

	}
}
