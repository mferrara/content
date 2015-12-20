<?php

namespace HiveMind\Jobs;

use HiveMind\ArticleProcessor;
use Illuminate\Queue\Jobs\Job;
use Subreddit;

class ProcessArticles {

	public function processSubreddit(Job $job, $data)
	{
        try{
            $subreddit_id = $data['subreddit_id'];

            $subreddit = Subreddit::find($subreddit_id);
            ArticleProcessor::fire($subreddit);

            $subreddit->cached 				= 1;
            $subreddit->currently_updating 	= 0;
            $subreddit->save();

            $job->delete();
        }
        catch(\Exception $e)
        {
            \Log::error('Yo, something broke. ProcessArticles@processSubreddit - '.$subreddit->name);
            \Log::error($e->getMessage());
            \Log::error($e->getTraceAsString());

            $subreddit->cached 				= 0;
            $subreddit->save();

            $job->release();
        }
	}

    public function processSearchquery(Job $job, $data)
    {
        try{
            $searchquery_id = $data['searchquery_id'];

            $searchquery = \Searchquery::find($searchquery_id);
            ArticleProcessor::fire($searchquery);

            $searchquery->cached              = 1;
            $searchquery->currently_updating  = 0;
            $searchquery->save();

            $job->delete();
        }
        catch(\Exception $e)
        {
            \Log::error('Yo, something broke. ProcessArticles@processSearchquery - '.$searchquery->name);
            \Log::error($e->getMessage());
            \Log::error($e->getTraceAsString());

            $searchquery->cached              = 0;
            $searchquery->save();

            $job->release();
        }
    }

} 