@include('admin.layouts.components.asset_datatables')

@extends('admin.layouts.index')

@section('title')
    <h1>
        Stunting
        <small>3 Bulanan Anak 2-6 Tahun</small>
    </h1>
@endsection

@section('breadcrumb')
    <li class="active">3 Bulanan Anak 2-6 Tahun</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    @include('admin.stunting.widget')

    <div class="row">
        @include('admin.stunting.navigasi')

        <div class="col-md-9 col-lg-9">
            <div class="box box-info">
                <div class="box-header">
                    <div class="col-md-8 no-padding">
                        <div class="col-md-4">
                            <div class="form-group">
                                <select name="kuartal" id="kuartal" required class="form-control input-sm" title="Pilih salah satu">
                                    @foreach (kuartal2() as $item)
                                        <option value="{{ $item['ke'] }}" {{ $item['ke'] == $kuartal ? 'selected' : '' }}>Kuartal ke {{ $item['ke'] }}
                                            ({{ $item['bulan'] }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <select name="tahun" id="tahun" required class="form-control input-sm" title="Pilih salah satu">
                                    @foreach ($tahun as $item)
                                        <option value="{{ $item->tahun }}">{{ $item->tahun }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <select name="id" id="id" required class="form-control input-sm" title="Pilih salah satu">
                                    <option value="">Semua</option>
                                    @foreach ($posyandu as $item)
                                        <option value="{{ $item->id }}" {{ $item->id == $id ? 'selected' : '' }}>
                                            {{ $item->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 no-padding">
                            <button type="button" class="btn btn-social btn-info btn-sm" id="cari">
                                <i class="fa fa-search"></i> Cari
                            </button>
                        </div>
                    </div>
                    <div class="col-md-4 no-padding pull-right">
                    </div>
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
                                @for ($i = $awalKuartal; $i <= $akhirKuartal; $i++)
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
@push('scripts')
    <script>
        $('#cari').click(function() {
            let kuartal = $('#kuartal option:selected').val();
            let tahun = $('#tahun option:selected').val();
            let posyandu = $('#id option:selected').val();
            window.location.href = "{{ ci_route('stunting.rekapitulasi_bulanan_balita') }}/" + kuartal + "/" +
                tahun + "/" + posyandu;
        });
    </script>
@endpush
