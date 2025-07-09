@include('admin.layouts.components.asset_datatables')

@extends('admin.layouts.index')

@section('title')
    <h1>
        Stunting
        <small>Bulanan Anak 2-6 Tahun</small>
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">Bulanan Anak 2-6 Tahun</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    @include('admin.stunting.widget')

    <div class="row">
        @include('admin.stunting.navigasi')

        <div class="col-md-9 col-lg-9">
            <div class="box box-info">
                <div class="box-header">
                    @include('admin.stunting.filter', ['urlFilter' => ci_route('stunting.rekapitulasi_bulanan_balita')])
                </div>

                <div class="box-body table-responsive">
                    <table id="tabeldata" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th rowspan="3" class="text-center padat" style="vertical-align: middle;">No</th>
                                <th rowspan="3" class="text-center" style="vertical-align: middle;">NO KIA</th>
                                <th rowspan="3" class="text-center" style="vertical-align: middle;">Nama Anak</th>
                                <th rowspan="3" class="text-center" style="vertical-align: middle;">Jenis Kelamin</th>
                            </tr>
                            <tr>
                                <th colspan="2" class="text-center" style="vertical-align: middle;">Usia Menurut Kategori</th>
                                <th colspan="12" class="text-center" style="vertical-align: middle;">Mengikuti Layanan PAUD (Parenting Bagi Orang Tua Anak Usia 2 - <
                                        3
                                        Tahun)
                                        Atau
                                        Kelas
                                        PAUD
                                        Bagi
                                        Anak
                                        3
                                        -
                                        6
                                        Tahun</th
                                    >
                            </tr>
                            <tr>
                                <th class="text-center" style="vertical-align: middle;">Anak Usia 2 - < 3 Tahun</th>
                                <th class="text-center" style="vertical-align: middle;">Anak Usia 3 - 6 Tahun</th>
                                @for ($i = $awalBulan; $i <= $akhirBulan; $i++)
                                    <th class="text-center" style="vertical-align: middle;">{{ getBulan($i) }}</th>
                                @endfor

                            </tr>
                        </thead>
                        @forelse ($dataFilter as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->kia->no_kia }}</td>
                                <td>{{ $item->kia->anak->nama }}</td>
                                <td>{{ App\Enums\JenisKelaminEnum::valueOf($item->kia->anak->sex) }}</td>
                                <td>{{ $item->kategori_usia == 1 ? 'v' : '-' }}</td>
                                <td>{{ $item->kategori_usia == 2 ? 'v' : '-' }}</td>
                                @for ($i = $awalKuartal; $i <= $akhirKuartal; $i++)
                                    <td class="text-center">{{ $item->{strtolower(getBulan($i))} == 1 ? '-' : ($item->{strtolower(getBulan($i))} == 2 ? 'v' : 'x') }}</td>
                                @endfor
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center">Data tidak ditemukan</td>
                            </tr>
                        @endforelse
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
