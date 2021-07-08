@extends('guest.master')

@section('meta:title', $page->renderMeta('title'))
@section('meta:description', $page->renderMeta('description'))
@section('meta:keywords', $page->renderMeta('keywords'))
@section('og:image', $page->renderMetaImage())
@section('og:title', $page->renderMeta('og_title'))
@section('og:description', $page->renderMeta('og_description'))

@section('content')

<div class="container">
	<div class="row">
		<div class="col-12 col-sm-12 offset-md-3 col-md-6 text-center mt-5 mb-3">
			<h1 class="theme--text">{{ $pageItems['frame-1-text'] }}</h1>
		</div>
	</div>
	<div class="row pb-5">
		<div class="col-12 col-sm-12 offset-md-1 col-md-10 theme--text">
			{!! $pageItems['frame-1-content'] !!}
		</div>
	</div>
</div>

@endsection