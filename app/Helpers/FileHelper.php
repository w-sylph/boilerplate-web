<?php

namespace App\Helpers;

use Illuminate\Validation\ValidationException;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class FileHelper
{
    /* Get file from url  */
    public static function downloadExternalImage($url) {
        try {
            $client = new Client();
            $response = $client->get($url);
            $file = $response->getBody()->getContents();
        } catch (RequestException $e) {
            throw ValidationException::withMessages([
                'image_url' => 'Unable to download image'
            ]);
        }

        return $file;
    }
}