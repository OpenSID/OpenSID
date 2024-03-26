<div class="tab-pane active" id="pengaturan-umum">

    @include('admin.surat_dinas.pengaturan.kembali')

    <div class="box-body form-horizontal">
        <div class="form-group">
            <label class="col-sm-3 control-label" for="kode_surat">Kode/Klasifikasi Surat</label>
            <div class="col-sm-7">
                <select class="form-control input-sm required" id="kode_surat" name="kode_surat" data-placeholder="-- Pilih Kode/Klasifikasi Surat --">
                    @if ($klasifikasiSurat)
                        <option value="{{ $klasifikasiSurat->kode }}">
                            {{ $klasifikasiSurat->kode . ' - ' . $klasifikasiSurat->nama }}</option>
                    @elseif ($suratDinas->kode_surat)
                        <option value="{{ $suratDinas->kode_surat }}">
                            {{ $suratDinas->kode_surat }}</option>
                    @endif
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Nama Layanan</label>
            <div class="col-sm-7">
                <div class="input-group">
                    <span class="input-group-addon input-sm">Surat</span>
                    <input type="text" class="form-control input-sm nama_terbatas required" id="nama" name="nama" placeholder="Nama Layanan" value="{{ $suratDinas->nama }}" />
                </div>
            </div>
        </div>
        @if (strpos($form_action, 'insert') !== false && is_null($suratDinas->template))
            <div class="form-group">
                <label class="col-sm-3 control-label" for="nama">Pemohon Surat</label>
                <div class="col-sm-3">
                    <select class="form-control input-sm" id="pemohon_surat" name="pemohon_surat">
                        <option value="warga" selected>Warga</option>
                        <option value="non_warga">Bukan Warga</option>
                    </select>
                </div>
            </div>
        @endif

        <div class="form-group">
            <label class="col-sm-3 control-label" for="nama">Masa Berlaku Default</label>
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-2">
                        <input type="number" class="form-control input-sm" id="masa_berlaku" name="masa_berlaku" onchange="masaBerlaku()" value="{{ $suratDinas->masa_berlaku ?? 0 }}">
                    </div>
                    <div class="col-sm-3">
                        <select class="form-control input-sm" id="satuan_masa_berlaku" name="satuan_masa_berlaku">
                            @foreach ($masaBerlaku as $kode_masa => $judul_masa)
                                <option value="{{ $kode_masa }}" @selected($suratDinas->satuan_masa_berlaku === $kode_masa)>{{ $judul_masa }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <label class="text-muted text-red">Isi 0 jika tidak digunakan dan maksimal 31.</label>
            </div>
        </div>

        @if ($orientations)
            <div class="form-group">
                <label class="col-sm-3 control-label">Orientasi Kertas</label>
                <div class="col-sm-7">
                    <select class="form-control input-sm select2 required" name="orientasi">
                        @foreach ($orientations as $value)
                            <option value="{{ $value }}" @selected(($suratDinas->orientasi ?? $default_orientations) === $value)>
                                {{ $value }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        @endif

        @if ($orientations)
            <div class="form-group">
                <label class="col-sm-3 control-label">Ukuran Kertas</label>
                <div class="col-sm-7">
                    <select class="form-control input-sm select2 required" name="ukuran">
                        @foreach ($sizes as $value)
                            <option value="{{ $value }}" @selected(($suratDinas->ukuran ?? $default_sizes) === $value)>
                                {{ $value }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        @endif

        <div class="form-group">
            <label class="col-sm-3 control-label">Gunakan Margin Kertas Global</label>
            <div class="btn-group col-xs-12 col-sm-8" data-toggle="buttons" style="margin: 0 0 5px 0">
                <label id="lmg1" class="tipe btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-3 form-check-label @active($margin_global)">
                    <input
                        id="img1"
                        type="radio"
                        name="margin_global"
                        @checked($margin_global)
                        class="form-check-input"
                        type="radio"
                        value="1"
                        autocomplete="off"
                    >Ya
                </label>
                <label id="lmg2" class="tipe btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-3 form-check-label @active(!$margin_global)">
                    <input
                        id="img2"
                        type="radio"
                        name="margin_global"
                        class="form-check-input"
                        @checked(!$margin_global)
                        type="radio"
                        value="0"
                        autocomplete="off"
                    >Tidak
                </label>
            </div>
            <div id="manual_margin" style="display: none;">
                <div class="col-sm-7 col-sm-offset-3">
                    <div class="row">
                        @foreach ($margins as $key => $value)
                            <div class="col-sm-6">
                                <div class="input-group" style="margin-top: 3px; margin-bottom: 3px">
                                    <span class="input-group-addon input-sm">{{ ucwords($key) }}</span>
                                    <input
                                        type="number"
                                        class="form-control input-sm required"
                                        min="0"
                                        name="{{ $key }}"
                                        min="0"
                                        max="10"
                                        step="0.01"
                                        style="text-align:right;"
                                        value="{{ $value }}"
                                    >
                                    <span class="input-group-addon input-sm">cm</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3 control-label">Gunakan Penomoran Surat Global</label>
            <div class="btn-group col-xs-12 col-sm-8" data-toggle="buttons" style="margin: 0 0 5px 0">
                <label id="lmg11" class="tipe btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-3 form-check-label @active($format_nomor_global)">
                    <input
                        id="img11"
                        type="radio"
                        name="format_nomor_global"
                        @checked($format_nomor_global)
                        class="form-check-input"
                        type="radio"
                        value="1"
                        autocomplete="off"
                    >Ya
                </label>
                <label id="lmg21" class="tipe btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-3 form-check-label @active(!$format_nomor_global)">
                    <input
                        id="img21"
                        type="radio"
                        name="format_nomor_global"
                        class="form-check-input"
                        @checked(!$format_nomor_global)
                        type="radio"
                        value="0"
                        autocomplete="off"
                    >Tidak
                </label>
            </div>
            <div id="manual_nomor_surat" style="display: none;">
                <div class="col-sm-7 col-sm-offset-3">
                    <input type="text" class="form-control input-sm" name="format_nomor" placeholder="[nomor_surat, 3]/PK-TBT/[bulan_romawi]/[tahun]" value="{{ $format_nomor }}">
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3 control-label">Lampiran</label>
            <div class="col-sm-7">
                <select class="form-control input-sm select2" name="lampiran[]" multiple="multiple" data-placeholder="Pilih Lampiran">
                    @foreach ($daftar_lampiran as $value)
                        <option value="{{ $value }}" @selected(in_array($value, explode(',', $suratDinas->lampiran)))>{{ $value }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3 control-label">Tampilkan QR Code</label>
            <div class="btn-group col-xs-12 col-sm-8" data-toggle="buttons">
                <label id="lq1" class="tipe btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-3 form-check-label @active($suratDinas->qr_code)">
                    <input
                        id="iq1"
                        type="radio"
                        name="qr_code"
                        class="form-check-input"
                        type="radio"
                        value="1"
                        @checked($suratDinas->qr_code)
                        autocomplete="off"
                    >Ya
                </label>
                <label id="lq2" class="tipe btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-3 form-check-label @active(!$suratDinas->qr_code)">
                    <input
                        id="iq2"
                        type="radio"
                        name="qr_code"
                        class="form-check-input"
                        type="radio"
                        value="0"
                        @checked(!$suratDinas->qr_code)
                        autocomplete="off"
                    >Tidak
                </label>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3 control-label">Tampilkan Header</label>
            <div class="btn-group col-xs-12 col-sm-8" data-toggle="buttons">
                <label id="lh1" for="ih1" class="tipe btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-3 form-check-label @active($header == 1)">
                    <input
                        id="ih1"
                        type="radio"
                        name="header"
                        class="form-check-input"
                        type="radio"
                        value="1"
                        @checked($header == 1)
                        autocomplete="off"
                    >Semua Halaman
                </label>
                <label id="lh2" for="lh2" class="tipe btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-3 form-check-label @active($header == 2)">
                    <input
                        id="ih2"
                        type="radio"
                        name="header"
                        class="form-check-input"
                        type="radio"
                        value="2"
                        @checked($header == 2)
                        autocomplete="off"
                    >Hanya Halaman Awal
                </label>
                <label id="lh3" for="lh3" class="tipe btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-3 form-check-label @active($header == 0)">
                    <input
                        id="ih3"
                        type="radio"
                        name="header"
                        class="form-check-input"
                        type="radio"
                        value="0"
                        @checked($header == 0)
                        autocomplete="off"
                    >Tidak
                </label>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3 control-label">Tampilkan Footer</label>
            <div class="btn-group col-xs-12 col-sm-8" data-toggle="buttons">
                <label id="lf1" class="tipe btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-3 form-check-label @active($footer)">
                    <input
                        id="if1"
                        type="radio"
                        name="footer"
                        class="form-check-input"
                        type="radio"
                        value="1"
                        @checked($footer)
                        autocomplete="off"
                    >Ya
                </label>
                <label id="lf2" class="tipe btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-3 form-check-label @active(!$footer)">
                    <input
                        id="if2"
                        type="radio"
                        name="footer"
                        class="form-check-input"
                        type="radio"
                        value="0"
                        @checked(!$footer)
                        autocomplete="off"
                    >Tidak
                </label>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3 control-label" for="logo_garuda">Logo Burung Garuda</label>
            <div class="btn-group col-xs-12 col-sm-8" data-toggle="buttons">
                <label id="lbg1" class="tipe btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-3 form-check-label @active($suratDinas->logo_garuda)">
                    <input
                        id="ibg1"
                        type="radio"
                        name="logo_garuda"
                        class="form-check-input"
                        type="radio"
                        value="1"
                        @checked($suratDinas->logo_garuda)
                        autocomplete="off"
                    >Ya
                </label>
                <label id="lbg2" class="tipe btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-3 form-check-label @active(!$suratDinas->logo_garuda)">
                    <input
                        id="ibg2"
                        type="radio"
                        name="logo_garuda"
                        class="form-check-input"
                        type="radio"
                        value="0"
                        @checked(!$suratDinas->logo_garuda)
                        autocomplete="off"
                    >Tidak
                </label>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="confirm-restore" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-exclamation-triangle text-red"></i>
                    Konfirmasi</h4>
            </div>
            <div class="modal-body btn-info">
                Apakah Anda yakin ingin mengembalikan surat bawaan/sistem ini?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-social btn-danger btn-sm pull-left" data-dismiss="modal"><i class="fa fa-sign-out"></i> Tutup</button>
                <a class="btn-ok">
                    <a href="{{ ci_route('surat_dinas.restore_surat_bawaan', $suratDinas->url_surat) }}" class="btn btn-social btn-success btn-sm" id="ok-restore"><i class="fa fa-refresh"></i>
                        Kembalikan</a>
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            var margin_global = $("[name='margin_global']:checked").val()
            if (margin_global == 0) {
                $('#manual_margin').show()
            }

            var format_nomor_global = $("[name='format_nomor_global']:checked").val()
            if (format_nomor_global == 0) {
                $('#manual_nomor_surat').show()
            }
        })

        $("[name='margin_global']").change(function() {
            var val = $(this).val()
            if (val == 0) {
                $('#manual_margin').show()
            } else {
                $('#manual_margin').hide()
            }
        })

        $("[name='format_nomor_global']").change(function() {
            var val = $(this).val()
            if (val == 0) {
                $('#manual_nomor_surat').show()
            } else {
                $('#manual_nomor_surat').hide()
            }
        })

        $('#kode_surat').select2({
            tags: true,
            ajax: {
                url: SITE_URL + 'surat_dinas/apisurat',
                dataType: 'json',
                data: function(params) {
                    return {
                        q: params.term || '',
                        page: params.page || 1,
                    };
                },
                cache: true
            },
            placeholder: function() {
                return $(this).data('placeholder');
            },
            minimumInputLength: 1,
            allowClear: true,
            escapeMarkup: function(markup) {
                return markup;
            },
            createTag: function(params) {
                // batasi hanya 10 karakter
                var term = params.term.substring(0, 10);
                return {
                    id: term,
                    text: term,
                    newOption: true
                };
            },
            templateResult: function(data) {
                var $result = $("<span></span>").text(data.text);
                if (data.newOption) {
                    $result.append(" <em>(Buat Baru, maksimal 10 karakter)</em>");
                }
                return $result;
            },
            insertTag: function(data, tag) {
                data.push(tag);
            }
        });

        var TableData = $('#tabeldata').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            bPaginate: false,
            ajax: "{{ ci_route('surat_dinas.syaratSuratDatatables', $suratDinas->id) }}",
            columns: [{
                    data: 'ceklist',
                    class: 'padat',
                    searchable: false,
                    orderable: false
                },
                {
                    data: 'DT_RowIndex',
                    class: 'padat',
                    searchable: false,
                    orderable: false
                },
                {
                    data: 'ref_syarat_nama',
                    name: 'ref_syarat_nama',
                    searchable: true,
                    orderable: true
                },
            ],
            order: [
                [2, 'asc']
            ]
        });
    </script>
@endpush
