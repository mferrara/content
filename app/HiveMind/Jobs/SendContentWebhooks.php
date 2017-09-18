<?php

namespace HiveMind\Jobs;

use Illuminate\Queue\Jobs\Job;
use App\Searchquery;

class SendContentWebhooks
{

    public function send(Job $job, $data)
    {
        \Log::debug('Starting webhook send Job');
        $error = false;
        try {
            // Get the Searchquery
            $searchquery_id     = $data['searchquery_id'];
            $searchquery        = Searchquery::find($searchquery_id);

            // Are there any webhooks that need to be sent for this query?
            $usersearches       = $searchquery->usersearches()
                ->where('webhookurl_id', '>', 0)
                ->where('webhook_sent', 0)
                ->get();
            \Log::debug('Sending '.$usersearches->count().' webhooks.');
            // If so, send them
            if ($usersearches->count() > 0) {
                foreach ($usersearches as $usersearch) {
                    \Log::debug('Sending webhook for '.$usersearch->searchquery->name);
                    $usersearch->sendWebhook();
                }
            }
        } catch (\Exception $e) {
            \Log::error('Yo, something broke. SendContentWebhooks@send');
            \Log::error($e->getMessage());
            \Log::error($e->getTraceAsString());

            $error = true;

            $job->release();
        }

        if ($error === false) {
            $job->delete();
        }
    }
}
