@include('admin.layouts.components.asset_datatables')

@extends('admin.layouts.index')

@section('title')
    <h1>
        PRODUK
        <small>{{ $aksi }} Data</small>
    </h1>
@endsection

@section('breadcrumb')
    <li><a href="{{ site_url('lapak_admin/produk') }}"></i> Produk</a></li>
    <li class="active">{{ $aksi }} Data</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <form id="validasi" action="{{ $form_action }}" method="POST" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-9">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <a href="{{ site_url('lapak_admin/produk') }}" class="btn btn-social  btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left"></i> Kembali Ke Daftar Data Produk</a>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label class="control-label" for="kategori">Nama Pelapak</label>
                            <select class="form-control input-sm select2 required" name="id_pelapak">
                                <option value="">Pilih Nama Pelapak</option>
                                @foreach ($pelapak as $pel)
                                    <option value="{{ $pel->id }}" @selected($main->id_pelapak == $pel->id)>{{ $pel->nik . ' - ' . $pel->pelapak }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="nama">Nama Produk</label>
                            <input
                                name="nama"
                                class="form-control input-sm strip_tags judul required"
                                type="text"
                                placeholder="Nama Produk"
                                minlength="3"
                                maxlength="100"
                                value="{{ e($main->nama) }}"
                            />
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="kategori">Kategori Produk</label>
                                    <select class="form-control input-sm select2 required" name="id_produk_kategori">
                                        <option value="">Pilih Kategori Produk</option>
                                        @foreach ($kategori as $kat)
                                            <option value="{{ $kat->id }}" @selected($main->id_produk_kategori == $kat->id)>{{ $kat->kategori }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="harga">Harga Produk</label>
                                    <div class="input-group">
                                        <span class="input-group-addon input-sm">Rp.</span>
                                        <input
                                            id="harga"
                                            name="harga"
                                            onkeyup="cek_nominal();"
                                            class="form-control input-sm number required"
                                            type="number"
                                            placeholder="Harga Produk"
                                            style="text-align:right;"
                                            min="100"
                                            max="2000000000"
                                            step="100"
                                            value="{{ $main->harga }}"
                                        />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="control-label" for="satuan">Satuan Produk</label>
                                    <select class="form-control input-sm select2-tags required" name="satuan">
                                        <option value="">Pilih Satuan Produk</option>
                                        @foreach ($satuan as $sat)
                                            <option value="{{ $sat }}" @selected($main->satuan == $sat)>{{ $sat }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <label for="nama">Potongan Harga Produk</label>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <select id="tipe_potongan" name="tipe_potongan" class="form-control input-sm required">
                                        <option value="1" @selected($main->tipe_potongan == 1)>Persen (%)</option>
                                        <option value="2" @selected($main->tipe_potongan == 2)>Nominal (Rp.)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6" id="tampil-persen" {{ jecho($main->tipe_potongan, 2, 'style="display:none;"') }}>
                                <div class="form-group">
                                    <div class="input-group">
                                        <input
                                            type="number"
                                            class="form-control input-sm number required"
                                            {{ $main->tipe_potongan == 1 ? '' : 'disabled' }}
                                            id="persen"
                                            name="persen"
                                            onkeyup="cek_persen();"
                                            placeholder="Potongan Persen (%)"
                                            style="text-align:right;"
                                            min="0"
                                            max="100"
                                            step="1"
                                            value="{{ $main->potongan ?? 0 }}"
                                        />
                                        <span class="input-group-addon input-sm">%</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6" id="tampil-nominal" {{ jecho($main->tipe_potongan, 1, 'style="display:none;"') }}>
                                <div class="form-group">
                                    <div class="input-group">
                                        <span class="input-group-addon input-sm ">Rp.</span>
                                        <input
                                            type="number"
                                            class="form-control input-sm number required"
                                            {{ $main->tipe_potongan == 2 ? '' : 'disabled' }}
                                            id="nominal"
                                            name="nominal"
                                            onkeyup="cek_nominal();"
                                            placeholder="Potongan Nominal (Rp.)"
                                            style="text-align:right;"
                                            min="0"
                                            max="99999999999"
                                            step="10"
                                            value="{{ $main->potongan ?? 0 }}"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label" for="kode_desa">Deskripsi Produk</label>
                            <textarea name="deskripsi" class="form-control input-sm required" rows="5">{{ e($main->deskripsi) }}</textarea>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="reset" class="btn btn-social  btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
                        <button type="submit" class="btn btn-social  btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Foto Produk</h3>
                        <div class="box-tools">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <center>
                            @php $foto = json_decode($main->foto, null); @endphp
                            @for ($i = 0; $i < setting('banyak_foto_tiap_produk'); $i++)
                                <b>Foto {{ $i == 0 ? 'Utama' : 'Tambahan ' . $i }}</b>
                                @php $ii = $i + 1; @endphp
                                <div class="form-group preview-img">
                                    @if (is_file(LOKASI_PRODUK . $foto[$i]))
                                        <img class="img-responsive" src="{{ to_base64(LOKASI_PRODUK . $foto[$i]) }}" alt="Foto Produk">
                                    @else
                                        <img class="img-responsive" src="{{ to_base64('assets/images/404-image-not-found.jpg') }}" alt="Foto Produk" />
                                    @endif
                                    <div class="input-group input-group-sm">
                                        <input type="hidden" name="old_foto_{{ $ii }}" value="{{ $foto[$i] }}">
                                        <input type="text" class="form-control file-path" readonly>
                                        <input type="file" class="hidden file-input" name="foto_{{ $ii }}" accept=".gif,.jpg,.jpeg,.png">
                                        <span class="input-group-btn">
                                            <button type="button" class="btn btn-info file-browser"><i class="fa fa-search"></i></button>
                                        </span>
                                        <span class="input-group-addon" style="background-color: red; border: 1px solid #ccc;">
                                            <input type="checkbox" title="Centang Untuk Hapus Foto" name="hapus_foto_{{ $ii }}" value="hapus">
                                        </span>
                                    </div>
                                </div>
                                <hr />
                            @endfor
                        </center>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@push('scripts')
    @include('admin.layouts.components.validasi_form')
    <script type="text/javascript">
        /**
         * Tipe Potongan
         * 1 = Persen
         * 2 = Nominal
         */
        $(document).ready(function() {

            $('#tipe_potongan').change();

            $('#tipe_potongan').on('change', function() {
                if (this.value == 2) {
                    $('#tampil-persen').hide();
                    $('#tampil-nominal').show();
                    $('#nominal').addClass('required');
                    $('#persen').removeClass('required');
                    $('#nominal').removeAttr("disabled");
                    cek_nominal();
                } else {
                    $('#tampil-nominal').hide();
                    $('#tampil-persen').show();
                    $('#persen').addClass('required');
                    $('#nominal').removeClass('required');
                    $('#persen').removeAttr("disabled");
                    cek_persen();
                }
            });
        });

        function cek_persen() {
            if (parseInt($('#persen').val()) > 100) {
                $('#persen').val(100);
            }
        }

        function cek_nominal() {
            if (parseInt($('#nominal').val()) > parseInt($('#harga').val())) {
                $('#nominal').val($('#harga').val());
            }
        }
    </script>
@endpush
