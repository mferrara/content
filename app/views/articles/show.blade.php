@extends('layouts.default')

@section('content')

    {{ link_to('/', 'Home', ['class' => 'btn btn-primary']) }}

    <h1>{{ $article->title }}</h1>
    <hr/>
    
    <div class="row">
        <div class="col-md-4">
            <div class="panel panel-default">
            	  <div class="panel-heading">
            			<h3 class="panel-title">Details</h3>
            	  </div>
            	  <div class="panel-body">
            			<table class="table table-condensed">
            				<thead>
            					<tr>
            						<th></th>
            						<th></th>
            					</tr>
            				</thead>
            				<tbody>
            					<tr>
            						<td><strong>Type</strong></td>
            						<td>{{ $article->content_type }}</td>
            					</tr>
                                <tr>
                                    <td><strong>Word Count</strong></td>
                                    <td>{{ number_format($article->word_count) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Character Count</strong></td>
                                    <td>{{ number_format($article->character_count) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Paragraph Count</strong></td>
                                    <td>{{ number_format($article->paragraph_count) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Upvotes</strong></td>
                                    <td>{{ number_format($article->ups) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Downvotes</strong></td>
                                    <td>{{ number_format($article->downs) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Score</strong></td>
                                    <td>{{ number_format($article->score) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Comment Count</strong></td>
                                    <td>{{ number_format($article->num_comments) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>NSFW</strong></td>
                                    <td>{{ $article->nsfw == 1 ? 'Yes' : 'No' }}</td>
                                </tr>
            				</tbody>
            			</table>
            	  </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Relationships</h3>
                </div>
                <div class="panel-body">
                    <table class="table table-condensed">
                        <thead>
                        <tr>
                            <th></th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td><strong>Subreddit</strong></td>
                            <td>{{ $article->subreddit->present()->link() }}</td>
                        </tr>
                        <tr>
                            <td><strong>Author</strong></td>
                            <td>{{ $article->author->present()->link() }}</td>
                        </tr>
                        <tr>
                            <td><strong></strong></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><strong></strong></td>
                            <td></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-right">
            <a href="http://reddit.com{{ $article->permalink }}" class="btn btn-primary">View on Reddit</a>
            @if($article->is_self == 0)
                <a href="{{ $article->url }}" class="btn btn-primary">View Linked Page</a>
            @endif
        </div>
    </div>

    @if($article->is_self)
        <p>{{ html_entity_decode($article->present()->body()) }}</p>
    @endif

@stop