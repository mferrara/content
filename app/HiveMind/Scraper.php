<?php

namespace HiveMind;

class Scraper {

	public function GET($url)
	{
		// Check cache for this search
		$key = 'reddit_search_'.md5($url);
		if(\Cache::has($key))
			return \Cache::get($key);

		$browser = new \GuzzleHttp\Client();
		$result = $browser->get($url);

		\Cache::add($key, $result->getBody(), 30);

		return $result->getBody();
	}

} 