@extends('theme::template')

@section('layout')
<div class="default-row mt-20">
	<div class="container-custom" style="margin-bottom:10px;">
        <div class="">
        {{-- Content --}}
        @yield('content')
        </div>
    </div>
</div>
@endsection