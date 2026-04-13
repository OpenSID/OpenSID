@extends('theme::template')

@section('layout')
    <div class="container mx-auto lg:px-0 px-3 flex flex-col lg:flex-row my-5 gap-3 lg:gap-5 justify-between text-gray-600">
        {{-- Content --}}
        <main class="lg:w-2/3 w-full bg-white mr-4 py-2 ">
            @yield('content')
        </main>
        <!-- Widget -->
        <div class="lg:w-1/3 w-full ml-0">
            @include('theme::partials.sidebar')
        </div>
    </div>
@endsection
