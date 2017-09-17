<?php


namespace HiveMind\Presenters;

use \Laracasts\Presenter\Presenter;
use Carbon\Carbon;

class SearchqueryPresenter extends Presenter
{

    public function link()
    {
        return link_to('search?q='.urlencode($this->name), $this->name);
    }
}
