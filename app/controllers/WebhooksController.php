<?php

class WebhooksController extends \BaseController {

    public function kimono()
    {
        $input = Input::all();

        Log::error(print_r($input));

        return 'woot!';
	}

}