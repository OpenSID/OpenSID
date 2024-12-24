@extends('buku_tamu.index')

@section('content')
    <div class="col-xl-8 mb-5 mb-xl-10">
        <div class="card card-flush h-lg-100">
            <div class="card-header pt-7">
                <div class="card-title">
                    <span class="svg-icon svg-icon-1 me-2">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M20 14H18V10H20C20.6 10 21 10.4 21 11V13C21 13.6 20.6 14 20 14ZM21 19V17C21 16.4 20.6 16 20 16H18V20H20C20.6 20 21 19.6 21 19ZM21 7V5C21 4.4 20.6 4 20 4H18V8H20C20.6 8 21 7.6 21 7Z" fill="currentColor"></path>
                            <path opacity="0.3"
                                d="M17 22H3C2.4 22 2 21.6 2 21V3C2 2.4 2.4 2 3 2H17C17.6 2 18 2.4 18 3V21C18 21.6 17.6 22 17 22ZM10 7C8.9 7 8 7.9 8 9C8 10.1 8.9 11 10 11C11.1 11 12 10.1 12 9C12 7.9 11.1 7 10 7ZM13.3 16C14 16 14.5 15.3 14.3 14.7C13.7 13.2 12 12 10.1 12C8.10001 12 6.49999 13.1 5.89999 14.7C5.59999 15.3 6.19999 16 7.39999 16H13.3Z"
                                fill="currentColor"
                            ></path>
                        </svg>
                    </span>
                    <h2>Indeks Kepuasan</h2>
                </div>
            </div>
            <div class="card-body pt-5">
                <div class="fv-row mb-7">
                    <h2 class="text-center">{{ $pertanyaan->pertanyaan }}</h2>
                </div>
                <div class="row g-6 g-xl-9 mb-6 mb-xl-9">
                    @foreach (\App\Enums\JawabanKepuasanEnum::all() as $index => $jawaban)
                        <div class="col-sm-12 col-md-6 col-lg-4 col-xl-3">
                            <a href="{{ site_url('buku-tamu/jawaban/' . $id . '/' . $index . '?pertanyaan=' . $pertanyaan->pertanyaan) }}" class="text-gray-800 text-hover-primary d-flex flex-column">
                                <div class="card h-100" style="background-color: #f3f7f9">
                                    <div class="card-body d-flex justify-content-center text-center flex-column p-3">
                                        <div class="symbol symbol-60px mb-5">
                                            <img src="{{ asset('images/layanan_mandiri/' . underscore($jawaban, true, true) . '.png') }}" class="theme-light-show" alt="{{ $jawaban }}">
                                        </div>
                                        <div class="fs-5 fw-bold mb-2">{{ $jawaban }}</div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
