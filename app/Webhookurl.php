<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Webhookurl
 *
 * @package App
 * @mixin \Eloquent
 * @property int $id
 * @property string $url
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Usersearch[] $usersearches
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Webhookurl whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Webhookurl whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Webhookurl whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Webhookurl whereUrl($value)
 */
class Webhookurl extends Model
{

    protected $fillable = [];
    protected $guarded  = ['id', 'created_at'];

    public function usersearches()
    {
        return $this->hasMany(Usersearch::class);
    }
}
