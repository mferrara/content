@extends('layouts.default')

@section('content')

<div class="row">
	<div class="col-md-4 col-md-offset-2">
		<div class="text-center">
            <div class="text-center">
                <p class="lead">Add search to queue</p>
            </div>

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
            <div class="text-center">
                <p class="lead">Dig into a subreddit</p>
            </div>

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
<br/>
<hr/>

<div class="row">
    <div class="col-md-3 col-md-offset-1 col-xs-12">
        <p class="lead text-center">Aggregate Stats</p>
        <dl class="dl-horizontal">
            <dt>Posts indexed</dt>
            <dd>{{ number_format($total_articles) }}</dd>
            <dt>Text Posts</dt>
            <dd>{{ number_format($total_self) }}</dd>
            <dt>Subreddits</dt>
            <dd>{{ number_format($total_subreddits) }}</dd>
            <dt>Post authors</dt>
            <dd>{{ number_format($total_authors) }}</dd>
            <dt>Linked domains</dt>
            <dd>{{ number_format($total_domains) }}</dd>
            <dt>&nbsp;</dt><dd>&nbsp;</dd>
            <dt>{{ link_to('searches', 'Keywords searched') }}</dt>
            <dd>{{ number_format($total_queries) }}</dd>
            <dt>{{ link_to('subreddits', 'Subreddits dug into') }}</dt>
            <dd>{{ number_format($scraped_subreddits) }}</dd>
            <dt>{{ link_to('pending', 'Searches pending') }}</dt>
            <dd>{{ number_format($pending_searches) }}</dd>
        </dl>
    </div>
    <div class="col-md-4 col-xs-12">
        <div class="text-center">
            <p class="lead">Recently Updated Searches</p>
            <ul class="list-unstyled">
                @foreach($recent_searches as $search)
                    <li>{{ link_to('search?q='.urlencode($search->name),$search->name) }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="col-md-4 col-xs-12">
        <div class="text-center">
            <p class="lead">Recently Updated Subreddits</p>
            <ul class="list-unstyled">
                @foreach($recent_subreddits as $subreddit)
                    <li>{{ link_to('sub/'.$subreddit->name, 'r/'.$subreddit->name) }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

<br/><br/>

<div class="row">
    <div class="col-md-4 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Top Linked Domains <small>(By article count)</small></h3>
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
                    @foreach($top_domains as $domain)
                        <tr>
                            <td>{{ link_to('domain/'.$domain->name, $domain->name) }}</td>
                            <td class="text-right">{{ number_format($domain->article_count) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <p class="text-right">
                    <a href="/domains">View All</a>
                </p>
            </div>
        </div>
    </div>

    <div class="col-md-4 col-xs-12">
        <div class="panel panel-default">
        	  <div class="panel-heading">
        			<h3 class="panel-title">Top Subreddits <small>(By article count)</small></h3>
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
                		@foreach($top_subreddits as $sub)
                            <tr>
                                <td>{{ link_to('sub/'.$sub->name, 'r/'.$sub->name) }}</td>
                                <td class="text-right">{{ number_format($sub->article_count) }}</td>
                            </tr>
                        @endforeach
                	</tbody>
                </table>
                  <p class="text-right">
                      <a href="/subreddits">View All</a>
                  </p>
        	  </div>
        </div>
    </div>

    <div class="col-md-4 col-xs-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Top Authors <small>(By article count)</small></h3>
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
                    @foreach($top_authors as $author)
                        <tr>
                            <td>{{ link_to('author/'.$author->name, $author->name) }}</td>
                            <td class="text-right">{{ number_format($author->article_count) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                <p class="text-right">
                    <a href="/authors">View All</a>
                </p>
            </div>
        </div>
    </div>

</div>

<br/><br/>

<div class="row">
    <div class="col-md-4 col-md-offset-2 col-xs-12">
        <div class="text-center">
            <p class="lead">Random subreddits</p>
            <ul class="list-unstyled">
                @foreach($random_subreddits as $subreddit)
                    <li>{{ link_to('sub/'.$subreddit->name, 'r/'.$subreddit->name) }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    <div class="col-md-4 col-xs-12">
        <div class="text-center">
            <p class="lead">Random authors</p>
            <ul class="list-unstyled">
                @foreach($random_authors as $author)
                    <li>{{ link_to('author/'.$author->name, 'u/'.$author->name) }}</li>
                @endforeach
            </ul>
        </div>
    </div>
</div>

@stop