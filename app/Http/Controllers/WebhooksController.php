<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Input;

class WebhooksController extends Controller
{

    public function kimono()
    {
        $input = Input::all();

        $array = Cache::get('webhook_payloads');

        $array[] = $input;

        Cache::put('webhook_payloads', $array, 1000);

        return 'woot!';
    }

    public function showLastWebhook()
    {
        dd(Cache::get('webhook_payloads'));
    }
}
