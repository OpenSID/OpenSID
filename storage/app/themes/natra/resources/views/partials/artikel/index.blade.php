@extends('theme::layouts.right-sidebar')
@section('content')
    <div class="content_left" style="margin-bottom:10px;">
        <div class="archive_style_1">
            <div style="margin-top:10px;">
                @if (!empty($teks_berjalan))
                    <marquee onmouseover="this.stop()" onmouseout="this.start()">
                        @include('theme::layouts.teks_berjalan')
                    </marquee>
                @endif
            </div>
            @include('theme::partials.slider')
            @if (setting('covid_data'))
                @include('theme::partials.corona-widget')
            @endif
            @if (setting('covid_desa'))
                @include('theme::partials.corona-local')
            @endif
            @if ($headline)
                @include('theme::partials.artikel.list', ['post' => $headline])
            @endif
        </div>
        @php $title = (!empty($judul_kategori)) ? $judul_kategori : 'Artikel Terkini' @endphp
        @if (is_array($title))
            @foreach ($title as $item)
                @php $title = $item @endphp
            @endforeach
        @endif
        <div class="single_category wow fadeInDown">
            <h2> <span class="bold_line"><span></span></span> <span class="solid_line"></span> <span class="title_text">{{ $title }}</span> </h2>
        </div>
        @if ($artikel->count() > 0)
            <div class="single_category wow fadeInDown">
                <div class="archive_style_1">
                    @foreach ($artikel as $post)
                        @include('theme::partials.artikel.list', ['post' => $post])
                    @endforeach
                </div>
            </div>
            @include('theme::commons.page')
        @else
            @include('theme::partials.artikel.empty', ['title' => $title])
        @endif

    </div>
@endsection
