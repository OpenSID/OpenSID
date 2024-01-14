<div class="tab-pane active" id="pengaturan-umum">
    <div class="box-header with-border">
        <a href="{{ route('surat_master') }}"
            class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
            <i class="fa fa-arrow-circle-left"></i>Kembali ke Daftar Surat
        </a>
        @if (setting('tte') && ($suratMaster->jenis == 3 || $suratMaster->jenis == 4))
            <br/><br/>
            <div class="alert alert-info alert-dismissible">
                <h4><i class="icon fa fa-info"></i> Info !</h4>
                Jika surat ingin dikirim ke kecamatan, letakan kode [qr_camat] pada tempat yang ingin ditempelkan QRCode Kecamatan.
            </div>
        @endif
    </div>
    <div class="box-body form-horizontal">
        <div class="form-group">
            <label class="col-sm-3 control-label" for="kode_surat">Kode/Klasifikasi Surat</label>
            <div class="col-sm-7">
                <select class="form-control input-sm required" id="kode_surat" name="kode_surat">
                    @empty($suratMaster->kode_surat)
                        <option value="">-- Pilih Kode/Klasifikasi Surat --</option>
                    @else
                    <option value="{{ $suratMaster->kode_surat }}">{{ $suratMaster->kode_surat }}</option>
                    @endif
                    @foreach ($klasifikasiSurat as $item)
                        <option value="{{ $item->kode }}" @selected($item->kode === $suratMaster->kode_surat)>
                            {{ $item->kode . ' - ' . $item->nama }}</option>
                    @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label">Nama Layanan</label>
                <div class="col-sm-7">
                    <div class="input-group">
                        <span class="input-group-addon input-sm">Surat</span>
                        <input type="text" class="form-control input-sm nama_terbatas required" id="nama"
                            name="nama" placeholder="Nama Layanan" value="{{ $suratMaster->nama }}" />
                    </div>
                </div>
            </div>
            @if (strpos($form_action, 'insert') !== false && is_null($suratMaster->template))
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
                            <input type="number" class="form-control input-sm" id="masa_berlaku" name="masa_berlaku"
                                onchange="masaBerlaku()" value="{{ $suratMaster->masa_berlaku ?? 1 }}">
                        </div>
                        <div class="col-sm-3">
                            <select class="form-control input-sm" id="satuan_masa_berlaku" name="satuan_masa_berlaku">
                                @foreach ($masaBerlaku as $kode_masa => $judul_masa)
                                    <option value="{{ $kode_masa }}" @selected($suratMaster->satuan_masa_berlaku === $kode_masa)>{{ $judul_masa }}
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
                        <select class="form-control input-sm select2-tags required" name="orientasi">
                            @foreach ($orientations as $value)
                                <option value="{{ $value }}" @selected(($suratMaster->orientasi ?? $default_orientations) === $value)>
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
                        <select class="form-control input-sm select2-tags required" name="ukuran">
                            @foreach ($sizes as $value)
                                <option value="{{ $value }}" @selected(($suratMaster->ukuran ?? $default_sizes) === $value)>
                                    {{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif

            @if ($margins)
                <div class="form-group">
                    <label class="col-sm-3 control-label">Margin Kertas</label>
                    <div class="col-sm-7">
                        <div class="row">
                            @foreach ($margins as $key => $value)
                                <div class="col-sm-6">
                                    <div class="input-group" style="margin-top: 3px; margin-bottom: 3px">
                                        <span class="input-group-addon input-sm">{{ ucwords($key) }}</span>
                                        <input type="number" class="form-control input-sm required" min="0"
                                            name="{{ $key }}" min="0" max="10" step="0.01" style="text-align:right;"
                                            value="{{ $value }}">
                                        <span class="input-group-addon input-sm">cm</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            @if (! in_array($suratMaster->jenis, [1, 2]))
                <div class="form-group">
                    <label class="col-sm-3 control-label">Lampiran</label>
                    <div class="col-sm-7">
                        <select class="form-control input-sm select2" name="lampiran">
                            <option value="">Tidak Ada</option>
                            @foreach ($daftar_lampiran as $value)
                                <option value="{{ $value }}" @selected($suratMaster->lampiran === $value)>
                                    {{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif

            @if (isset($format_nomor))
                <div class="form-group">
                    <label class="col-sm-3 control-label">Format Nomor Surat</label>
                    <div class="col-sm-7">
                        <input type="text" class="form-control input-sm" name="format_nomor" placeholder="Format Nomor Surat" value="{{ $format_nomor }}">
                    </div>
                </div>
            @endif

            @if ($qrCode)
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="mandiri">Tampilkan QR Code</label>
                    <div class="btn-group col-xs-12 col-sm-8" data-toggle="buttons">
                        <label id="n1"
                            class="tipe btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-2 form-check-label @active($suratMaster->qr_code)">
                            <input id="q1" type="radio" name="qr_code" class="form-check-input" type="radio"
                                value="1" @checked($suratMaster->qr_code) autocomplete="off">Ya
                        </label>
                        <label id="n2"
                            class="tipe btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-2 form-check-label @active(!$suratMaster->qr_code)">
                            <input id="q2" type="radio" name="qr_code" class="form-check-input" type="radio"
                                value="0" @checked(!$suratMaster->qr_code) autocomplete="off">Tidak
                        </label>
                    </div>
                </div>
            @endif

            @if (isset($header))
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="mandiri">Tampilkan Header</label>
                    <div class="btn-group col-xs-12 col-sm-8" data-toggle="buttons">
                        <label id="n1"
                            class="tipe btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-2 form-check-label @active($header)">
                            <input id="q1" type="radio" name="header" class="form-check-input" type="radio"
                                value="1" @checked($header) autocomplete="off">Ya
                        </label>
                        <label id="n2"
                            class="tipe btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-2 form-check-label @active(!$header)">
                            <input id="q2" type="radio" name="header" class="form-check-input" type="radio"
                                value="0" @checked(!$header) autocomplete="off">Tidak
                        </label>
                    </div>
                </div>
            @endif

            @if (isset($footer))
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="mandiri">Tampilkan Footer</label>
                    <div class="btn-group col-xs-12 col-sm-8" data-toggle="buttons">
                        <label id="n1"
                            class="tipe btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-2 form-check-label @active($footer)">
                            <input id="q1" type="radio" name="footer" class="form-check-input" type="radio"
                                value="1" @checked($footer) autocomplete="off">Ya
                        </label>
                        <label id="n2"
                            class="tipe btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-2 form-check-label @active(!$footer)">
                            <input id="q2" type="radio" name="footer" class="form-check-input" type="radio"
                                value="0" @checked(!$footer) autocomplete="off">Tidak
                        </label>
                    </div>
                </div>
            @endif


            <div class="form-group">
                <label class="col-sm-3 control-label" for="logo_garuda">Logo Burung Garuda</label>
                <div class="btn-group col-xs-12 col-sm-8" data-toggle="buttons">
                    <label id="o1"
                        class="tipe btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-2 form-check-label @active($suratMaster->logo_garuda)">
                        <input id="bg1" type="radio" name="logo_garuda" class="form-check-input" type="radio"
                            value="1" @checked($suratMaster->logo_garuda) autocomplete="off">Ya
                    </label>
                    <label id="o2"
                        class="tipe btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-2 form-check-label @active(!$suratMaster->logo_garuda)">
                        <input id="bg2" type="radio" name="logo_garuda" class="form-check-input" type="radio"
                            value="0" @checked(!$suratMaster->logo_garuda) autocomplete="off">Tidak
                    </label>
                </div>
            </div>

            @if (setting('tte'))
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="kecamatan">Kirim ke Kecamatan</label>
                    <div class="btn-group col-xs-12 col-sm-8" data-toggle="buttons">
                        <label id="n1"
                            class="tipe btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-2 form-check-label @active($suratMaster->kecamatan)">
                            <input id="q1" type="radio" name="kecamatan" class="form-check-input" type="radio"
                                value="1" @checked($suratMaster->kecamatan) autocomplete="off">Ya
                        </label>
                        <label id="n2"
                            class="tipe btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-2 form-check-label @active(!$suratMaster->kecamatan)">
                            <input id="q2" type="radio" name="kecamatan" class="form-check-input" type="radio"
                                value="0" @checked(!$suratMaster->kecamatan) autocomplete="off">Tidak
                        </label>
                    </div>
                </div>
            @endif

            <div class="form-group">
                <label class="col-sm-3 control-label" for="mandiri">Sediakan di Layanan Mandiri</label>
                <div class="btn-group col-xs-12 col-sm-8" data-toggle="buttons">
                    <label id="m1"
                        class="tipe btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-2 form-check-label @active($suratMaster->mandiri)">
                        <input id="g1" type="radio" name="mandiri" class="form-check-input" type="radio"
                            value="1" @checked($suratMaster->mandiri) autocomplete="off">Ya
                    </label>
                    <label id="m2"
                        class="tipe btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-2 form-check-label @active(!$suratMaster->mandiri)">
                        <input id="g2" type="radio" name="mandiri" class="form-check-input" type="radio"
                            value="0" @checked(!$suratMaster->mandiri) autocomplete="off">Tidak
                    </label>
                </div>
            </div>

            <div class="form-group" id="syarat"
                {{ jecho($suratMaster->mandiri, false, 'style="display:none;"') }}>
                <label class="col-sm-3 control-label" for="mandiri">Syarat Surat</label>
                <div class="col-sm-7">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="tabeldata" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="checkall" /></th>
                                    <th>NO</th>
                                    <th>NAMA DOKUMEN</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <script>
            $('#kode_surat').select2({
                ajax: {
                    url: SITE_URL + 'surat_master/apisurat',
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
                    $(this).data('placeholder');
                },
                minimumInputLength: 0,
                allowClear: true,
                escapeMarkup: function(markup) {
                    return markup;
                },
            });

            var TableData = $('#tabeldata').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                bPaginate: false,
                ajax: "{{ route('surat_master.syaratsuratdatatables', $suratMaster->id) }}",
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
