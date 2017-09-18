<?php
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App{
/**
 * Class Article
 *
 * @package App
 * @mixin \Eloquent
 * @property int $id
 * @property string $reddit_id
 * @property string $fullname
 * @property string $type
 * @property string $content_type
 * @property int $subreddit_id
 * @property int|null $character_count
 * @property int|null $word_count
 * @property int|null $paragraph_count
 * @property mixed|null $data
 * @property mixed|null $post_text
 * @property mixed|null $post_text_html
 * @property int $author_id
 * @property int $score
 * @property int $ups
 * @property int $downs
 * @property int $nsfw
 * @property string $permalink
 * @property string $url
 * @property int $basedomain_id
 * @property int $created
 * @property int $is_self
 * @property string $title
 * @property string $slug
 * @property int $num_comments
 * @property int $comments_scraped
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Author $author
 * @property-read \App\Basedomain $basedomain
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Comment[] $comments
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Searchquery[] $searchqueries
 * @property-read \App\Subreddit $subreddit
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Article whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Article whereBasedomainId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Article whereCharacterCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Article whereCommentsScraped($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Article whereContentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Article whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Article whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Article whereData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Article whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Article whereDowns($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Article whereFullname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Article whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Article whereIsSelf($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Article whereNsfw($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Article whereNumComments($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Article whereParagraphCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Article wherePermalink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Article wherePostText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Article wherePostTextHtml($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Article whereRedditId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Article whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Article whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Article whereSubredditId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Article whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Article whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Article whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Article whereUps($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Article whereUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Article whereWordCount($value)
 */
	class Article extends \Eloquent {}
}

namespace App{
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
	class Author extends \Eloquent {}
}

namespace App{
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
	class Basedomain extends \Eloquent {}
}

namespace App{
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
	class Comment extends \Eloquent {}
}

namespace App{
/**
 * Class Searchquery
 *
 * @package App
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property int $currently_updating
 * @property int $scraped
 * @property int $cached
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Article[] $articles
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Usersearch[] $usersearches
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Searchquery whereCached($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Searchquery whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Searchquery whereCurrentlyUpdating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Searchquery whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Searchquery whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Searchquery whereScraped($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Searchquery whereUpdatedAt($value)
 */
	class Searchquery extends \Eloquent {}
}

namespace App{
/**
 * Class Subreddit
 *
 * @package App
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property int $article_count
 * @property int $currently_updating
 * @property int $scraped
 * @property int $cached
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Article[] $articles
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subreddit whereArticleCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subreddit whereCached($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subreddit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subreddit whereCurrentlyUpdating($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subreddit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subreddit whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subreddit whereScraped($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Subreddit whereUpdatedAt($value)
 */
	class Subreddit extends \Eloquent {}
}

namespace App{
/**
 * Class User
 *
 * @package App
 * @mixin \Eloquent
 */
	class User extends \Eloquent {}
}

namespace App{
/**
 * Class Usersearch
 *
 * @package App
 * @mixin \Eloquent
 * @property int $id
 * @property int $searchquery_id
 * @property int|null $subreddit_id
 * @property int|null $webhookurl_id
 * @property int $webhook_sent
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \App\Searchquery $searchquery
 * @property-read \App\Subreddit|null $subreddit
 * @property-read \App\Webhookurl|null $webhookurl
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Usersearch whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Usersearch whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Usersearch whereSearchqueryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Usersearch whereSubredditId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Usersearch whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Usersearch whereWebhookSent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Usersearch whereWebhookurlId($value)
 */
	class Usersearch extends \Eloquent {}
}

namespace App{
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
	class Webhookurl extends \Eloquent {}
}

