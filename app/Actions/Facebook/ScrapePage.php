<?php

namespace App\Actions\Facebook;

use GuzzleHttp\Client;

class ScrapePage
{
    /**
     * Scrape API: https://developers.facebook.com/tools/explorer/
     * @param  string $url URL to scrape OG graph
     */
    public function execute($url) {
        $result = false;

        if (config('facebook.access-token')) {
            // Create a client with a base URI
            $client = new Client;
            $response = $client->request('POST', 'https://graph.facebook.com/v7.0', [
                'query' => [
                    'id' => $url,
                    'scrape' => true,
                    'access_token' => config('facebook.access-token')
                ]
            ]);

            $result = $response->getBody()->getContents();
        }

        return $result;
    }
}