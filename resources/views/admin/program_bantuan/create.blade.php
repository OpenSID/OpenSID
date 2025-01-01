@include('admin.pengaturan_surat.asset_tinymce')
@include('admin.layouts.components.asset_datatables')
@include('admin.layouts.components.jquery_ui')
@include('admin.layouts.components.asset_validasi')
@include('admin.layouts.components.datetime_picker')

@extends('admin.layouts.index')

@section('title')
    <h1>Tambah Program Bantuan</h1>
@endsection

@section('breadcrumb')
    <li><a href="{{ site_url('program_bantuan') }}"> Daftar Program Bantuan</a></li>
    <li class="active">Tambah Program Bantuan</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="box box-info">
        <div class="box-header with-border">
            <a href="{{ site_url('program_bantuan') }}" class="btn btn-social btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Daftar Program Bantuan"><i class="fa fa-arrow-circle-o-left"></i> Kembali Ke Daftar
                Program Bantuan</a>
        </div>
        <form id="validasi" action="{{ $form_action }}" method="POST" enctype="multipart/form-data" class="form-horizontal">
            <div class="box-body">
                @php $cid = $_REQUEST['cid']; @endphp
                <div class="form-group">
                    <label class="col-sm-3 control-label">Sasaran Program</label>
                    <div class="col-sm-3">
                        <select class="form-control input-sm required" name="cid" id="cid">
                            {{-- cek cid ini di view tampil apa --}}
                            <option value="">Pilih Sasaran Program {{ $cid }}</option>
                            @foreach ($sasaran as $key => $item)
                                <option value="{{ $key }}" @selected($key == $cid)>{{ $item }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group" style="display: none;" id="penerima">
                    <label class="col-sm-3 control-label" for="penerima">Penerima</label>
                    <div class="col-sm-9">
                        <select class="form-control input-sm select2 required" name="kk_level[]" multiple="multiple">
                            @foreach ($kk_level as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-3" for="nama">Nama Program</label>
                    <div class="col-sm-8">
                        <input name="nama" class="form-control input-sm nomor_sk required" maxlength="100" placeholder="Nama Program" type="text"></input>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="ndesc">Keterangan</label>
                    <div class="col-sm-8">
                        <textarea id="ndesc" name="ndesc" class="form-control input-sm required" placeholder="Isi Keterangan" rows="8"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="asaldana">Asal Dana</label>
                    <div class="col-sm-3">
                        <select class="form-control input-sm required" name="asaldana" id="asaldana">
                            <option value="">Asal Dana</option>
                            @foreach ($asaldana as $ad)
                                <option value="{{ $ad }}">{{ $ad }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="tgl_post">Rentang Waktu Program</label>
                    <div class="col-sm-4">
                        <div class="input-group input-group-sm date">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input class="form-control input-sm pull-right required" id="tgl_mulai" name="sdate" placeholder="Tgl. Mulai" type="text">
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="input-group input-group-sm date">
                            <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <input class="form-control input-sm pull-right required" id="tgl_akhir" name="edate" placeholder="Tgl. Akhir" type="text">
                        </div>
                    </div>
                </div>
            </div>
            <div class='box-footer'>
                <button type='reset' class='btn btn-social btn-danger btn-sm'><i class='fa fa-times'></i> Batal</button>
                <button type='submit' class='btn btn-social btn-info btn-sm pull-right confirm'><i class='fa fa-check'></i>
                    Simpan</button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        $('#cid').change(function() {
            var cid = $(this).val();
            if (cid == 2) {
                $('#penerima').show();
                $('[name="kk_level[]"]').addClass('required');
            } else {
                $('#penerima').hide();
                $('[name="kk_level[]"]').removeClass('required');
            }
        });
    </script>
@endpush
