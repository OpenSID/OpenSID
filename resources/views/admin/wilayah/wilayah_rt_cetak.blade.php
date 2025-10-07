@extends('admin.layouts.print_layout')

@section('title', 'Data Wilayah')

@section('styles')
    <!-- TODO: Pindahkan ke external css -->
    <style>
        .textx {
            mso-number-format: "\@";
        }

        td,
        th {
            font-size: 6.5pt;
        }
    </style>
@endsection

@section('header')
    <div class="header" align="center">
        <label align="left">{{ get_identitas() }}</label>
        <h3>DATA WILAYAH ADMINISTRASI</h3>
        <h4>RT {{ strtoupper(setting('sebutan_dusun')) }} {{ strtoupper($dusun) }}</h4>
    </div>
    <br>
@endsection

@section('content')
    <table class="border thick">
        <thead>
            <tr class="border thick">
                <th width="30">No</th>
                <th width="50">RT</th>
                <th width="100">NIK Ketua RT</th>
                <th width="100">Nama Ketua RT</th>
                <th width="50">Jumlah KK</th>
                <th width="50">L+P</th>
                <th width="50">L</th>
                <th width="50">P</th>
            </tr>
        </thead>
        <tbody>
            @php
                $no = 1;
            @endphp
            @foreach ($rts as $rt)
                <tr>
                    <td align="center">{{ $no++ }}</td>
                    <td align="center">{{ strtoupper($rt->rt) }}</td>
                    <td>{{ $rt->kepala->nik ?? '' }}</td>
                    <td>{{ $rt->kepala->nama ?? '' }}</td>
                    <td align="right">{{ $rt->keluarga_aktif_count }}</td>
                    <td align="right">{{ $rt->penduduk_pria_count + $rt->penduduk_wanita_count }}</td>
                    <td align="right">{{ $rt->penduduk_pria_count }}</td>
                    <td align="right">{{ $rt->penduduk_wanita_count }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color:#BDD498;font-weight:bold;">
                <td colspan="4" align="left"><label>TOTAL</label></td>
                <td align="right">{{ $rts->sum('keluarga_aktif_count') }}</td>
                <td align="right">{{ $rts->sum('penduduk_pria_count') + $rts->sum('penduduk_wanita_count') }}</td>
                <td align="right">{{ $rts->sum('penduduk_pria_count') }}</td>
                <td align="right">{{ $rts->sum('penduduk_wanita_count') }}</td>
            </tr>
        </tfoot>
    </table>
    <label>Tanggal cetak : &nbsp; </label>{{ tgl_indo(date('Y m d')) }}
@endsection
