<?php

namespace HiveMind\Jobs;

use HiveMind\ArticleProcessor;
use Illuminate\Queue\Jobs\Job;
use Subreddit;

class ProcessArticles {

	public function processSubreddit(Job $job, $data)
	{
        $error = false;
        try{
            $subreddit_id = $data['subreddit_id'];

            $subreddit = Subreddit::find($subreddit_id);
            \Log::error('Starting processing for r/'.$subreddit->name);
            $processed = ArticleProcessor::fire($subreddit);

            if($processed)
            {
                $subreddit->cached 				= 1;
                $subreddit->currently_updating 	= 0;
                $subreddit->save();
                \Log::error('Finished processing for '.$subreddit->name);
            }
            else
            {
                $error = true;
                $job->release();
            }
        }
        catch(\Exception $e)
        {
            \Log::error('Yo, something broke. ProcessArticles@processSubreddit - '.$subreddit->name.' try: '.$job->attempts());
            \Log::error($e->getMessage());
            \Log::error($e->getTraceAsString());

            $subreddit->cached 				= 0;
            $subreddit->save();

            $error = true;

            $job->release();
        }

        if($error === false)
        {
            $job->delete();
        }
	}

    public function processSearchquery(Job $job, $data)
    {
        $error = false;
        try{
            $searchquery_id = $data['searchquery_id'];

            $searchquery = \Searchquery::find($searchquery_id);
            \Log::error('Starting processing for '.$searchquery->name.' try: '.$job->attempts());
            $processed = ArticleProcessor::fire($searchquery);

            if($processed)
            {
                $searchquery->cached              = 1;
                $searchquery->currently_updating  = 0;
                $searchquery->save();
                \Log::error('Finished processing for '.$searchquery->name);
            }
            else
            {
                $error = true;
                $job->release();
            }
        }
        catch(\Exception $e)
        {
            \Log::error('Yo, something broke. ProcessArticles@processSearchquery - '.$searchquery->name);
            \Log::error($e->getMessage());
            \Log::error($e->getTraceAsString());

            $searchquery->cached              = 0;
            $searchquery->save();

            $error = true;

            $job->release();
        }

        if($error === false)
        {
            $job->delete();
        }
    }

} 