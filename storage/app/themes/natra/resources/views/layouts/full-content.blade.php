@extends('theme::template')

@push('styles')
    <style>
        .web .content-wrapper {
            margin-left: 0px !important;
        }
    </style>
@endpush

@section('layout')
    <section id="mainContent">
        <div class="content_bottom">
            <div class="row">
                <div class="col-lg-12 col-md-12">
                    <div id="contentwrapper" class="web">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
