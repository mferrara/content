<?php


namespace HiveMind\Presenters;

use \Laracasts\Presenter\Presenter;
use Carbon\Carbon;

class AuthorPresenter extends Presenter {

    public function link()
    {
        return link_to('author/'.$this->name, $this->name);
    }

}