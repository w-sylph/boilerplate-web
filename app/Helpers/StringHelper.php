<?php

namespace App\Helpers;

class StringHelper
{
    public static function titleCase($value) {
        $word_splitters = array(' ', '-', "O'", "L'", "D'", 'St.', 'Mc');
        $lowercase_exceptions = array('the', 'van', 'den', 'von', 'und', 'der', 'de', 'da', 'of', 'and', "l'", "d'");
        $uppercase_exceptions = array('III', 'IV', 'VI', 'VII', 'VIII', 'IX');
     
        $string = strtolower($value);
        foreach ($word_splitters as $delimiter)
        { 
            $words = explode($delimiter, $string); 
            $newwords = array(); 
            foreach ($words as $word)
            { 
                if (in_array(strtoupper($word), $uppercase_exceptions))
                    $word = strtoupper($word);
                else
                if (!in_array($word, $lowercase_exceptions))
                    $word = ucfirst($word); 
     
                $newwords[] = $word;
            }
     
            if (in_array(strtolower($delimiter), $lowercase_exceptions))
                $delimiter = strtolower($delimiter);
     
            $string = join($delimiter, $newwords); 
        } 
        return $string; 
    }

    public static function getShortClassName($object){
        $result = class_basename($object);
        return preg_replace('/(?<! )(?<!^)[A-Z]/',' $0', $result);
    }

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