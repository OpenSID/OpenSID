@extends('admin.layouts.index')
@include('admin.layouts.components.asset_validasi')
@include('admin.layouts.components.datetime_picker')
@section('title')
    <h1>
        Form Ekspedisi
    </h1>
@endsection

@section('breadcrumb')
    <li><a href="{{ site_url('ekspedisi') }}"> Buku Ekspedisi</a></li>
    <li class="active">Form Ekspedisi</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="box box-info">
        {!! form_open_multipart($form_action, 'class="form-horizontal" id="validasi"') !!}
        <div class="box-header with-border">
            <a href="{{ site_url('ekspedisi') }}" class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Buku Ekspedisi">
                <i class="fa fa-arrow-circle-left "></i>Kembali Ke Buku Ekspedisi
            </a>
        </div>
        <div class="box-body">
            <input type="hidden" id="nomor_urut_lama" name="nomor_urut_lama" value="{{ $surat_keluar['nomor_urut'] }}">
            <input type="hidden" id="url_remote" name="url_remote" value="{{ site_url('surat_keluar/nomor_surat_duplikat') }}">
            <div class="form-group">
                <label class="col-sm-3 control-label" for="nomor_urut">Nomor Urut</label>
                <div class="col-sm-8">
                    <input
                        id="nomor_urut"
                        name="nomor_urut"
                        class="form-control input-sm number required"
                        type="text"
                        placeholder="Nomor Urut"
                        readonly
                        value="{{ $surat_keluar['nomor_urut'] }}"
                    ></input>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label" for="kode_surat">Kode/Klasifikasi Surat</label>
                <div class="col-sm-8">
                    <select class="form-control input-sm select2-tags required" id="kode_surat" disabled name="kode_surat" style="width: 100%;">
                        <option value=''>-- Pilih Kode/Klasifikasi Surat --</option>
                        @foreach ($klasifikasi as $item)
                            <option value="{{ $item['kode'] }}" {{ selected($item['kode'], $surat_keluar['kode_surat']) }}>
                                {{ $item['kode'] . ' - ' . $item['nama'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label" for="nomor_surat">Nomor Surat</label>
                <div class="col-sm-8">
                    <input
                        id="nomor_surat"
                        name="nomor_surat"
                        maxlength="35"
                        class="form-control input-sm required"
                        type="text"
                        placeholder="Nomor Surat"
                        readonly
                        value="{{ $surat_keluar['nomor_surat'] }}"
                    ></input>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label" for="tanggal_surat">Tanggal Surat</label>
                <div class="col-sm-3">
                    <div class="input-group input-group-sm date">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input class="form-control input-sm pull-right required" id="tgl_2" name="tanggal_surat" type="text" readonly value="{{ tgl_indo_out($surat_keluar['tanggal_surat']) }}">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label" for="pengirim">Tujuan</label>
                <div class="col-sm-8">
                    <input
                        id="tujuan"
                        name="tujuan"
                        class="form-control input-sm required"
                        type="text"
                        placeholder="Tujuan"
                        readonly
                        value="{{ $surat_keluar['tujuan'] }}"
                    ></input>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label" for="disposisi_kepada">Isi Singkat/Perihal</label>
                <div class="col-sm-8">
                    <textarea
                        id="isi_singkat"
                        name="isi_singkat"
                        class="form-control input-sm required"
                        placeholder="Isi Singkat/Perihal"
                        readonly
                        rows="3"
                        style="resize:none;"
                    >{{ $surat_keluar['isi_singkat'] }}</textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label" for="tanggal_pengiriman">Tanggal Pengiriman</label>
                <div class="col-sm-3">
                    <div class="input-group input-group-sm date">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input class="form-control input-sm pull-right tgl required" name="tanggal_pengiriman" type="text" value="{{ tgl_indo_out($surat_keluar['tanggal_pengiriman']) }}">
                    </div>
                </div>
            </div>
            @if (null !== $surat_keluar['tanda_terima'] && $surat_keluar['tanda_terima'] != '.')
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="tanda_terima"></label>
                    <div class="col-sm-8">
                        <div class="mailbox-attachment-info">
                            <a href="{{ site_url('/surat_keluar/unduh_berkas_scan/' . $surat_keluar['id']) }}" title=""><i class="fa fa-paperclip"></i>
                                {{ $surat_keluar['tanda_terima'] }}
                            </a>
                            <p><label class="control-label"><input type="checkbox" name="gambar_hapus" value="{{ $surat_keluar['tanda_terima'] }}" /> Hapus Berkas Lama</label></p>
                        </div>
                    </div>
                </div>
            @endif
            <div class="form-group">
                <label class="col-sm-3 control-label" for="kode_pos">Berkas Scan Tanda Terima</label>
                <div class="col-sm-6">
                    <div class="input-group input-group-sm col-sm-12">
                        <input type="text" class="form-control" id="file_path">
                        <input type="file" class="hidden" id="file" name="tanda_terima">
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-info" id="file_browser"><i class="fa fa-search"></i> Browse</button>
                        </span>
                    </div>
                    <span class="help-block"><code>(Kosongkan jika tidak ingin mengubah berkas)</code></span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label" for="keterangan">Keterangan</label>
                <div class="col-sm-8">
                    <textarea name="keterangan" class="form-control input-sm" placeholder="Keterangan" rows="3" style="resize:none;">{{ $surat_keluar['keterangan'] }}</textarea>
                </div>
            </div>
        </div>
        <div class="box-footer">
            <button type="reset" class="btn btn-social btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
            <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i>
                Simpan</button>
        </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/custom-select2.js') }}"></script>
    <script>
        $('#tipe').on('change', function() {
            if (this.value == 1) {
                $('#d-dokumen').show();
                $('#d-url').hide();
                $("#file_path").addClass("required");
                $("#url").removeClass("required");
            } else {
                $('#d-dokumen').hide();
                $('#d-url').show();
                $("#file_path").removeClass("required");
                $("#url").addClass("required");
            }
        });
    </script>
@endpush
