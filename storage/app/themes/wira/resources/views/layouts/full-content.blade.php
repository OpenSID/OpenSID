@extends('theme::template')

@section('layout')
    <div class="container mx-auto lg:px-2 px-3 flex flex-col lg:flex-row my-5 gap-3 lg:gap-5 justify-between text-gray-600">
        <main class="w-full space-y-1 bg-white rounded-lg px-4 py-2 lg:py-4 lg:px-2 shadow">
            {{-- Content --}}
            @yield('content')
        </main>
    </div>
@endsection
