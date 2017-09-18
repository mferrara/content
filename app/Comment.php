<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


/**
 * Class Comment
 *
 * @package App
 * @mixin \Eloquent
 * @property int $id
 * @property int $subreddit_id
 * @property int $author_id
 * @property int $word_count
 * @property int $paragraph_count
 * @property mixed|null $body
 * @property mixed|null $body_html
 * @property string $name
 * @property string $reddit_id
 * @property int $ups
 * @property int $downs
 * @property int $article_id
 * @property int $gilded
 * @property int $created
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Article $article
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereArticleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereBodyHtml($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereDowns($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereGilded($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereParagraphCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereRedditId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereSubredditId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereUps($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Comment whereWordCount($value)
 */
class Comment extends Model
{

    protected $guarded      = ['id'];
    protected $fillable     = [];
    protected $softDelete   = true;

    public static $rules = [

        'fullname' => ['unqiue']

    ];

    public function article()
    {
        return $this->belongsTo(\App\Article::class);
    }
}
