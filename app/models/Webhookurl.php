<?php

class Webhookurl extends \Eloquent {

    protected $fillable = [];
    protected $guarded  = ['id', 'created_at'];

    public function usersearches()
    {
        return $this->hasMany(Usersearch::class);
    }

}