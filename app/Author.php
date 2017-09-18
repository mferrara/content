<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use \Laracasts\Presenter\PresentableTrait;

/**
 * Class Author
 *
 * @package App
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property int $article_count
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Article[] $articles
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Author whereArticleCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Author whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Author whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Author whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Author whereUpdatedAt($value)
 */
class Author extends Model
{

    use PresentableTrait;
    protected $presenter = '\HiveMind\Presenters\AuthorPresenter';

    protected $guarded = ['id'];
    protected $fillable = [];

    public static $rules = [

        'name' => ['unique:authors']

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
        DB::table('authors')->where('id', $this->id)->increment('article_count');
    }
}
