<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use \Laracasts\Presenter\PresentableTrait;

/**
 * Class Basedomain
 *
 * @package App
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property int $article_count
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Article[] $articles
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Basedomain whereArticleCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Basedomain whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Basedomain whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Basedomain whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Basedomain whereUpdatedAt($value)
 */
class Basedomain extends Model
{

    use PresentableTrait;

    protected $presenter = '\HiveMind\Presenters\BasedomainPresenter';

    protected $guarded = ['id'];
    protected $fillable = [];

    public static $rules    = [

        'name'  => ['unique:basedomains']

    ];

    public function articles()
    {
        return $this->hasMany(\App\Article::class);
    }

    public static function findOrCreate($name)
    {
        $model = self::where('name', $name)->first();

        if ($model === null) {
            $val = Validator::make(['name' => $name], self::$rules);
            if ($val->passes()) {
                $model = self::create(['name' => $name]);
            } else {
                dd(get_called_class().' not valid on insert');
            }
        }

        return $model;
    }

    public function incrementArticleCount()
    {
        DB::table('basedomains')->where('id', $this->id)->increment('article_count');
    }
}
