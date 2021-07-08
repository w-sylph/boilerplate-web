<?php

namespace App\Helpers;

class NumberHelper
{
    public static function toMoney($value, $prefix = '₱') {
    	$prefix = $prefix ? $prefix . ' ' : null;
    	return $prefix . number_format($value, 2, '.', ',');
    }

    public function formatPrice($value) {
        return static::toMoney($value);
    }

    public static function computeDiscount($value, $discount, $type = null) {
    	$result = ($value * $discount) / 100;
        
    	switch (strtolower($type)) {
    		case 'add':
    				$result = $value + $result;
    			break;
    		
    		case 'subtract':
    				$result = $value - $result;
    			break;
    	}

    	return $result;
    }
}