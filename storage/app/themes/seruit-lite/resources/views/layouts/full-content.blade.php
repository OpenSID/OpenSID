@extends('theme::template')

@section('layout')
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 pb-12">
        <main>
            @yield('content')
        </main>
    </div>
@endsection