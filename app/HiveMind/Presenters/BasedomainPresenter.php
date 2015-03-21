<?php


namespace HiveMind\Presenters;

use \Laracasts\Presenter\Presenter;
use Carbon\Carbon;

class BasedomainPresenter extends Presenter {

    public function link()
    {
        return link_to('domain/'.$this->name, $this->name);
    }

}