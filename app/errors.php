<?php

App::error(function(\HiveMind\Exceptions\NoContent $exception)
{
    Log::error($exception);

    // If we're on a queue job - release it.
    if(isset($job))
        $job->release();

    if(isset($query) && is_object($query))
    {
        return 'Failure to get content for '.$query->name.'.';
    }

    if(isset($sub) && is_object($sub))
    {
        return 'Failure to get content for '.$sub->name.'.';
    }

    return 'Failure to get content.';
});

