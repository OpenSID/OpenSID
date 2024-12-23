<div class="modal-body">
    @if ($main->foto)
        <div class="row">
            <div class="col-md-12">
                <div id="foto-produk" class="carousel slide" data-ride="carousel">
                    <div class="carousel-inner">
                        @php $foto = json_decode($main->foto, null); @endphp
                        @for ($i = 0; $i < $ci->setting->banyak_foto_tiap_produk; $i++)
                            @if ($foto[$i])
                                <div class="item {{ jecho($i, 0, 'active') }}">
                                    <div class="image-container">
                                        <img src="{{ is_file(LOKASI_PRODUK . $foto[$i]) ? to_base64(LOKASI_PRODUK . $foto[$i]) : to_base64('assets/images/404-image-not-found.jpg') }}" alt="Foto {{ $i + 1 }}">
                                    </div>
                                    <div class="carousel-caption">
                                        Foto {{ $i == 0 ? 'Utama' : 'Tambahan' }}
                                    </div>
                                </div>
                            @endif
                        @endfor
                    </div>
                    <a class="left carousel-control" href="#foto-produk" data-slide="prev">
                        <span class="fa fa-angle-left"></span>
                    </a>
                    <a class="right carousel-control" href="#foto-produk" data-slide="next">
                        <span class="fa fa-angle-right"></span>
                    </a>
                </div>
            </div>
        </div>
        <hr />
    @endif
    <div class="form-group">
        <label class="control-label" for="pelapak">Nama Pelapak</label>
        <input name="pelapak" class="form-control input-sm" type="text" value="{{ $main->pelapak }}" disabled />
    </div>
    <div class="form-group">
        <label class="control-label" for="nama">Nama Produk</label>
        <input name="nama" class="form-control input-sm" type="text" value="{{ e($main->nama) }}" disabled />
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label" for="kategori">Kategori Produk</label>
                <input name="kategori" class="form-control input-sm" type="text" value="{{ $main->kategori }}" disabled />
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label class="control-label" for="harga">Harga Produk</label>
                <div class="input-group">
                    <span class="input-group-addon input-sm">Rp.</span>
                    <input name="harga" class="form-control input-sm" type="text" style="text-align:right;" value="{{ str_replace('Rp.', '', rupiah($main->harga)) }}" disabled />
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label class="control-label" for="satuan">Satuan Produk</label>
                <input name="satuan" class="form-control input-sm" type="text" value="{{ $main->satuan }}" disabled />
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label class="control-label" for="potongan">Potongan Harga</label>
                @if ($main->tipe_potongan == 1)
                    <div class="input-group">
                        <input name="potongan" class="form-control input-sm" type="text" style="text-align:right;" value="{{ $main->potongan }}" disabled />
                        <span class="input-group-addon input-sm">%</span>
                    </div>
                @else
                    <div class="input-group">
                        <span class="input-group-addon input-sm">Rp.</span>
                        <input name="potongan" class="form-control input-sm" type="text" style="text-align:right;" value="{{ str_replace('Rp.', '', rupiah($main->potongan)) }}" disabled />
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="form-group">
        <label class="control-label" for="kode_desa">Deskripsi Produk</label>
        <textarea name="deskripsi" class="form-control input-sm" rows="5" disabled>{{ e($main->deskripsi) }}</textarea>
    </div>
</div>
<div class="modal-footer">
    <button type="reset" class="btn btn-social  btn-danger btn-sm pull-left" data-dismiss="modal"><i class="fa fa-sign-out"></i> Tutup</button>
    <a href="{{ site_url("lapak_admin/produk_form/{$main->id}") }}" class="btn btn-social  bg-orange btn-sm"><i class="fa fa-edit"></i> Ubah</a>
</div>
