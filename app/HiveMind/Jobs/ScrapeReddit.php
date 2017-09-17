<?php

namespace HiveMind\Jobs;

use Aws\CloudFront\Exception\Exception;
use GuzzleHttp\Exception\ServerException;
use HiveMind\Reddit;
use HiveMind\ArticleProcessor;
use Config;
use Subreddit;
use Searchquery;
use Illuminate\Queue\Jobs\Job;

class ScrapeReddit
{

    public function subreddit(Job $job, $data)
    {
        $subreddit      = Subreddit::find($data['subreddit_id']);
        $sort           = $data['sort_type'];
        $time           = $data['time'];
        $page_depth     = config('hivemind.page_depth');

        $scraper = new Reddit();

        foreach ($time as $time_frame) {
            foreach ($sort as $sort_method) {
                $scraper->Subreddit($subreddit->name, $page_depth, $sort_method, $time_frame);
            }
        }

        $subreddit->scraped = 1;
        $subreddit->save();

        // Queue up the processing of the articles
        // This is after the model is saved because we're triggering the clearing
        // of this cache by updating of the model
        $subreddit->queueArticleProcessing();

        $job->delete();
    }

    public function search(Job $job, $data)
    {
        $query          = Searchquery::find($data['searchquery_id']);
        $sort_method    = $data['sort_type'];
        $subs           = $data['subreddits'];
        $search_type    = $data['search_type'];
        $time           = $data['time'];
        $page_depth     = config('hivemind.page_depth');

        $scraper = new Reddit();

        if (is_array($time)) {
            foreach ($time as $time_frame) {
                $scraper->Search($query, $page_depth, $search_type, $sort_method, $time_frame, $subs);
            }
        } else {
            $scraper->Search($query, $page_depth, $search_type, $sort_method, $time, $subs);
        }

        // Save the model
        $query->scraped = 1;
        $query->save();

        // Queue up the processing of the articles
        // This is after the model is saved because we're triggering the clearing
        // of this cache by updating of the model
        $query->queueArticleProcessing();

        // Send the webhooks associated with this Searchquery
        $query->queueWebhooks();

        $job->delete();
    }
}
