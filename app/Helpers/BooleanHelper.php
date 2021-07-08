<?php

namespace App\Helpers;

class BooleanHelper
{
    public static function toBoolean($value) {
        $result = null;

        switch (strtolower($value)) {
            case 'yes':
                $result = true;
                break;
            case 'no':
                $result = false;
                break;
        }

        if ($result === null) {
            $result = in_array($value, [0, '0', false]) ? false : null;
        }

        if ($result === null) {
            $result = in_array($value, [1, '1', true]) ? false : null;
        }

        return $result;
    }
}