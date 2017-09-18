<?php

namespace HiveMind\Jobs;

use HiveMind\ArticleProcessor;
use Illuminate\Queue\Jobs\Job;
use App\Subreddit;
use App\Searchquery;

class ProcessArticles
{

    public function processSubreddit(Job $job, $data)
    {
        $error = false;
        try {
            $subreddit_id   = $data['subreddit_id'];
            $subreddit      = Subreddit::find($subreddit_id);
            $no_keywords    = false;
            if ($job->attempts() > 1) {
                $no_keywords = true;
            }
            $processed = ArticleProcessor::fire($subreddit, $no_keywords);

            if ($processed) {
                $subreddit->cached              = 1;
                $subreddit->currently_updating  = 0;
                $subreddit->save();
            } else {
                $error = true;
                $job->release();
            }
        } catch (\Exception $e) {
            \Log::error('Yo, something broke. ProcessArticles@processSubreddit - '.$subreddit->name.' try: '.$job->attempts());
            \Log::error($e->getMessage());
            \Log::error($e->getTraceAsString());

            $subreddit->cached              = 0;
            $subreddit->save();

            $error = true;

            $job->release();
        }

        if ($error === false) {
            $job->delete();
        }
    }

    public function processSearchquery(Job $job, $data)
    {
        $error = false;
        try {
            $searchquery_id = $data['searchquery_id'];
            $searchquery    = Searchquery::find($searchquery_id);
            $no_keywords    = false;
            if ($job->attempts() > 1) {
                $no_keywords = true;
            }
            $processed = ArticleProcessor::fire($searchquery, $no_keywords);

            if ($processed) {
                $searchquery->cached              = 1;
                $searchquery->currently_updating  = 0;
                $searchquery->save();
            } else {
                $error = true;
                $job->release();
            }
        } catch (\Exception $e) {
            \Log::error('Yo, something broke. ProcessArticles@processSearchquery - '.$searchquery->name);
            \Log::error($e->getMessage());
            \Log::error($e->getTraceAsString());

            $searchquery->cached              = 0;
            $searchquery->save();

            $error = true;

            $job->release();
        }

        if ($error === false) {
            $job->delete();
        }
    }
}
