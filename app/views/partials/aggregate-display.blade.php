<div class="row">
	<div class="col-md-3">
        <h5 class="text-center">Post breakdown</h5>
		<dl class="dl-horizontal">
		@foreach(array_slice($aggregate_data['content_types'], 0, 5) as $name => $count)
			@if(isset($usersearch))
                <dt><a href="/search?q={{ urlencode($usersearch->searchquery->name) }}&content_type={{$name}}">{{$name}}</a></dt>
            @else
                <dt>{{$name}}</dt>
            @endif

			<dd>{{$count}}</dd>
		@endforeach
			<dt>Self Posts</dt><dd>{{ $aggregate_data['self_posts'] }}</dd>
			<dt>Total Posts</dt><dd>{{ $aggregate_data['total_posts'] }}</dd>
		</dl>
	</div>
	<div class="col-md-3">
        <h5 class="text-center">Top Linked Domains</h5>
		<dl class="dl-horizontal">
			@foreach(array_slice($aggregate_data['base_domains'], 0, 7) as $name => $count)
                @if(isset($usersearch))
                    <dt>{{ link_to('search?q='.urlencode($usersearch->searchquery->name).'&domain='.$name, $name) }}</dt>
                @else
                    <dt>{{ link_to('/domain/'.$name, $name) }}</dt>
                @endif
				<dd>{{ $count }}</dd>
			@endforeach
		</dl>
	</div>
	<div class="col-md-3">
        <h5 class="text-center">Top Subreddits</h5>
		<dl class="dl-horizontal">
		@foreach(array_slice($aggregate_data['subreddits'], 0, 7) as $name => $count)
            @if(isset($usersearch))
                <dt>{{ link_to('search?q='.urlencode($usersearch->searchquery->name).'&subreddit='.$name, 'r/'.$name) }}</dt>
            @else
                <dt>{{ link_to('/sub/'.$name, 'r/'.$name) }}</dt>
            @endif
			<dd>{{ $count }}</dd>
		@endforeach
		</dl>
	</div>
	<div class="col-md-3">
        <h5 class="text-center">Top Authors</h5>
		<dl class="dl-horizontal">
    		@foreach(array_slice($aggregate_data['authors'], 0, 7) as $name => $count)
                @if(isset($usersearch))
                    <dt>{{ link_to('search?q='.urlencode($usersearch->searchquery->name).'&author='.$name, 'u/'.$name) }}</dt>
                @else
                    <dt>{{ link_to('/author/'.$name, 'u/'.$name) }}</dt>
                @endif
    			<dd>{{ $count }}</dd>
    		@endforeach
		</dl>
	</div>
</div>
<div class="row">
    <div class="col-md-12">
        @if(isset($aggregate_data['keywords']) && count($aggregate_data['keywords']) > 0)
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Keywords</h3>
                </div>
                <div class="panel-body">
                    {{ var_dump($aggregate_data['keywords']) }}
                </div>
            </div>
        @endif
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
                @if(isset($subreddit))
				    {{ str_replace('ago', 'from now', \Carbon\Carbon::createFromTimestamp(strtotime($aggregate_data['updated']) + Config::get('hivemind.cache_reddit_subreddit_requests'))->diffForHumans()) }}
                @endif
                @if(isset($usersearch))
                        {{ str_replace('ago', 'from now', \Carbon\Carbon::createFromTimestamp(strtotime($aggregate_data['updated']) + Config::get('hivemind.cache_reddit_search_requests'))->diffForHumans()) }}
                @endif
			@endif
		</dd>
	</dl>
</div>
</div>