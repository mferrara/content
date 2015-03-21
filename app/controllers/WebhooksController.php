<?php

class WebhooksController extends \BaseController {

    public function kimono()
    {
        $input = Input::all();

        Cache::put('last_webhook_payload', $input, 10);

        return 'woot!';
	}

}