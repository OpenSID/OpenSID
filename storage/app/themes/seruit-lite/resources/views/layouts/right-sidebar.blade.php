@extends('theme::template')

@section('layout')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-12">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <main class="lg:col-span-8">
            @yield('content')
        </main>
        <aside class="lg:col-span-4 space-y-6">
            @include('theme::partials.sidebar')
        </aside>
    </div>
</div>
@endsection