<?php
$tgl = date('d_m_Y');
header('Content-Type: application/vnd.ms-excel');
header("Content-Disposition: attachment; filename=statistik_analisis_jawaban_{$tgl}.xls");
header('Pragma: no-cache');
header('Expires: 0');
?>
<!-- TODO: Pindahkan ke external css -->
<style>
    td {
        mso-number-format: "\@";
        vertical-align: top;
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
        <tr>
            <th>No</th>
            <th>{{ $judul['nomor'] }}</th>
            <th>{{ $judul['nama'] }}</th>
            @if (in_array($subjek_tipe, [1, 2, 3, 4]))
                <th>L/P</th>
            @endif
            @if (in_array($subjek_tipe, [1, 2, 3, 4, 7, 8]))
                <th>{{ ucwords(setting('sebutan_dusun')) }}</th>
                @if ($subjek_tipe != 6)
                    <th>RW</th>
                    @if ($subjek_tipe != 7)
                        <th>RT</th>
                    @endif
                @endif
            @endif
            <th style="background-color:#fefe00">Batas</th>
            @foreach ($indikator as $pt)
                @php
                    if ($pt['par']) {
                        $w = '';
                    } else {
                        $w = "width='80'";
                    }
                @endphp

                @if ($pt['id_tipe'] == 1)
                    <td {{ $w }}>
                        {{ $pt['no'] }}<br>{{ $pt['pertanyaan'] }}
                        @if ($pt['par'])
                            :
                            @foreach ($pt['par'] as $jb)
                                <br>{{ $jb['kode_jawaban'] }}&nbsp{{ $jb['jawaban'] }}
                            @endforeach;
                        @endif
                    </td>
                @else
                    @if ($pt['id_tipe'] == 2)
                        <td {{ $w }} style='background-color:#aaaafe;'>
                            {{ $pt['no'] }}<br>{{ $pt['pertanyaan'] }}
                            @if ($pt['par'])
                                @foreach ($pt['par'] as $jb)
                                    <br>{{ $jb['kode_jawaban'] }}&nbsp{{ $jb['jawaban'] }}
                                @endforeach
                            @endif
                        </td>
                    @elseif ($pt['id_tipe'] == 3)
                        <td style='background-color:#00fe00;'>
                            {{ $pt['no'] }}<br>{{ $pt['pertanyaan'] }}
                        </td>
                    @else
                        <td style='background-color:#feaaaa;'>
                            {{ $pt['no'] }}<br>{{ $pt['pertanyaan'] }}
                        </td>
                    @endif
                @endif
            @endforeach

        </tr>
        <tr>
            <th colspan='{{ $span_kolom ?: 7 }}' style="background-color:#fefe00"></th>
            <th style="background-color:#fefe00">{{ $key }}</th>
            @php
                $tot = count($indikator);
            @endphp
            @foreach ($indikator as $pt)
                <td style='background-color:#fefe00'>
                    {{ $pt['nomor'] }}
                </td>
            @endforeach
        </tr>
        @foreach ($main as $data)
            <tr>
                <td>{{ $data['no'] }}</td>
                <td>{{ $data['nid'] }}</td>
                <td>{{ $data['nama'] }}</td>
                @if (in_array($subjek_tipe, [1, 2, 3, 4]))
                    <td>{{ $data['sex'] == 1 ? 'L' : 'P' }}</td>
                @endif
                @if (in_array($subjek_tipe, [1, 2, 3, 4, 7, 8]))
                    <td>{{ $data['dusun'] }}</td>
                    @if ($subjek_tipe != 6)
                        <td>{{ $data['rw'] }}</td>
                        @if ($subjek_tipe != 7)
                            <td>{{ $data['rt'] }}</td>
                        @endif
                    @endif
                @endif
                <td style="background-color:#fefe00">{{ $data['id'] }}</td>

                @if (!$data['par'])
                    @for ($j = 0; $j < $tot; $j++)
                        <td></td>
                    @endfor
                @else
                    @foreach ($indikator as $pt)
                        @php
                            //cumawarna
                            $bx = '';
                            $false = 0;

                            foreach ($data['par'] as $jawab):
                                $isi = '';
                                if ($pt['id'] == $jawab['id_indikator'] && $false == 0):
                                    if ($pt['id_tipe'] == 1):
                                        $isi = $jawab['kode_jawaban'];
                                    elseif ($pt['id_tipe'] == 2):
                                        $isi .= $jawab['kode_jawaban'];
                                    else:
                                        $isi = $jawab['jawaban'];
                                    endif;

                                    //kosong dia
                                    if ($isi == ''):
                                        $bx = "style='background-color:#bbffbb;'";
                                    endif;

                                    //koreksi
                                    if ($jawab['korek'] == -1):
                                        $bx = "style='background-color:#ff9999;'";
                                    endif;

                                    if ($pt['id_tipe'] != 2):
                                        $false = 1;
                                    endif;
                                endif;
                            endforeach;
                        @endphp

                        <td {{ $bx }}>

                            @php
                                $false = 0;
                                $isi = '';

                                foreach ($data['par'] as $jawab):
                                    if ($pt['id'] == $jawab['id_indikator'] && $false == 0):
                                        if ($pt['id_tipe'] == 1):
                                            $isi = $tipe == 1 ? $jawab['jawaban'] : $jawab['kode_jawaban'];
                                        elseif ($pt['id_tipe'] == 2 && $pt['is_teks'] == 0):
                                            $isi .= $tipe == 1 ? $jawab['jawaban'] : $jawab['kode_jawaban'] . ',';
                                        elseif ($pt['id_tipe'] == 2 && $pt['is_teks'] == 1):
                                            $isi .= $jawab['jawaban'] . ',';
                                        else:
                                            $isi = $jawab['jawaban'];
                                        endif;

                                        //kosong dia
                                        if ($isi == ''):
                                            $bx = "style='background-color:#bbffbb;'";
                                        endif;

                                        //koreksi
                                        if ($jawab['korek'] == -1):
                                            $isi = 'xxx';
                                            $bx = "style='background-color:#ff9999;'";
                                        endif;

                                        if ($pt['id_tipe'] != 2):
                                            $false = 1;
                                        endif;
                                    endif;
                                endforeach;

                                //DEL last koma
                                if ($pt['id_tipe'] == 2):
                                    $jml = strlen($isi);
                                    $isi = substr($isi, 0, $jml - 1);
                                endif;

                            @endphp
                            {{ $isi }}
                        </td>
                    @endforeach
                @endif
            </tr>
        @endforeach
    </table>
</div>
