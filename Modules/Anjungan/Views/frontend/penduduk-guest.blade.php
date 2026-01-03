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
            <h1>Permohonan Surat Tanpa Akun</h1>
        </div>
        <div class="relhid">
            @if ($errors->any())
                <div id="notif" class="alert alert-danger">
                    @foreach ($errors->all() as $item)
                        <p>{{ $item }}</p>
                    @endforeach
                </div>
            @endif
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
                            <span>Pindai KTP-el</span>
                        </label>
                    </li>
                    <li>
                        <label for="tab2" role="button" class="difle-c">
                            <svg viewBox="0 0 24 24">
                                <path
                                    d="M12.1,18.55L12,18.65L11.89,18.55C7.14,14.24 4,11.39 4,8.5C4,6.5 5.5,5 7.5,5C9.04,5 10.54,6 11.07,7.36H12.93C13.46,6 14.96,5 16.5,5C18.5,5 20,6.5 20,8.5C20,11.39 16.86,14.24 12.1,18.55M16.5,3C14.76,3 13.09,3.81 12,5.08C10.91,3.81 9.24,3 7.5,3C4.42,3 2,5.41 2,8.5C2,12.27 5.4,15.36 10.55,20.03L12,21.35L13.45,20.03C18.6,15.36 22,12.27 22,8.5C22,5.41 19.58,3 16.5,3Z"
                                />
                            </svg>
                            <span>Input Nama</span>
                        </label>
                    </li>
                </ul>
                <div class="content">
                    <section>
                        <div class="article-box d-flex" style="height:46.5vh; display:flex; align-items:center; justify-content:center; padding:0;">
                            <form id="form-ktp" action="{{ ci_route('anjungan-mandiri/penduduk-guest') }}" method="POST" autocomplete="off" style="width:100%; max-width:340px; margin:auto; text-align:center;">

                                <input type="hidden" name="{{ $ci->security->get_csrf_token_name() }}" value="{{ $ci->security->get_csrf_hash() }}" />

                                <img src="{{ asset('images/camera-scan.gif') }}" alt="tapping" width="100" style="margin-bottom:12px;">

                                @if (ENVIRONMENT == 'development')
                                    @php $display = ''; @endphp
                                    <div class="form-group" style="margin-bottom:12px;width:100%;">
                                        <input
                                            type="text"
                                            class="form-control"
                                            id="tag_id_card"
                                            name="tag_id_card"
                                            placeholder="Pindai KTP-el di sini"
                                            required
                                            autofocus
                                            style="width:100%;box-sizing:border-box;padding:7px 18px;font-size:1em;border-radius:5px;border:1px solid #ccc;"
                                        >
                                    </div>
                                @else
                                    @php $display = 'display:none;'; @endphp
                                    <input
                                        type="text"
                                        id="tag_id_card"
                                        name="tag_id_card"
                                        placeholder="Pindai KTP-el di sini"
                                        required
                                        autofocus
                                        style="width:0; height:0; overflow:hidden; border:none; padding:0; margin:0;"
                                    >
                                @endif

                                <button type="submit" class="btn btn-primary btn-block" style="width:100%; padding:8px 0; font-size:1em; border-radius:5px; background:var(--bg2); color:#fff; border:none; {{ $display }}">
                                    Lanjutkan
                                </button>
                            </form>
                        </div>
                    </section>
                    <section>
                        <div class="article-box difle-c" style="height:46.5vh;align-items:center;justify-content:center;padding:0;">
                            <form action="{{ ci_route('anjungan-mandiri/penduduk-guest') }}" method="POST" autocomplete="off" style="width:100%;max-width:340px;margin:auto;">
                                <input type="hidden" name="{{ $ci->security->get_csrf_token_name() }}" value="{{ $ci->security->get_csrf_hash() }}" />
                                <div class="form-group" style="margin-bottom:12px;width:100%;">
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="nama"
                                        name="nama"
                                        placeholder="Masukkan nama lengkap"
                                        required
                                        style="width:100%;box-sizing:border-box;padding:7px 18px;font-size:1em;border-radius:5px;border:1px solid #ccc;"
                                    >
                                </div>
                                <div class="form-group" style="margin-bottom:12px;width:100%;">
                                    <input
                                        type="date"
                                        class="form-control"
                                        id="tanggal_lahir"
                                        name="tanggal_lahir"
                                        placeholder="Tanggal Lahir"
                                        required
                                        style="width:100%;box-sizing:border-box;padding:7px 18px;font-size:1em;border-radius:5px;border:1px solid #ccc;"
                                    >
                                </div>
                                <button type="submit" class="btn btn-primary btn-block" style="width:100%;padding:8px 0;font-size:1em;border-radius:5px;background:var(--bg2);color:#fff;border:none;">Lanjutkan</button>
                            </form>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
    <!-- Mulai Artikel -->
@endsection

@push('scripts')
    <script>
        const inputField = document.getElementById('tag_id_card');
        const form = document.getElementById('form-ktp');

        inputField.addEventListener('keypress', function(event) {
            if (event.keyCode === 13 || event.key === 'Enter') {
                event.preventDefault();
                const value = this.value.trim();
                if (value.length === 10) {
                    form.submit();
                }
            }
        });

        $(document).ready(function() {
            window.setTimeout(function() {
                $("#notif").fadeTo(500, 0).slideUp(500, function() {
                    $(this).remove();
                });
            }, 5000);
        });
    </script>
@endpush
