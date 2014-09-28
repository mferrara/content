<?php

namespace HiveMind;


class GoogleTrends {

	public static function getTrends()
	{
		$return = [];

		// API 1
		$request = "https://www.kimonolabs.com/api/7klgm2uw?apikey=kfUE38PA7D0QmPetnOI2LjzAR63kjNBv";
		$response = file_get_contents($request);
		$results = json_decode($response, TRUE);

		if(isset($results['results']['collection1']))
		{
			$results = $results['results']['collection1'];

			$return = [];
			foreach($results as $key => $array)
			{
				if(mb_strlen(str_replace('.', '', $array['keyword'])) > 2)
					$return[] = $array['keyword'];
			}
		}

		// API 2
		$request = "https://www.kimonolabs.com/api/8i72e5y2?apikey=kfUE38PA7D0QmPetnOI2LjzAR63kjNBv";
		$response = file_get_contents($request);
		$results = json_decode($response, TRUE);

		if(isset($results['results']['collection1']))
		{
			$results = $results['results']['collection1'];

			foreach($results as $key => $array)
			{
				foreach($array as $key => $data)
				{
					$return[] = $data['text'];
				}
			}
		}

		return $return;
	}

} 