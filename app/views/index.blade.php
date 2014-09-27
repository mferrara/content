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
	<div class="col-md-12">
		<div class="text-center">
			<p class="lead">Recent shit people have searched for</p>
			<ul class="list-unstyled">
				@foreach($searches as $search)
					<li>{{ link_to('search?q='.urlencode($search->searchquery->name),$search->searchquery->name) }}</li>
				@endforeach
			</ul>
		</div>
	</div>
</div>



@stop