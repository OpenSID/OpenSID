@extends('admin.layouts.print_layout')

@section('title', 'BUKU ' . strtoupper(setting('sebutan_pemerintah_desa')))

@section('styles')
    <!-- TODO: Pindahkan ke external css -->
    <style>
        .textx {
            mso-number-format: "\@";
        }

        td.bold {
            font-weight: bold;
        }

        td.underline {
            border-bottom: solid 1px;
        }
    </style>
@endsection

@section('header')
    @include('admin.layouts.components.print_header', [
        'document_title' => 'BUKU ' . strtoupper(setting('sebutan_pemerintah_desa')),
        'model_code' => 'Model A.4'
    ])
@endsection

@section('content')
    <table class="border thick">
        <thead>
            <tr class="border thick">
                <th width="3%">NO</th>
                <th width="10%">NAMA</th>
                <th>{{ setting('sebutan_nip_desa') }}</th>
                <th>NIP</th>
                <th>JENIS KELAMIN</th>
                <th>TEMPAT TANGGAL LAHIR</th>
                <th>AGAMA</th>
                <th width="5%">PANGKAT/ GOLONGAN</th>
                <th>JABATAN</th>
                <th>PENDIDIKAN TERAKHIR</th>
                <th width="10%">NOMOR DAN TANGGAL KEPUTUSAN PENGANGKATAN</th>
                <th width="10%">NOMOR DAN TANGGAL KEPUTUSAN PEMBERHENTIAN</th>
                <th width="7%">KETERANGAN (Periode/Masa Jabatan)</th>
            </tr>
            <tr>
                <th>1</th>
                <th>2</th>
                <th>3</th>
                <th>4</th>
                <th>5</th>
                <th>6</th>
                <th>7</th>
                <th>8</th>
                <th>9</th>
                <th>10</th>
                <th>11</th>
                <th>12</th>
                <th>13</th>
        </thead>
        <tbody>
            @foreach ($main as $key => $data)
                <tr>
                    <td class="padat">{{ $key + 1 }}</td>
                    <td>{{ $data->pamong_nama }}</td>
                    <td class="textx">{{ $data->pamong_niap }}</td>
                    <td class="textx">{{ $data->pamong_nip }}</td>
                    <td>{{ $data->pamong_sex }}</td>
                    <td>{{ $data->pamong_tempatlahir . ', ' . tgl_indo_out($data->pamong_tanggallahir) }}</td>
                    <td>{{ $data->pamong_agama }}</td>
                    <td>{{ $data->pamong_pangkat }}</td>
                    <td>{{ $data->jabatan->nama }}</td>
                    <td>{{ App\Enums\PendidikanKKEnum::valueOf($data->pamong_pendidikan) }}</td>
                    <td>{{ $data->pamong_nosk . ', ' . tgl_indo_out($data->pamong_tglsk) }}</td>
                    <td>{{ $data->pamong_nohenti . ', ' . tgl_indo_out($data->pamong_tglhenti) }}</td>
                    <td>{{ $data->pamong_masajab }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection

@section('signature')
    @include('admin.layouts.components.blok_ttd_pamong', ['total_col' => 13, 'spasi_kiri' => 3, 'spasi_tengah' => 6])
@endsection
