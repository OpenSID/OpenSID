@extends('theme::template')

@section('layout')
    <div class="w-full flex flex-col lg:flex-row my-6 gap-6 text-gray-600">
        {{-- Content --}}
        <main class="lg:flex-1 w-full bg-white rounded-xl shadow-sm overflow-hidden p-4 lg:p-6">
            @yield('content')
        </main>
        <!-- Widget -->
        <div class="lg:w-1/3 2xl:w-[400px] w-full">
            @include('theme::partials.sidebar')
        </div>
    </div>
@endsection
