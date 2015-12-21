<?php

namespace HiveMind;

use GuzzleHttp\Exception\ServerException;

class Scraper {

	public function GET($url)
	{
		// Check cache for this search
		$key = 'reddit_search_'.md5($url);
		if(\Cache::has($key))
			return \Cache::get($key);

        $browser = new \GuzzleHttp\Client();
        // Fetch results (make up to 3 attempts)
        try {
            $result = $browser->get($url);
        }
        catch(ServerException $e)
        {
            // Did we get a 503? Let's wait a few seconds and try again
            if($e->getResponse()->getStatusCode() == 503)
            {
                \Log::error('503 on - '.$url);
                \Log::error('Trying again...');
                sleep(3);
                try{
                    $result = $browser->get($url);
                }
                catch(ServerException $e)
                {
                    // Did we get a 503? Let's wait a few seconds and try again
                    if($e->getResponse()->getStatusCode() == 503)
                    {
                        \Log::error('2nd 503 on - '.$url);
                        \Log::error('Trying again...');
                        sleep(3);
                        $result = $browser->get($url);
                    }
                }
            }
        }

        if(isset($result))
		    \Cache::add($key, $result->getBody(), 30);

        $body = false;
        if(isset($result))
        {
            $body = $result->getBody();
        }

		return $body;
	}

} 