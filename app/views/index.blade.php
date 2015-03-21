@extends('layouts.default')

@section('content')

<div class="row">
	<div class="col-md-12">
		<div class="text-center">
			<p class="lead">Search for stuff</p>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-4 col-md-offset-2">
		<div class="text-center">
			<p>By keyword</p>
			{{ Form::open(['url' => '/search', 'method' => 'get']) }}
			<div class="input-group">
			  <input id="keyword-search" name="q" type="search" class="form-control" placeholder="Lakers">
			  <span class="input-group-btn">
				  <button id="keyword-search-submit" class="btn btn-default" type="button">Go!</button>
				</span>
			</div>
			{{ Form::close() }}
		</div>
	</div>
	<div class="col-md-4">
		<div class="text-center">
			<p>By subreddit</p>
			{{ Form::open(['url' => '/', 'method' => 'get']) }}
			<div class="input-group">
              <span class="input-group-addon">r/</span>
              <input id="subreddit-search" type="search" class="form-control" placeholder="worldnews">
              <span class="input-group-btn">
				  <button id="subreddit-search-submit" class="btn btn-default" type="button">Go!</button>
				</span>
            </div>
			{{ Form::close() }}
		</div>
	</div>
</div>
<br/><br/>
<div class="row">
	<div class="col-md-4">
		<div class="text-center">
			<p class="lead">Recent searches</p>
			<ul class="list-unstyled">
				@foreach($searches as $search)
					<li>{{ link_to('search?q='.urlencode($search->name),$search->name) }}</li>
				@endforeach
			</ul>
		</div>
	</div>
	<div class="col-md-4">
		<div class="text-center">
			<p class="lead">Random subreddits</p>
			<ul class="list-unstyled">
				@foreach($subreddits as $subreddit)
					<li>{{ link_to('sub/'.$subreddit->name, 'r/'.$subreddit->name) }}</li>
				@endforeach
			</ul>
		</div>
	</div>
	<div class="col-md-4">
		<div class="text-center">
			<p class="lead">Random authors</p>
			<ul class="list-unstyled">
				@foreach($authors as $author)
					<li>{{ link_to('author/'.$author->name, 'u/'.$author->name) }}</li>
				@endforeach
			</ul>
		</div>
	</div>
</div>
<br/><br/>
<div class="row">
	<div class="col-md-4">&nbsp;</div>
	<div class="col-md-5 text-center">
		<dl class="dl-horizontal">
			<dt>Posts indexed</dt>
			<dd>{{ number_format($total_articles) }}</dd>
			<dt>Subreddits</dt>
			<dd>{{ number_format($total_subreddits) }}</dd>
			<dt>Post authors</dt>
			<dd>{{ number_format($total_authors) }}</dd>
			<dt>Linked domains</dt>
			<dd>{{ number_format($total_domains) }}</dd>
			<dt>&nbsp;</dt><dd>&nbsp;</dd>
			<dt>{{ link_to('searches', 'Keywords searched') }}</dt>
			<dd>{{ number_format($total_queries) }}</dd>
			<dt>{{ link_to('subreddits', 'Subreddits searched') }}</dt>
			<dd>{{ number_format($scraped_subreddits) }}</dd>
			<dt>Searches pending</dt>
			<dd>{{ number_format($pending_searches) }}</dd>
		</dl>
	</div>
	<div class="col-md-1">&nbsp;</div>
</div>


@stop