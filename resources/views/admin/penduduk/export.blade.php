@extends('admin.layouts.index')

@section('title')
<h1>
    Ekspor Data Kependudukan
</h1>
@endsection

@section('breadcrumb')
<li class="active">Ekspor Data Kependudukan</li>
@endsection

@section('content')

<div class="box box-info">
    <div class="box-header with-border">
        <a href="{{ route('penduduk') }}" class="btn btn-social btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" title="Kembali Ke Data Penduduk"><i class="fa fa-arrow-circle-o-left"></i> Kembali Ke Data Penduduk</a>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-8">
                <table class="table table-striped table-hover">
                    <tr>
                        <td class="col-sm-10">Ekspor Data Penduduk (Format .xlsx untuk di impor ke database SID melalui menu Impor Database)</td>
                        <td class="col-sm-2">
                            <a href="{{ route('penduduk.export_excel') }}" class="btn btn-social btn-info btn-sm"><i class="fa fa-download"></i> Unduh</a>
                        </td>
                    </tr>
                    <tr>
                        <td>Ekspor Data Dasar Kependudukan (.sid)</td>
                        <td>
                            <a href="{{ route('penduduk.export_dasar') }}" class="btn btn-social btn-info btn-sm"><i class="fa fa-download"></i> Unduh</a>
                        </td>
                    </tr>
                    <tr>
                        <td>Ekspor Data CSV (.csv)</td>
                        <td>
                            <a href="{{ route('penduduk.export_csv') }}" class="btn btn-social btn-info btn-sm"><i class="fa fa-download"></i> Unduh</a>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection