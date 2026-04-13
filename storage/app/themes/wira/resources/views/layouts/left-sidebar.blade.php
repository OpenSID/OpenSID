@extends('theme::template')

@section('layout')
    <div class="container mx-auto lg:px-0 px-3 flex flex-col lg:flex-row my-5 gap-3 lg:gap-5 justify-between text-gray-600">
        <!-- Widget -->
        <div class="lg:w-1/3 w-full">
            @include('theme::partials.sidebar')
        </div>
        {{-- Content --}}
        <main>
            @yield('content')
        </main>
    </div>
@endsection
