<?php

namespace HiveMind;


class GoogleTrends {

	public static function getTrends()
	{
		$return = [];

		// API 1 - Top Charts (Updated Monthly)
		$urls[] = "https://www.kimonolabs.com/api/5zz6hkqg?apikey=kfUE38PA7D0QmPetnOI2LjzAR63kjNBv";

        // API 2 - Hot Trends (Updated Daily)
        $urls[] = "https://www.kimonolabs.com/api/2f33sbd0?apikey=kfUE38PA7D0QmPetnOI2LjzAR63kjNBv";

        // API 3 - Google news top searches
        $urls[] = "https://www.kimonolabs.com/api/2v9hkv1e?apikey=kfUE38PA7D0QmPetnOI2LjzAR63kjNBv";

        foreach($urls as $url)
        {
            $response = file_get_contents($url);
            $results = json_decode($response, TRUE);

            if($results['lastrunstatus'] !== 'success')
            {
                unset($api);
                $api['api'] = $results['name'];

                // API Failed
            }

            if(isset($results['results']['keywords']))
            {
                $keywords = $results['results']['keywords'];

                foreach($keywords as $keyword)
                {
                    $return[] = $keyword['keyword']['text'];
                }
            }
        }

		return $return;
	}

} 