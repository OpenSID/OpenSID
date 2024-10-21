<?php
$tgl = date('d_m_Y');

header('Content-type: application/octet-stream');
header("Content-Disposition: attachment; filename=statistik_analisis_jawaban_{$tgl}.xls");
header('Pragma: no-cache');
header('Expires: 0');
?>
<!-- TODO: Pindahkan ke external css -->
<style>
    td {
        mso-number-format: "\@";
    }

    td,
    th {
        font-size: 9pt;
        line-height: 9px;
        border: 0.5px solid #555;
        cell-padding: -2px;
        margin: 0px;
    }
</style>
<div id="body">
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th colspan="2">Pertanyaan</th>
                <th colspan="2">Jawaban</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($main as $data)
                <tr>
                    <td>{{ $data['no'] }}</td>
                    <td>
                        {{ $data['pertanyaan'] }}<br>
                        *{{ $data['tipe_indikator'] }}
                    </td>
                    <td>{{ $data['nomor'] }}</td>
                    @if ($data['id_tipe'] == 1)
                        <td>
                            @foreach ($data['par'] as $par)
                                {{ $par['kode_jawaban'] }}.<br>
                            @endforeach
                        </td>
                        <td>
                            @foreach ($data['par'] as $par)
                                {{ $par['jawaban'] }}<br>
                            @endforeach
                        </td>
                    @else
                        <td>-</td>
                        <td>-</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
