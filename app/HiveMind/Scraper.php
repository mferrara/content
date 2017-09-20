<?php

namespace HiveMind;

use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Client;
use HiveMind\Exceptions\NoContentException;

class Scraper
{

    public function request($url)
    {
        // Check cache for this request
        $key = 'request_'.md5($url);
        if (\Cache::has($key)) {

            \Log::debug('Request returned from cache - '.$url);
            $cached_value = \Cache::get($key);
            $cached_value = json_encode($cached_value);

            return $cached_value;
        }

        \Log::debug('Requesting URL: '.$url);

        $result     = false;
        $browser    = new Client();
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

                        try{
                            $result = $browser->get($url, ['headers' => ['User-Agent' => config('hivemind.useragent')]]);
                        }
                        catch (ServerException $e)
                        {
                            throw $e;
                        }

                    }
                }
            }
            else
                throw $e;
        }

        $body = false;
        // If the $result var is not false, meaning the $browser->get() returned without throwing a ServerException
        if ($result !== false) {

            // Get the body of the response
            $body = $result->getBody();

            // Cache the result if there was one
            if (mb_strlen($body) > 0) {
                \Cache::add($key, $body, 30);
            }
            else
            {
                // No content was returned for this $url so let's throw an exception
                throw new NoContentException('No content on request for URL: '.$url);
            }
        }
        else
        {
            throw new \Exception('No response on request. URL: '.$url);
        }
        
        return $body;
    }
}
