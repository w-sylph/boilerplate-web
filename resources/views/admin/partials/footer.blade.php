<footer class="main-footer bg-danger overflow-hidden">
	@if ($env->dev())
    	<developer-mode
        disabled
        label="{{ config('web.version') }}"
        fetch-url="{{ route('developer.users.fetch') }}"
        submit-url="{{ route('developer.users.change-account') }}"
        ></developer-mode>
	@else
	    <div class="float-right d-none d-sm-block">
			<span class="font-weight-bold text-white">Version</span> {{ config('web.version') }}
	    </div>
	@endif
	<strong>Copyright &copy; {{ now()->year }} <a class="text-white" href="#">{{ config('app.name') }}</a>.</strong> All rights reserved.
</footer>