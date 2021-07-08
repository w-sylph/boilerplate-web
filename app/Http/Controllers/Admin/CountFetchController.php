<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Samples\SampleItem;
use App\Models\Products\Invoice;

class CountFetchController extends Controller
{
    public function fetchNotificationCount(Request $request) {
    	$user = $request->user();

    	$count = $user->unreadNotifications()->count();

    	return response()->json([
    		'count' => $count,
    	]);
    }

    public function fetchSampleItemCount(Request $request) {
    	$count = SampleItem::where('status', SampleItem::STATUS_PENDING)->count();

    	return response()->json([
    		'count' => $count,
    	]);
    }

    public function fetchInvoiceCount(Request $request) {
        if (! $request->filled('pending') && ! $request->filled('paid') && ! $request->filled('in-transit')) {
            $statuses = [
                Invoice::STATUS_PENDING,
                Invoice::STATUS_PAID,
                Invoice::STATUS_INTRANSIT,
            ];

            $count = Invoice::active()->whereIn('status', $statuses)->count();
        }

        if ($request->filled('pending')) {
            $count = Invoice::active()->where('status', Invoice::STATUS_PENDING)->count();
        }

        if ($request->filled('paid')) {
            $count = Invoice::active()->where('status', Invoice::STATUS_PAID)->count();
        }

        if ($request->filled('in-transit')) {
            $count = Invoice::active()->where('status', Invoice::STATUS_INTRANSIT)->count();
        }

        return response()->json([
            'count' => $count,
        ]);
    }
}
