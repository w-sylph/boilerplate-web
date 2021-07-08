<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SortableController extends Controller
{
    public function sort(Request $request) {

        switch ($request->input('type')) {
            case 'file':
                    $model = 'App\Models\Mutators\FileRecord';
                break;
            case 'sample':
                    $model = 'App\Models\Samples\SampleItem';
                break;
            case 'article':
                    $model = 'App\Models\Articles\Article';
                break;
            case 'collection':
                    $model = 'App\Models\Collections\Collection';
                break;
            case 'carousel':
                    $model = 'App\Models\Carousels\Carousel';
                break;
            case 'product':
                    $model = 'App\Models\Products\Product';
                break;
            case 'product-category':
                    $model = 'App\Models\Products\ProductCategory';
                break;
            case 'option':
                    $model = 'App\Models\Products\ProductOption';
                break;
            case 'option-value':
                    $model = 'App\Models\Products\ProductOptionValue';
                break;
            case 'payment-type':
                    $model = 'App\Models\Products\PaymentType';
                break;
            case 'shipping-rate':
                    $model = 'App\Models\ShippingRates\ShippingRate';
                break;
            default:
                    abort(404);
                break;
        }

        if (in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses($model))) {
            $current = $model::withTrashed()->findOrFail($request->input('current'));
            $previous = $model::withTrashed()->findOrFail($request->input('previous'));
            $next = $model::withTrashed()->findOrFail($request->input('next'));
        } else {
            $current = $model::findOrFail($request->input('current'));
            $previous = $model::findOrFail($request->input('previous'));
            $next = $model::findOrFail($request->input('next'));
        }

        if ($request->input('willInsertAfter')) {
            $current->moveAfter($previous);
        } else {
            $current->moveBefore($next);
        }

    	return response()->json(true);
    }
}
