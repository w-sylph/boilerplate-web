<?php

namespace App\Traits;

trait ListTrait
{
    /**
     * Format Array of Objects
     * @param  collection or array of objects
     * @return array        formatted array of objects
     */
    public static function formatList($items) {
        $result = [];

        if (count($items)) {
            foreach ($items as $item) {
                $result[] = static::formatItem($item);
            }
        }

        return $result;
    }

    /**
     * Format Object's Properties to prevent displaying of unnecessary properties
     * @param  object
     * @return object
     */
    public static function formatItem($item) {
    	return [
    		//
    	];
    }
}