@extends('layouts.default')

@section('content')

<div class="row">
	<div class="col-md-12">
		<div class="text-center">
			<p class="lead">Search for shit</p>
			{{ Form::open(['url' => '/search', 'method' => 'get']) }}
			{{ Form::input('search', 'q') }}
			{{ Form::submit() }}
			{{ Form::close() }}
		</div>
	</div>
</div>
<br/><br/>
<div class="row">
	<div class="col-md-4">
		<div class="text-center">
			<p class="lead">Recent retarded searches</p>
			<ul class="list-unstyled">
				@foreach($searches as $search)
					<li>{{ link_to('search?q='.urlencode($search->name),$search->name) }}</li>
				@endforeach
			</ul>
		</div>
	</div>
	<div class="col-md-4">
		<div class="text-center">
			<p class="lead">Random fucking subreddits</p>
			<ul class="list-unstyled">
				@foreach($subreddits as $subreddit)
					<li>{{ link_to('sub/'.$subreddit->name, 'r/'.$subreddit->name) }}</li>
				@endforeach
			</ul>
		</div>
	</div>
	<div class="col-md-4">
		<div class="text-center">
			<p class="lead">Random stupid people</p>
			<ul class="list-unstyled">
				@foreach($authors as $author)
					<li>{{ link_to('author/'.$author->name, 'u/'.$author->name) }}</li>
				@endforeach
			</ul>
		</div>
	</div>
</div>



@stop