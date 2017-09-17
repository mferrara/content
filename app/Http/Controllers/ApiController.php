<?php

namespace App\Http\Controllers;

use App\Http\Requests\Request;
use App\Usersearch;

class ApiController extends Controller
{

    public function search()
    {
        if (! Request::has('query')) {
            return json_encode(['error' => 'No search query provided']);
        }

        $query      = urldecode(Request::get('query'));
        $webhookurl = urldecode(Request::get('webhook_url'));
        $usersearch = Usersearch::getSearch($query, 'plain', 'relevance', 'all', $webhookurl);

        $return = [];
        if ($usersearch !== null) {
            $return = ['result' => true];
        }

        return $return;
    }
}
