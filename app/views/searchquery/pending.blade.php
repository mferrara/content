@extends('layouts.default')

@section('content')

    {{ link_to('/', 'Home', ['class' => 'btn btn-primary']) }}

    <div class="row">
        <div class="col-md-6 col-xs-12 text-center">
            <h1>Pending Searches <small>{{ count($pending_queries) }}</small></h1>

            <dl class="dl-horizontal">
                @foreach($pending_queries as $search)
                    <dt>{{ link_to('search?q='.urlencode($search->name), $search->name) }}</dt>
                    <dd>
                        @if($search->scraped == 0)
                            <span class="label label-danger">Pending</span>
                        @elseif($search->currently_updating == 1)
                            <span class="label label-warning">Updating</span>
                        @elseif($search->isStale())
                            <span class="label label-default">Stale</span>
                        @else
                            <span class="label label-success">Complete</span>
                        @endif
                    </dd>
                @endforeach
            </dl>
        </div>
        <div class="col-md-6 col-xs-12 text-center">
            <h1>Pending Subreddits <small>{{ count($pending_subreddits) }}</small></h1>

            <dl class="dl-horizontal">
                @foreach($pending_subreddits as $subreddit)
                    <dt>{{ link_to('search?q='.urlencode($subreddit->name), $subreddit->name) }}</dt>
                    <dd>
                        @if($subreddit->scraped == 0)
                            <span class="label label-danger">Pending</span>
                        @elseif($subreddit->currently_updating == 1)
                            <span class="label label-warning">Updating</span>
                        @elseif($subreddit->isStale())
                            <span class="label label-default">Stale</span>
                        @else
                            <span class="label label-success">Complete</span>
                        @endif
                    </dd>
                @endforeach
            </dl>
        </div>
    </div>

@stop