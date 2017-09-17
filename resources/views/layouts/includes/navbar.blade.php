<div class="navbar navbar-default navbar-fixed-top" role="navigation">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand fade-out" href="/">Show Alerts</a>
		</div>
		<div class="collapse navbar-collapse navbar-right">
			@if(Sentry::check())
			<ul class="nav navbar-nav">
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">Account <span class="caret"></span></a>
					<ul class="dropdown-menu" role="menu">
						<li><a class="fade-out" href="/user/alerts">Alerts</a></li>
						<li><a class="fade-out" href="/user/settings">Account Settings</a></li>
						<li class="divider"></li>
						@if(Sentry::getUser()->hasAccess('admin'))
						<li><a class="fade-out" href="/admin">Admin</a></li>
						<li class="divider"></li>
						@endif
						<li><a class="fade-out" href="/logout">Logout</a></li>
					</ul>
				</li>
			</ul>
			@else
			<ul class="nav navbar-nav navbar-right">
				<li class="pull-right">{!! HTML::link('login', 'Login', ['class' => 'fade-out']) !!}</li>
				<li class="pull-right">{!! HTML::link('register', 'Sign up', ['class' => 'fade-out']) !!}</li>
			</ul>
			@endif
		</div><!--/.nav-collapse -->
	</div>
</div>