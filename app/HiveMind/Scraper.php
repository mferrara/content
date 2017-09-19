<?php

namespace HiveMind;

use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Client;
use HiveMind\Exceptions\NoContentException;

class Scraper
{

    public function GET($url, $source_name)
    {
        // Check cache for this search
        $key = $source_name.'_search_'.md5($url);
        if (\Cache::has($key)) {
            return \Cache::get($key);
        }

        $browser = new Client();
        // Fetch results (make up to 3 attempts)
        try {
            $result = $browser->get($url, ['headers' => ['User-Agent' => config('hivemind.useragent')]]);
        } catch (ServerException $e) {
            // Did we get a 503? Let's wait a few seconds and try again
            if ($e->getResponse()->getStatusCode() == 503) {
                \Log::error('503 on - '.$url);
                \Log::error('Trying again...');
                sleep(\config('hivemind.503_sleep'));
                try {
                    $result = $browser->get($url, ['headers' => ['User-Agent' => config('hivemind.useragent')]]);
                } catch (ServerException $e) {
                    // Did we get a 503? Let's wait a few seconds and try again
                    if ($e->getResponse()->getStatusCode() == 503) {
                        \Log::error('2nd 503 on - '.$url);
                        \Log::error('Trying again...');
                        sleep(\config('hivemind.503_sleep'));
                        $result = $browser->get($url, ['headers' => ['User-Agent' => config('hivemind.useragent')]]);
                    }
                }
            }
        }

        $body = false;
        if (isset($result)) {
            $body = $result->getBody();

            // Cache the result if there was one
            if (mb_strlen($body)) {
                \Cache::add($key, $body, 30);
            }
            else
            {
                // No content was returned for this $url so let's throw an exception
                throw new NoContentException('No content on request for URL: '.$url);
            }
        }

        return $body;
    }
}
