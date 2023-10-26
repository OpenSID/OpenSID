<div class="box-header with-border">
    <a href="{{ route('surat_master') }}"
        class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
        <i class="fa fa-arrow-circle-left"></i>Kembali ke Daftar Surat
    </a>
    @if (in_array($suratMaster->jenis, [3, 4]))
        @if (super_admin() && $suratMaster->jenis == 3)
            <a href="#" data-href="{{route('surat_master.restore_surat_bawaan', $suratMaster->url_surat)}}" title="Restore Surat Bawaan/Sistem" data-toggle="modal" data-target="#confirm-restore"
                class="btn btn-social btn-success btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
                <i class="fa fa-refresh"></i>Restore Surat Bawaan/Sistem
            </a>
        @endif

        @if (setting('tte'))
            <br /><br />
            <div class="alert alert-info alert-dismissible">
                <h4><i class="icon fa fa-info"></i> Info !</h4>
                Jika surat ingin dikirim ke kecamatan, letakan kode [qr_camat] pada tempat yang ingin ditempelkan QRCode
                Kecamatan.
            </div>
        @endif

        @if ($data)
            <div class="row">
                <label for="penduduk_berulang" class="col-sm-2">Sumber Penduduk Berulang</label>
                <div class="btn-group col-xs-12 col-sm-10" data-toggle="buttons" style="margin: 0 0 5px 0">
                    <label class="tipe btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-3 form-check-label @active(($suratMaster->sumber_penduduk_berulang ?? 0))">
                        <input type="radio" name="sumber_penduduk_berulang" class="form-check-input" value="1" @checked(($suratMaster->sumber_penduduk_berulang ?? 0)) autocomplete="off">Ya
                    </label>
                    <label class="tipe btn btn-info btn-sm col-xs-12 col-sm-6 col-lg-3 form-check-label @active(! ($suratMaster->sumber_penduduk_berulang ?? 0))">
                        <input type="radio" name="sumber_penduduk_berulang" class="form-check-input" value="0" @checked(! ($suratMaster->sumber_penduduk_berulang ?? 0)) autocomplete="off">Tidak
                    </label>
                </div>
            </div>
            <hr>
            <a id="btn-new-tab" class="btn btn-social btn-primary btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-plus"></i>Tambah Bagian Form</a>
        @endif
    @endif
</div>