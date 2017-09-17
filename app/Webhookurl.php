<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Webhookurl extends Model
{

    protected $fillable = [];
    protected $guarded  = ['id', 'created_at'];

    public function usersearches()
    {
        return $this->hasMany(Usersearch::class);
    }
}
