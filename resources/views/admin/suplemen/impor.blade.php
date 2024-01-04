@extends('admin.layouts.index')

@section('title')
    <h1>
        Data Suplemen
        <small>{{ $action }} Data</small>
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ ci_route('suplemen') }}">Daftar Data Suplemen</a></li>
    <li class="active">{{ $action }} Data</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="box box-info">
        <div class="box-header with-border">
            <a href="{{ ci_route('suplemen.rincian', $suplemen->id) }}" class="btn btn-social btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left"></i> Kembali Ke Daftar Terdata Suplemen</a>
        </div>
        {!! form_open($form_action, 'class="form-horizontal" id="validasi" enctype="multipart/form-data"') !!}
        <div class="box-body">
            <div class="col-sm-12 form-group">
                <label for="file" class="control-label">File Data Suplemen : </label>
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control" id="file_path" name="userfile" required>
                    <input type="file" class="hidden" id="file" name="userfile" accept=".xls,.xlsx,.xlsm">
                    <span class="input-group-btn">
                        <button type="button" class="btn btn-info" id="file_browser"><i class="fa fa-search"></i> Browse</button>
                    </span>
                </div>
                <input type="hidden" id="id_suplemen" name="id_suplemen" value="{{ $suplemen->id }}">
                <label>Data yang dimasukkan adalah data yang baru</label>
                <br />
                <br />
                <a href="<?= base_url('assets/import/format_impor_suplemen.xlsx') ?>" class="btn btn-social  bg-purple btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block text-center"><i class="fa fa-file-excel-o"></i> Contoh Format Impor Data Suplemen</a>
            </div>
            @if ($pesan_impor = session('notif'))
                <hr>
                <table>
                    <tr>
                        <td>
                            <dl class="dl-horizontal">
                                <dt>Jumlah Data Gagal : </dt>
                                <dd>{{ $pesan_impor['gagal'] }}</dd>
                            </dl>
                        </td>
                    </tr>
                    @if ($pesan_impor['pesan'])
                        <tr>
                            <td>
                                <dl class="dl-horizontal">
                                    <dt>Rincian : </dt>
                                    <dd>{!! $pesan_impor['pesan'] !!}</dd>
                                </dl>
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <td>
                            <dl class="dl-horizontal">
                                <dt>Total Data Berhasil :</dt>
                                <dd>{{ $pesan_impor['sukses'] }}</dd>
                            </dl>
                        </td>
                    </tr>
                </table>
            @endif
        </div>
        <div class="box-footer">
            <button type="reset" class="btn btn-social btn-danger btn-sm" onclick="reset_form($(this).val());"><i class="fa fa-times"></i> Batal</button>
            <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
        </div>
        </form>
    </div>
@endsection
