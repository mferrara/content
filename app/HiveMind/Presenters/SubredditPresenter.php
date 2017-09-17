<?php


namespace HiveMind\Presenters;

use \Laracasts\Presenter\Presenter;
use Carbon\Carbon;

class SubredditPresenter extends Presenter
{

    public function link()
    {
        return link_to('sub/'.$this->name, $this->name);
    }
}
