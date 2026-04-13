@extends('theme::layouts.full-content')

<div class="px-4 md:px-6 lg:px-8">
    @section('content')
        <div class="flex flex-col md:flex-row gap-8 mt-8">
            @include('theme::partials.history')
            @include('theme::partials.location')
        </div>
            
        <div class="flex flex-col md:flex-row gap-8 mt-16">
            @include('theme::partials.development')
            @include('theme::partials.vision')
        </div>
            
        @include('theme::partials.statistics')
        @include('theme::partials.articles')
        @include('theme::partials.officials')
    @endsection
</div>