@extends('layouts.default')

@section('content')

{!! link_to('/', 'Home', ['class' => 'btn btn-primary']) !!}

<h1>Subreddits searched <small>{!! count($subreddits) !!}</small></h1>
<hr/>

<dl class="dl-horizontal">
	@foreach($subreddits as $subreddit)
		<dt>{!! link_to('sub/'.$subreddit->name, $subreddit->name) !!}</dt>
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

<div class="text-center">{!! $subreddits->links() !!}</div>

@stop