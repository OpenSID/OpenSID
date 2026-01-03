@php
    $layout = match (setting('anjungan_layar')) {
        '1' => 'anjungan::frontend.layout-landscape',
        '2' => 'anjungan::frontend.layout-portrait',
        default => 'anjungan::frontend.layout-landscape',
    };
@endphp

@extends($layout)

@section('content')
    <!-- Mulai Artikel -->
    <div class="article-area">
        <div class="article-head difle-c">
            <h1>Berita {{ ucwords(setting('sebutan_desa')) }}</h1>
        </div>
        <div class="relhid">
            <div class="tabs">
                <input type="radio" id="tab1" name="tab-control" checked>
                <input type="radio" id="tab2" name="tab-control">
                <ul>
                    <li>
                        <label for="tab1" role="button" class="difle-c">
                            <svg viewBox="0 0 24 24">
                                <path
                                    d="M21,16.5C21,16.88 20.79,17.21 20.47,17.38L12.57,21.82C12.41,21.94 12.21,22 12,22C11.79,22 11.59,21.94 11.43,21.82L3.53,17.38C3.21,17.21 3,16.88 3,16.5V7.5C3,7.12 3.21,6.79 3.53,6.62L11.43,2.18C11.59,2.06 11.79,2 12,2C12.21,2 12.41,2.06 12.57,2.18L20.47,6.62C20.79,6.79 21,7.12 21,7.5V16.5M12,4.15L5,8.09V15.91L12,19.85L19,15.91V8.09L12,4.15Z"
                                />
                            </svg>
                            <span>Terbaru</span>
                        </label>
                    </li>
                    <li>
                        <label for="tab2" role="button" class="difle-c">
                            <svg viewBox="0 0 24 24">
                                <path
                                    d="M12.1,18.55L12,18.65L11.89,18.55C7.14,14.24 4,11.39 4,8.5C4,6.5 5.5,5 7.5,5C9.04,5 10.54,6 11.07,7.36H12.93C13.46,6 14.96,5 16.5,5C18.5,5 20,6.5 20,8.5C20,11.39 16.86,14.24 12.1,18.55M16.5,3C14.76,3 13.09,3.81 12,5.08C10.91,3.81 9.24,3 7.5,3C4.42,3 2,5.41 2,8.5C2,12.27 5.4,15.36 10.55,20.03L12,21.35L13.45,20.03C18.6,15.36 22,12.27 22,8.5C22,5.41 19.58,3 16.5,3Z"
                                />
                            </svg>
                            <span>Populer</span>
                        </label>
                    </li>
                </ul>
                <div class="slider">
                    <div class="indicator"></div>
                </div>
                <div class="content">
                    <section>
                        <div class="article-box">
                            <div id="slide-container">
                                <div id="slides">
                                    @for ($i = 0; $i < $arsip_terkini->count(); $i += 2)
                                        <article class="featured-article animated">
                                            <div class="mlr-10">
                                                <div class="grider mlr-min5">
                                                    <div class="col-2">
                                                        <a data-value="{{ $arsip_terkini[$i]['url_slug'] }}" class="popup">
                                                            <div class="imagecrop-grid">
                                                                @if (file_exists(LOKASI_FOTO_ARTIKEL . 'sedang_' . $arsip_terkini[$i]['gambar']))
                                                                    <img src="{{ base_url(LOKASI_FOTO_ARTIKEL . 'sedang_' . $arsip_terkini[$i]['gambar']) }}">
                                                                @else
                                                                    <img src="{{ base_url('assets/images/404-image-not-found.jpg') }}">
                                                                @endif

                                                                <div class="posting">
                                                                    {{ tgl_indo($arsip_terkini[$i]['tgl_upload']) }}
                                                                </div>
                                                            </div>
                                                            <h2>{{ \Illuminate\Support\Str::limit($arsip_terkini[$i]->judul, $limit = 75, $end = '...') }}
                                                            </h2>
                                                        </a>
                                                    </div>

                                                    @if ($arsip_terkini[$i + 1])
                                                        <div class="col-2">
                                                            <a data-value="{{ $arsip_terkini[$i + 1]['url_slug'] }}" class="popup">
                                                                <div class="imagecrop-grid">
                                                                    @if (is_file(LOKASI_FOTO_ARTIKEL . 'sedang_' . $arsip_terkini[$i + 1]['gambar']))
                                                                        <img src="{{ base_url(LOKASI_FOTO_ARTIKEL . 'sedang_' . $arsip_terkini[$i + 1]['gambar']) }}">
                                                                    @else
                                                                        <img src="{{ base_url('assets/images/404-image-not-found.jpg') }}">
                                                                    @endif

                                                                    <div class="posting">
                                                                        {{ tgl_indo($arsip_terkini[$i + 1]['tgl_upload']) }}
                                                                    </div>
                                                                </div>
                                                                <h2>{{ \Illuminate\Support\Str::limit($arsip_terkini[$i + 1]->judul, $limit = 75, $end = '...') }}
                                                                </h2>
                                                            </a>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </article>
                                        <div class="button-slide difle-c">
                                            <button class="prev">
                                                <div class="slide-btn difle-c"><svg viewBox="0 0 24 24">
                                                        <path d="M7.41,15.41L12,10.83L16.59,15.41L18,14L12,8L6,14L7.41,15.41Z" />
                                                    </svg></div>
                                            </button>
                                            <button class="next">
                                                <div class="slide-btn difle-c"><svg viewBox="0 0 24 24">
                                                        <path d="M7.41,8.58L12,13.17L16.59,8.58L18,10L12,16L6,10L7.41,8.58Z" />
                                                    </svg></div>
                                            </button>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    </section>
                    <section>
                        <div class="article-box">
                            <div class="marquee-top">
                                <div class="track-top">
                                    @foreach ($arsip_populer as $arsip)
                                        <a data-value="{{ $arsip['url_slug'] }}" class="popup">
                                            <div class="article-row">
                                                <div class="relhid mlr-min5">
                                                    <div class="article-image">
                                                        <div class="imagecrop-artikel">
                                                            <img src="images/artikel/artikel5.jpg">
                                                            @if (is_file(LOKASI_FOTO_ARTIKEL . 'sedang_' . $arsip['gambar']))
                                                                <img src="{{ base_url(LOKASI_FOTO_ARTIKEL . 'sedang_' . $arsip['gambar']) }}">
                                                            @else
                                                                <img src="{{ base_url('assets/images/404-image-not-found.jpg') }}">
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="article-title">
                                                        <p>{{ hit($arsip['hit']) }} dilihat</p>
                                                        <h2> {{ \Illuminate\Support\Str::limit($arsip->judul, $limit = 65, $end = '...') }}
                                                        </h2>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
    <!-- Mulai Artikel -->
@endsection
