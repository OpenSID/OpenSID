@extends('admin.layouts.print_layout')

@section('title', 'Agenda Surat Keluar')

@if(isset($is_landscape) && $is_landscape)
@push('css')
<style>
    /* Mendukung landscape orientation untuk print preview */
    body.landscape #print-modal {
        width: 1122px;
        margin: 0 0 0 -589px;
    }

    /* Override overflow hidden untuk enable scrolling */
    body.landscape #print-modal-content {
        overflow: auto !important;
    }

    @media print {
        @page {
            margin: 0.5cm;
        }

        body {
            margin: 0;
            padding: 0;
        }
    }
</style>
@endpush
@endif

@push('css')
<style>
    body {
        orientation: landscape;
    }

    .textx {
        mso-number-format: "\@";
    }

    td,
    th {
        font-size: 8pt;
        mso-number-format: "\@";
    }

    table#ttd td {
        text-align: center;
        white-space: nowrap;
    }

    .underline {
        text-decoration: underline;
    }

    @page {
        size: landscape;
        margin: 1cm;
    }
</style>
@endpush

@section('header')
<div class="header" align="center">
    <label align="left"><?= get_identitas() ?></label>
     <h4>
        <span>AGENDA SURAT KELUAR</span>
        @if ($tahun)
            TAHUN {{ $tahun }}
        @endif
    </h4>
    <br>
</div>
@endsection

@section('content')
<table class="border thick">
    <thead>
        <tr class="border thick">
            <th>Nomor Urut</th>
            <th>Nomor Surat</th>
            <th>Tanggal Surat</th>
            <th>Ditujukan Kepada</th>
            <th>Isi Singkat</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($main as $data)
            <tr>
                <td><?= $data['nomor_urut'] ?></td>
                <td><?= $data['nomor_surat'] ?></td>
                <td><?= tgl_indo($data['tanggal_surat']) ?></td>
                <td><?= $data['tujuan'] ?></td>
                <td><?= $data['isi_singkat'] ?></td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection