@extends('layanan_mandiri.layouts.index')

@section('content')
    <style>
        .row {
            margin-left: -5px;
            margin-right: -5px;
        }
    </style>
    <div class="box box-solid">
        <div class="box-header with-border bg-aqua">
            <h4 class="box-title">PRODUK</h4>
        </div>
        <div class="box-body box-line">
            <div class="form-group">
                <a href="{{ site_url('layanan-mandiri/produk') }}" class="btn bg-aqua btn-social"><i class="fa fa-arrow-circle-left"></i>Kembali ke Daftar Produk</a>
            </div>
        </div>
        <div class="box-body box-line">
            <h4><b>TAMBAH PRODUK</b></h4>
            @include('layanan_mandiri.layouts.components.notifikasi')
            @if ($notifikasi)
                <div class="callout callout-{{ $notifikasi['status'] }}">
                    <h4>Informasi</h4>
                    <p>{{ $notifikasi['pesan'] }}</p>
                </div>
            @endif
        </div>
        @if (!$batas)
            <form id="validasi" action="{{ $form_action }}" method="POST" enctype="multipart/form-data">
                <div class="box-body">
                    <div class="form-group">
                        <label class="control-label" for="nama">Nama Produk</label>
                        <input
                            name="nama"
                            class="form-control strip_tags required"
                            type="text"
                            placeholder="Nama Produk"
                            minlength="3"
                            maxlength="100"
                            value="{{ htmlentities($produk->nama) }}"
                        />
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="kategori">Kategori Produk</label>
                                <select class="form-control select2 required" name="id_produk_kategori">
                                    <option value="">Pilih Kategori Produk</option>
                                    @foreach ($kategori as $kat)
                                        <option value="{{ $kat->id }}" {{ $produk->id_produk_kategori == $kat->id ? 'selected' : '' }}>{{ $kat->kategori }}</option>
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
                                    <span class="input-group-addon">Rp.</span>
                                    <input
                                        id="harga"
                                        name="harga"
                                        onkeyup="cek_nominal();"
                                        class="form-control number required"
                                        type="number"
                                        placeholder="Harga Produk"
                                        style="text-align:right;"
                                        min="100"
                                        max="2000000000"
                                        step="100"
                                        value="{{ $produk->harga }}"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label" for="satuan">Satuan Produk</label>
                                <select class="form-control select2-tags required" name="satuan">
                                    <option value="">Pilih Satuan Produk</option>
                                    @foreach ($satuan as $sat)
                                        <option value="{{ $sat }}" {{ $produk->satuan == $sat ? 'selected' : '' }}>{{ $sat }}</option>
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
                                <select id="tipe_potongan" name="tipe_potongan" class="form-control required">
                                    <option value="1" {{ $produk->tipe_potongan == 1 ? 'selected' : '' }}>Persen (%)</option>
                                    <option value="2" {{ $produk->tipe_potongan == 2 ? 'selected' : '' }}>Nominal (Rp.)</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6" id="tampil-persen" @if ($produk->tipe_potongan == 2) style="display:none;" @endif>
                            <div class="form-group">
                                <div class="input-group">
                                    <input
                                        type="number"
                                        class="form-control number required"
                                        {{ $produk->tipe_potongan == 1 || $produk->tipe_potongan == null ? '' : 'disabled' }}
                                        id="persen"
                                        name="persen"
                                        onkeyup="cek_persen();"
                                        placeholder="Potongan Persen (%)"
                                        style="text-align:right;"
                                        min="0"
                                        max="100"
                                        step="1"
                                        value="{{ $produk->potongan ?? 0 }}"
                                    />
                                    <span class="input-group-addon">%</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6" id="tampil-nominal" @if ($produk->tipe_potongan == 1 || $produk->tipe_potongan == null) style="display:none;" @endif>
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon">Rp.</span>
                                    <input
                                        type="number"
                                        class="form-control number required"
                                        {{ $produk->tipe_potongan == 2 ? '' : 'disabled' }}
                                        id="nominal"
                                        name="nominal"
                                        onkeyup="cek_nominal();"
                                        placeholder="Potongan Nominal (Rp.)"
                                        style="text-align:right;"
                                        min="0"
                                        max="99999999999"
                                        step="10"
                                        value="{{ $produk->potongan ?? 0 }}"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label" for="kode_desa">Deskripsi Produk</label>
                        <textarea name="deskripsi" class="form-control required" rows="5">{{ htmlentities($produk->deskripsi) }}</textarea>
                    </div>
                    <hr>
                    <div class="form-group">
                        <?php $foto = json_decode($produk->foto, null); ?>
                        <div class="row">
                            @php
                                $banyak_foto = setting('banyak_foto_tiap_produk') ?? 3;
                                $col = 12 / $banyak_foto;
                            @endphp

                            @for ($i = 0; $i < $banyak_foto; $i++)
                                @php $ii = $i + 1; @endphp
                                <div class="col-sm-{{ $col }}">
                                    <center>
                                        <div class="form-group">
                                            <b>Foto {{ $i == 0 ? 'Utama' : 'Tambahan' }}</b>
                                            @if (is_file(LOKASI_PRODUK . $foto[$i]))
                                                <img class="img-responsive" src="{{ to_base64(LOKASI_PRODUK . $foto[$i]) }}" alt="Foto Produk">
                                            @else
                                                <img class="img-responsive" src="{{ to_base64('assets/images/404-image-not-found.jpg') }}" alt="Foto Produk" />
                                            @endif
                                            <div class="input-group input-group-sm">
                                                <input type="hidden" name="old_foto_{{ $ii }}" value="{{ $foto[$i] }}">
                                                <input type="text" class="form-control" id="file_path{{ $ii }}">
                                                <input type="file" class="hidden" id="file{{ $ii }}" name="foto_{{ $ii }}" accept=".gif,.jpg,.jpeg,.png">
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn btn-info " id="file_browser{{ $ii }}"><i class="fa fa-search"></i></button>
                                                </span>
                                                <span class="input-group-addon" style="background-color: red; border: 1px solid #ccc;">
                                                    <input type="checkbox" title="Centang Untuk Hapus Foto" name="hapus_foto_{{ $ii }}" value="hapus">
                                                </span>
                                            </div>
                                        </div>
                                    </center>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
                @if ($verifikasi)
                    <div class="box-footer text-center">
                        <button type="reset" class="btn btn-social btn-danger"><i class="fa fa-times"></i>Batal</button>
                        <button type="submit" class="btn btn-social btn-success"><i class="fa fa-save"></i>Kirim</button>
                    </div>
                @endif
            </form>
        @endif
    </div>
@endsection

@push('scripts')
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
