<?php

namespace App\Traits;

use App\Models\Mutators\Address;

trait AddressTrait
{
    public function addresses() {
        return $this->morphMany(Address::class, 'parent');
    }

    public function getAddressList() {
    	return $this->addresses()->orderBy('default', 'desc')->get()->map(function($item) {
    		$item->address = $item->renderAddress();
    		return $item;
    	});
    }
}