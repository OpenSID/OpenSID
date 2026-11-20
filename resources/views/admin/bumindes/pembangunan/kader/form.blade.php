@extends('admin.layouts.index')
@include('admin.layouts.components.asset_validasi')
@include('admin.layouts.components.jquery_ui')
@include('admin.layouts.components.tokenfield')

@section('title')
    <h1>
        Buku Kader Pemberdayaan
        <small>{{ $action }} Data</small>
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ ci_route('bumindes_kader') }}">Daftar Buku Kader Pemberdayaan</a></li>
    <li class="active">{{ $action }} Data</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="box box-info">
        <div class="box-header with-border">
            @include('admin.layouts.components.tombol_kembali', ['url' => ci_route('bumindes_kader'), 'label' => 'Daftar Buku Kader Pemberdayaan'])
        </div>
        {!! form_open($formAction, 'class="form-horizontal" id="validasi"') !!}
        <div class="box-body">
            <div class="form-group">
                <label class="col-sm-3 control-label" for="penduduk_id">NIK / Nama Kader</label>
                <div class="col-sm-6">
                    <select class="form-control select2 required" id="penduduk_id" name="penduduk_id">
                        <option value="" selected="selected">-- Silakan Masukkan NIK / Nama Kader --</option>
                        @foreach ($daftar_penduduk as $penduduk)
                            <option value="{{ $penduduk->id }}" @selected($main->penduduk_id == $penduduk->id)>NIK : {{ $penduduk->nik . ' | Nama : ' . $penduduk->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label" for="kursus">Kursus</label>
                <div class="col-sm-6">
                    <input type="text" name="kursus" id="kursus" class="form-control ui-autocomplete required" placeholder="Pilih Kursus" value="{{ $main->kursus }}" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label" for="bidang">Bidang Keahlian</label>
                <div class="col-sm-6">
                    <input type="text" name="bidang" id="bidang" class="form-control ui-autocomplete required" placeholder="Pilih Bidang Keahlian" value="{{ $main->bidang }}" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label" for="keterangan">Keterangan</label>
                <div class="col-sm-6">
                    <textarea name="keterangan" id="keterangan" class="form-control input-sm required" maxlength="100" placeholder="Keterangan" rows="5">{{ $main->keterangan }}</textarea>
                </div>
            </div>
        </div>
        <div class="box-footer">
            <button type="reset" class="btn btn-social btn-danger btn-sm" onclick="reset_form($(this).val());"><i class="fa fa-times"></i> Batal</button>
            <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
        </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {

            // Base URL untuk request AJAX
            var baseUrl = SITE_URL + '/bumindes_kader/';

            // Cache global untuk autocomplete
            var autoCompleteCache = {};

            /**
             * Inisialisasi Tokenfield + Autocomplete AJAX
             */
            function initTokenField(selector, ajaxEndpoint) {

                $(selector).tokenfield({
                    autocomplete: {
                        source: function (request, response) {

                            let keyword = request.term || '';
                            let cacheKey = ajaxEndpoint + '|' + keyword;

                            // Ambil token yang sudah dipilih
                            let existingValues = $(selector)
                                .tokenfield('getTokens')
                                .map(token => token.value);

                            // =========================
                            // AMBIL DARI CACHE (JIKA ADA)
                            // =========================
                            if (autoCompleteCache[cacheKey]) {
                                let filtered = autoCompleteCache[cacheKey].filter(item =>
                                    !existingValues.includes(item.value)
                                );
                                response(filtered);
                                return;
                            }

                            // =========================
                            // REQUEST KE SERVER
                            // =========================
                            $.get(baseUrl + ajaxEndpoint, { nama: keyword }, function (data) {

                                let parsed;

                                // Fallback parsing JSON (aman)
                                if (typeof data === 'string') {
                                    try {
                                        parsed = JSON.parse(data);
                                    } catch (e) {
                                        parsed = [];
                                    }
                                } else {
                                    parsed = data;
                                }

                                // Normalisasi ke format { value: "xxx" }
                                let normalized = parsed.map(item => {
                                    if (typeof item === 'object' && item.value) {
                                        return item;
                                    }
                                    return { value: item };
                                });

                                // Simpan ke cache
                                autoCompleteCache[cacheKey] = normalized;

                                // Filter token yang sudah dipilih
                                let filtered = normalized.filter(item =>
                                    !existingValues.includes(item.value)
                                );

                                response(filtered);
                            });
                        },
                        delay: 150
                    },
                    showAutocompleteOnFocus: true
                });

                /**
                 * Cegah token duplikat (lapisan keamanan)
                 */
                $(selector).on('tokenfield:createtoken', function (e) {
                    let tokens = $(this).tokenfield('getTokens');
                    tokens.forEach(token => {
                        if (token.value === e.attrs.value) {
                            e.preventDefault();
                        }
                    });
                });

                /**
                 * Paksa autocomplete muncul saat klik / fokus
                 */
                $(document).on('focus click', selector + '-tokenfield', function () {
                    $(this).autocomplete('search', '');
                });

                /**
                 * Load data lama (mode edit)
                 */
                let value = $(selector).val();
                if (value) {
                    try {
                        $(selector).tokenfield('setTokens', JSON.parse(value));
                    } catch (e) {
                        $(selector).tokenfield('setTokens', value.split(','));
                    }
                }
            }

            // =========================
            // INISIALISASI FIELD
            // =========================
            initTokenField('#kursus', 'get_kursus');
            initTokenField('#bidang', 'get_bidang');

        });
    </script>

@endpush
