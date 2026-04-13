@extends('theme::layouts.right-sidebar')
@section('content')

	<div class="head-home">
		@include('theme::partials.header')
	</div>
	<div class="homepage">
		@if ($cari)
		@elseif (!empty($judul_kategori))
		@else
			@include('theme::partials.slider')
			@include('theme::partials.running_text')
		@endif
		@include('theme::partials.home')
		@include('theme::partials.footer')
	</div>
@endsection
