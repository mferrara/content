@extends('layouts.default')

@section('content')

{!! link_to('/', 'Home', ['class' => 'btn btn-primary']) !!}

<div class="row">
    <div class="col-md-6">
        <h1>Search Results <small>{!! $usersearch->searchquery->name !!}</small></h1>
    </div>
    <div class="col-md-6 text-right">
        @if( app('request')->input('content_type') || app('request')->input('domain') || app('request')->input('subreddit') || app('request')->input('author'))

            @if(app('request')->input('domain'))
                <a href="/domain/{!! app('request')->input('domain') !!}" class="btn btn-link">View all results from {!! app('request')->input('domain') !!}</a>
            @endif

            @if(app('request')->input('subreddit'))
                <a href="/sub/{!! app('request')->input('subreddit') !!}" class="btn btn-link">View all results from r/{!! app('request')->input('subreddit') !!}</a>
            @endif

            @if(app('request')->input('author'))
                <a href="/author/{!! app('request')->input('author') !!}" class="btn btn-link">View all results from u/{!! app('request')->input('author') !!}</a>
            @endif

            <a href="/search?q={!! urlencode($usersearch->searchquery->name) !!}" class="btn btn-sm btn-default">Clear Filters</a>

        @endif
    </div>
</div>
<hr />

@if($aggregate_data != false)
	@include('partials.aggregate-display')
@else
	<div class="row">
    	<div class="col-md-2">
    		<p class="text-center">Processing...</p>
    	</div>
    	<div class="col-md-4">
    		<p class="text-center">Processing...</p>
    	</div>
    	<div class="col-md-3">
    		<p class="text-center">Processing...</p>
    	</div>
    	<div class="col-md-3">
    		<p class="text-center">Processing...</p>
    	</div>
    </div>
@endif

@if($currently_updating == true)
	<p class="lead alert alert-warning text-center">Results being collected/updated @if($articles != false)...here's what we've got so far @else ...please check back in a few minutes @endif</p>
@endif

@include('partials.articles-table')

@stop