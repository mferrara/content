<?php

class Comment extends \Eloquent
{

    protected $guarded      = ['id'];
    protected $fillable     = [];
    protected $softDelete   = true;

    public static $rules = [

        'fullname' => ['unqiue']

    ];

    public function article()
    {
        return $this->belongsTo('Article');
    }
}
