@extends('theme::template')

@section('layout')
    <div class="w-full flex flex-col my-6 text-gray-600">
        <main class="w-full bg-white rounded-xl shadow-sm overflow-hidden p-4 lg:p-10">
            <div class="max-w-5xl mx-auto">
                {{-- Content --}}
                @yield('content')
            </div>
        </main>
    </div>
@endsection
