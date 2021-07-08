<?php

namespace App\Helpers;

use Faker\Factory as Faker;

use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Avatar;
use Storage;
use Intervention\Image\Facades\Image;

use App\Helpers\ListHelper;

class GeneratorHelper
{
    public static function randomFile($dir = 'tmp', $required = 5, $extension = 'jpg') {

        $files = Storage::files('public/' . $dir);

        if (count($files) > $required) {
            $files = Arr::where($files, function($file) {
                return strpos($file, '.jpg') !== false;
            });
            $path = Arr::random($files);
            $path = str_replace('public/', '', $path);
        } else {
            $path = $dir . '/' . Str::random(40) . '.jpg';
            Image::canvas(300, 300, '#dddddd')->save('public/storage/' . $path);
        }

        return $path;
    }

    public static function generateMobileNumber() {
        return '9' . (string) rand(10, 99) . (string) rand(100, 999) . (string) rand(1000, 9999);
    }

    public static function generateTelephoneNumber() {
        return (string) rand(1000, 9999) . (string) rand(1000, 9999);
    }

    public static function generateBirthday($age = null) {
        if (!$age) {
            $age = rand(14, 50);
        }
        
        return now()->subYears($age)->subDays(rand(1, 365))->toDateString();
    }

    public static function randomGender() {
        return Arr::random(ListHelper::getGenderList());
    }
}