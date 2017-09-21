<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Usersearch;

class ApiController extends Controller
{

    /**
     * @param Request $request
     * @return array|string
     */
    public function search(Request $request)
    {
        if (! $request->has('query')) {
            return json_encode(['error' => 'No search query provided']);
        }

        $query      = urldecode($request->get('query'));
        $webhookurl = urldecode($request->get('webhook_url'));
        $max_words  = null;
        if($request->has('max_words'))
            $max_words  = urldecode($request->get('max_words'));

        $subreddits = 'all';
        if($request->has('subreddit_filter'))
            $subreddits = $request->get('subreddit_filter');

        $options = [
            'max_words'         => $max_words
        ];

        $usersearch = Usersearch::getSearch($query, 'plain', 'relevance', $subreddits, $webhookurl, $options);

        $return = [];
        if ($usersearch !== null) {
            $return = ['result' => true];
        }

        return $return;
    }
}
