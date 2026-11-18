@extends('admin.layouts.print_layout')

@section('title', 'Buku Tanah Desa')

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
        margin: 0.5cm;
    }
</style>
@endpush

@section('header')
    <div class="header" align="center">
        <label align="left">{{ get_identitas() }}</label>
        <h4>BUKU TANAH DESA BULAN {{ strtoupper(getBulan($bulan)) }} TAHUN {{ $tahun }}</h4>
        <br>
    </div>
@endsection

@section('content')
    <table class="border thick">
        <thead>
            <tr class="border thick">
                <th rowspan="3">NOMOR URUT</th>
                <th rowspan="3">NAMA PERORANGAN / BADAN HUKUM</th>
                <th rowspan="3">JML (M<sup>2</sup>)</th>
                <th colspan="8">STATUS HAK TANAH (M<sup>2</sup>)</th>
                <th colspan="14">PENGGUNAAN TANAH (M<sup>2</sup>)</th>
                <th rowspan="3">KET</th>
            </tr>
            <tr class="border thick">
                <th colspan="5">SUDAH <br> BERSERTIFIKAT</th>
                <th colspan="3">BELUM <br> BERSERTIFIKAT</th>
                <th colspan="5">NON PERTANIAN</th>
                <th colspan="9">PERTANIAN</th>
            </tr>
            <tr class="border thick">
                <th>HM</th>
                <th>HGB</th>
                <th>HP</th>
                <th>HGU</th>
                <th>HPL</th>
                <th>MA</th>
                <th>VI</th>
                <th>TN</th>
                <th>PERUMAHAN</th>
                <th>PERDAGANGAN DAN JASA</th>
                <th>PERKANTORAN</th>
                <th>INDUSTRI</th>
                <th>FASILITAS UMUM</th>
                <th>SAWAH</th>
                <th>TEGALAN</th>
                <th>PERKEBUNAN</th>
                <th>PETERNAKAN / PERIKANAN</th>
                <th>HUTAN BELUKAR</th>
                <th>HUTAN LEBAT / LINDUNG</th>
                <th>MUTASI TANAH DI DESA</th>
                <th>TANAH KOSONG</th>
                <th>LAIN - LAIN</th>
            </tr>
            <tr class="border thick">
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
                <th>14</th>
                <th>15</th>
                <th>16</th>
                <th>17</th>
                <th>18</th>
                <th>19</th>
                <th>20</th>
                <th>21</th>
                <th>22</th>
                <th>23</th>
                <th>24</th>
                <th>25</th>
                <th>26</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($main as $key => $data)
                <tr>
                    <td class="text-left">{{ $key + 1 }}</td>
                    <td>{{ strtoupper($data->nama_pemilik_asal ?: $data->penduduk->nama) }}</td>
                    <td>{{ $data->luas }}</td>
                    <td class="text-center">{{ $data->hak_milik ?: '' }}</td>
                    <td class="text-center">{{ $data->hak_guna_bangunan ?: '' }}</td>
                    <td class="text-center">{{ $data->hak_pakai ?: '' }}</td>
                    <td class="text-center">{{ $data->hak_guna_usaha ?: '' }}</td>
                    <td class="text-center">{{ $data->hak_pengelolaan ?: '' }}</td>
                    <td class="text-center">{{ $data->hak_milik_adat ?: '' }}</td>
                    <td class="text-center">{{ $data->hak_verponding ?: '' }}</td>
                    <td class="text-center">{{ $data->tanah_negara ?: '' }}</td>
                    <td class="text-center">{{ $data->perumahan ?: '' }}</td>
                    <td class="text-center">{{ $data->perdagangan_jasa ?: '' }}</td>
                    <td class="text-center">{{ $data->perkantoran ?: '' }}</td>
                    <td class="text-center">{{ $data->industri ?: '' }}</td>
                    <td class="text-center">{{ $data->fasilitas_umum ?: '' }}</td>
                    <td class="text-center">{{ $data->sawah ?: '' }}</td>
                    <td class="text-center">{{ $data->tegalan ?: '' }}</td>
                    <td class="text-center">{{ $data->perkebunan ?: '' }}</td>
                    <td class="text-center">{{ $data->peternakan_perikanan ?: '' }}</td>
                    <td class="text-center">{{ $data->hutan_belukar ?: '' }}</td>
                    <td class="text-center">{{ $data->hutan_lebat_lindung ?: '' }}</td>
                    <td>{{ strtoupper($data->mutasi) }}</td>
                    <td class="text-center">{{ $data->tanah_kosong ?: '' }}</td>
                    <td class="text-center">{{ $data->lain ?: '' }}</td>
                    <td>{{ strtoupper($data->keterangan) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
