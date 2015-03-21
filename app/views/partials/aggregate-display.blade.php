<div class="row">
	<div class="col-md-3">
		<dl class="dl-horizontal">
		@foreach(array_slice($aggregate_data['content_types'], 0, 5) as $name => $count)
			<dt>{{$name}}</dt>
			<dd>{{$count}}</dd>
		@endforeach
			<dt>Self Posts</dt><dd>{{ $aggregate_data['self_posts'] }}</dd>
			<dt>Total Posts</dt><dd>{{ $aggregate_data['total_posts'] }}</dd>
		</dl>
	</div>
	<div class="col-md-3">
		<dl class="dl-horizontal">
			@foreach(array_slice($aggregate_data['base_domains'], 0, 7) as $name => $count)
				<dt>{{ link_to('domain/'.$name, $name) }}</dt>
				<dd>{{ $count }}</dd>
			@endforeach
		</dl>
	</div>
	<div class="col-md-3">
		<dl class="dl-horizontal">
		@foreach(array_slice($aggregate_data['subreddits'], 0, 7) as $name => $count)
			<dt>{{ link_to('sub/'.$name, 'r/'.$name) }}</dt>
			<dd>{{ $count }}</dd>
		@endforeach
		</dl>
	</div>
	<div class="col-md-3">
		<dl class="dl-horizontal">
    		@foreach(array_slice($aggregate_data['authors'], 0, 7) as $name => $count)
    			<dt>{{ link_to('author/'.$name, 'u/'.$name) }}</dt>
    			<dd>{{ $count }}</dd>
    		@endforeach
		</dl>
	</div>
</div>
<div class="row">
<div class="col-md-6">
	<dl class="dl-horizontal">
		<dt>Last Updated</dt><dd>{{ \Carbon\Carbon::createFromTimestamp(strtotime($aggregate_data['updated']))->diffForHumans() }}</dd>
		<dt>Next Update</dt>
		<dd>
			@if($currently_updating == 1)
				<strong>Now!</strong>
			@else
				{{ str_replace('ago', 'from now', \Carbon\Carbon::createFromTimestamp(strtotime($aggregate_data['updated']) + Config::get('hivemind.cache_reddit_requests'))->diffForHumans()) }}
			@endif
		</dd>
	</dl>
</div>
</div>