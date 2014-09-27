<table class="table table-condensed table-striped">
	<thead>
		<tr>
			<th>Title</th>
			<th>Link</th>
			<th>Type</th>
			<th>Score</th>
			<th>Subreddit</th>
			<th>Author</th>
			<th>Date Posted</th>
		</tr>
	</thead>
	<tbody>
	@foreach($articles as $article)
		<tr>
			<td>{{ link_to('post/'.$article->fullname, $article->title) }}</td>
			<td>{{ link_to($article->url, '>>', ['target' => '_blank', 'class' => 'btn btn-default btn-xs']) }}</td>
			<td>{{ $article->content_type }}</td>
			<td>{{ $article->score }}</td>
			<td>{{ link_to('sub/'.$article->subreddit->name, $article->subreddit->name) }}</td>
			<td>{{ link_to('author/'.$article->author->name, $article->author->name) }}</td>
			<td>{{ \Carbon\Carbon::createFromTimeStamp($article->created)->toFormattedDateString() }}</td>
		</tr>
	@endforeach
	</tbody>
</table>
@if(isset($usersearch))
<div class="text-center">{{ $articles->appends(['q' => $usersearch->searchquery->name])->links() }}</div>
@endif