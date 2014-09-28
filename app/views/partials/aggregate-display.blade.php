<div class="row">
	<div class="col-md-2">
		<dl class="dl-horizontal">
		@foreach(array_slice($aggregate_data['content_types'], 0, 5) as $name => $count)
			<dt>{{$name}}</dt>
			<dd>{{$count}}</dd>
		@endforeach
		</dl>
	</div>
	<div class="col-md-4">
		<dl class="dl-horizontal">
			<dt>Self Posts</dt><dd>{{ $aggregate_data['self_posts'] }}</dd>
			<dt>Total Posts</dt><dd>{{ $aggregate_data['total_posts'] }}</dd>
			<dt>Last Updated</dt><dd>{{ \Carbon\Carbon::createFromTimestamp(strtotime($aggregate_data['updated']))->diffForHumans() }}</dd>
		</dl>
	</div>
	<div class="col-md-3">
		<dl class="dl-horizontal">
		@foreach(array_slice($aggregate_data['subreddits'], 0, 5) as $name => $count)
			<dt>{{ link_to('sub/'.$name, 'r/'.$name) }}</dt>
			<dd>{{$count}}</dd>
		@endforeach
		</dl>
	</div>
	<div class="col-md-3">
		<dl class="dl-horizontal">
    		@foreach(array_slice($aggregate_data['authors'], 0, 5) as $name => $count)
    			<dt>{{ link_to('author/'.$name, 'u/'.$name) }}</dt>
    			<dd>{{$count}}</dd>
    		@endforeach
		</dl>
	</div>
</div>