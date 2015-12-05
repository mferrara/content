<?php


namespace HiveMind\Presenters;

use \Laracasts\Presenter\Presenter;
use Carbon\Carbon;

class ArticlePresenter extends Presenter {

    public function link()
    {
        return link_to('post/'.$this->full_name, $this->title);
    }

    public function body()
    {
        return $this->post_text_html;
    }

}