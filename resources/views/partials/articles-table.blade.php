@if($articles == false)

@else

<table class="table table-condensed table-striped">
	<thead>
		<tr>
			<th>Title</th>
			<th>Link</th>
			<th>Type</th>
			<th>Score</th>
			<th>Subreddit</th>
			<th>Author</th>
			<th class="text-right" style="width: 120px">Date Posted</th>
		</tr>
	</thead>
	<tbody>
	@foreach($articles as $article)
		<tr>
			<td>
                {!! link_to('post/'.$article->fullname, $article->title) !!}
                @if($article->nsfw == 1)
                    <span class="pull-right label label-warning">NSFW</span>
                @endif
            </td>
			<td>{!! link_to($article->url, '>>', ['target' => '_blank', 'class' => 'btn btn-default btn-xs']) !!}</td>
			<td>{!! $article->content_type !!}</td>
			<td class="text-right">{!! number_format($article->score) !!}</td>
			<td>{!! link_to('sub/'.$article->subreddit->name, $article->subreddit->name) !!}</td>
			<td>{!! link_to('author/'.$article->author->name, $article->author->name) !!}</td>
			<td class="text-right">{!! \Carbon\Carbon::createFromTimeStamp($article->created)->toFormattedDateString() !!}</td>
		</tr>
	@endforeach
	</tbody>
</table>
@if(isset($usersearch))
	<div class="text-center">{!! $articles->appends(['q' => $usersearch->searchquery->name])->render() !!}</div>
@else
	<div class="text-center">{!! $articles->render() !!}</div>
@endif

@endif