<!DOCTYPE html>
<html class="no-js" lang="{{ app()->getLocale() }}">
<head>

    @include('partials.meta-tags')
    @include('admin.partials.styles')

</head>
<body class="login-page">

	<div id="app" class="login-box">
	    <div class="login-logo">
	        <a href="#"><b>{{ config('app.name')}} Admin</b></a>
	    </div>
	    <!-- /.login-logo -->
	    <div class="card">
		    <div class="card-body login-card-body">

		        @yield('content')

		    </div>
	    </div>
    </div>

</body>
</html>