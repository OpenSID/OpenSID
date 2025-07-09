@extends('theme::template')

@section('layout')
    <section>
        <div class="content_bottom">
            <div class="row">
                <div class="col-lg-3 col-md-3">
                    @include('theme::partials.sidebar')
                </div>
                <div class="col-lg-9 col-md-9">
                    <div class="content_left">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
