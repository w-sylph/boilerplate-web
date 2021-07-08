<?php

namespace App\Http\Controllers\Web;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\Products\Invoice;
use App\Models\Products\PaymentProvider;

class CountFetchController extends Controller
{
	public function fetchProfileCount(Request $request) {
    	$user = $request->user();

    	$count = $this->getUnreadNotificationCount($user);
    	$count += $this->getInvoiceCount($user);

    	return response()->json([
    		'count' => $count,
    	]);
    }

    public function fetchNotificationCount(Request $request) {
    	$user = $request->user();

    	$count = $this->getUnreadNotificationCount($user);

    	return response()->json([
    		'count' => $count,
    	]);
    }

    public function fetchInvoiceCount(Request $request) {
    	$user = $request->user();

        if (! $request->filled('pending') && ! $request->filled('paid') && ! $request->filled('in-transit')) {
        	$count = $this->getInvoiceCount($user);
        }

        if ($request->filled('pending')) {
            $count = $user->invoices()->active()->where('status', Invoice::STATUS_PENDING)->count();
        }

        if ($request->filled('paid')) {
            $count = $user->invoices()->active()->where('status', Invoice::STATUS_PAID)->count();
        }

        if ($request->filled('in-transit')) {
            $count = $user->invoices()->active()->where('status', Invoice::STATUS_INTRANSIT)->count();
        }

    	return response()->json([
    		'count' => $count,
    	]);
    }

    protected function getUnreadNotificationCount($user) {
    	return $user->unreadNotifications()->count();
    }

    protected function getInvoiceCount($user) {
    	return $user->invoices()->active()->where('status', Invoice::STATUS_INTRANSIT)
            ->orWhere(function($query) {
                return $query->where('status', Invoice::STATUS_PENDING)
                ->whereHas('payment_provider', function($query) {
                    return $query->where('type', PaymentProvider::TYPE_BANK_DEPOSIT);
                });
            })
            ->count();
    }
}
