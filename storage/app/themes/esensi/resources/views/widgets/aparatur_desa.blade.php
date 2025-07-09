<div class="box">
    <div class="box-header">
        <h3 class="box-title">
            <i class="fas fa-user mr-1"></i>{{ $judul_widget }}
        </h3>
    </div>
    <div class="box-body">
        <div class="owl-carousel">
            @foreach ($aparatur_desa['daftar_perangkat'] as $data)
                <div class="relative space-y-2">
                    <div class="w-3/4 mx-auto">
                        <img src="{{ $data['foto'] }}" alt="{{ $data['nama'] }}" class="object-cover object-center bg-gray-300">
                    </div>
                    @if (getWidgetSetting('aparatur_desa', 'overlay') == true)
                        <div class="space-y-1 text-sm text-center z-10">
                            <span class="text-h6">{{ $data['nama'] }}</span>
                            <span class="block">{{ $data['jabatan'] }}</span>
                            @if ($data['pamong_niap'])
                                <span class="block">{{ setting('sebutan_nip_desa') }} : {{ $data['pamong_niap'] }}</span>
                            @endif
                            @if ($data['kehadiran'] == 1)
                                @if ($data['status_kehadiran'] == 'hadir')
                                    <span class="btn btn-primary w-auto mx-auto inline-block">Hadir</span>
                                @endif
                                @if ($data['tanggal'] == date('Y-m-d') && $data['status_kehadiran'] != 'hadir')
                                    <span class="btn btn-danger w-auto mx-auto inline-block">{{ ucwords($data['status_kehadiran']) }}</span>
                                @endif
                                @if ($data['tanggal'] != date('Y-m-d'))
                                    <span class="btn btn-danger w-auto mx-auto inline-block">Belum Rekam Kehadiran</span>
                                @endif
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>
