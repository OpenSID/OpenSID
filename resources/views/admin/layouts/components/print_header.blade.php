@php
    $model_code = $model_code ?? '';
    $document_title = $document_title ?? 'DOKUMEN';
@endphp

<div align="center">
    <h3>{{ strtoupper($document_title) }}</h3>
</div>

@if ($model_code)
    <table>
        <col span="12" style="width: 7.75%;">
        <col style="width: 7%;">
        <tr>
            <td colspan="12">&nbsp;</td>
            <td style="border: solid 1px black; font-size: 14px; text-align: center; padding: 5px 0px;">
                {{ $model_code }}</td>
        </tr>
    </table>
@endif

<table>
    <tr>
        <td colspan="2" class="bold" style="width: 13%;">{{ ucwords(setting('sebutan_desa')) }}</td>
        <td colspan="1" style="width: 10%; white-space: nowrap;"> : {{ $desa['nama_desa'] ?? '' }}</td>
        <td colspan="10">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="2" class="bold">{{ ucwords(setting('sebutan_kecamatan')) }}</td>
        <td colspan="1"> : {{ $desa['nama_kecamatan'] ?? '' }}</td>
        <td colspan="10">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="2" class="bold">{{ ucwords(setting('sebutan_kabupaten')) }}</td>
        <td colspan="1"> : {{ $desa['nama_kabupaten'] ?? '' }}</td>
        <td colspan="10">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="2" class="bold">Bulan / Tahun</td>
        <td colspan="1"> : {{ getBulan(date('m')) . ' / ' . date('Y') }}</td>
        <td colspan="10">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="13">&nbsp;</td>
    </tr>
</table>
