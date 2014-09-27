<!doctype html>
<html lang="en-US">
	<head>
		@include('layouts.includes.head')
	</head>
	<body>

		<div class="container-fluid">
			@include('flash::message')

			@yield('content')
		</div>

		@include('layouts.includes.footer')
		@include('layouts.includes.js')
	</body>
</html>
