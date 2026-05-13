@extends('theme::template')

@section('layout')
<div class="default-row mt-20">
	<div class="container-custom" style="margin-bottom:10px;">
        <div class="row-custom mlr-min-20">
            <div class="pageright">
            @include('theme::partials.sidebar')
            </div>
            <div class="pageleft">
            {{-- Content --}}
            @yield('content')
            </div>
        </div>
    </div>
</div>
@endsection