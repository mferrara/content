@extends('layouts.default')

@section('content')

{{ link_to('/', 'Home', ['class' => 'btn btn-primary']) }}

<h1>Searches <small>{{ count($searches) }}</small></h1>

<dl class="dl-horizontal">
	@foreach($searches as $search)
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

<div class="text-center">{{ $searches->links() }}</div>

@stop