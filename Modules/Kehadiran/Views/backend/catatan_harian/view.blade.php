@extends('admin.layouts.index')

@section('title')
    <h1>
        Lihat Catatan Harian Kerja
        <small>Lihat Data Catatan Harian Kerja</small>
    </h1>
@endsection

@section('breadcrumb')
    <li><a href="{{ route('kehadiran_catatan_harian.index') }}">Catatan Harian Kerja</a></li>
    <li class="active">Lihat</li>
@endsection

@section('content')
    <div class="box box-info">
        <div class="box-header with-border">
            @include('admin.layouts.components.tombol_kembali', ['url' => ci_route('kehadiran_catatan_harian'), 'label' => 'Daftar Catatan Harian'])
        </div>

        <div class="box-body">
            <div class="form-group">
                <label class="col-sm-2 control-label">Tanggal</label>
                <div class="col-sm-3">
                    <input type="date" class="form-control input-sm" 
                        value="{{ $catatan_harian->tanggal->format('Y-m-d') }}" disabled>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">Nama Perangkat</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control input-sm" value="{{ $pamong?->pamong_nama ?? '-' }}" disabled>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">Jabatan</label>
                <div class="col-sm-3">
                    <input type="text" class="form-control input-sm" value="{{ $pamong?->jabatan?->nama ?? '-' }}" disabled>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">Uraian Kegiatan</label>
                <div class="col-sm-9">
                    <textarea class="form-control input-sm" rows="4" disabled>{{ $catatan_harian->uraian_kegiatan }}</textarea>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">Lokasi Kegiatan</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control input-sm" 
                        value="{{ $catatan_harian->lokasi_kegiatan }}" disabled>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">Hasil yang Diharapkan</label>
                <div class="col-sm-9">
                    <textarea class="form-control input-sm" rows="3" disabled>{{ $catatan_harian->hasil_diharapkan }}</textarea>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-2 control-label">Foto Kegiatan</label>
                <div class="col-sm-9">
                    {{-- Foto Container Panel --}}
                    <div class="panel panel-default">
                        <div class="panel-body" style="padding: 15px;">
                            {{-- Foto Cards Grid --}}
                            <div class="row">
                                @if($catatan_harian->fotos->isNotEmpty())
                                    @foreach($catatan_harian->fotos as $index => $foto)
                                        <div class="col-sm-4 col-xs-6" style="margin-bottom: 15px;">
                                            <div style="border: 2px solid #17a2b8; border-radius: 6px; padding: 12px; background-color: #f9f9f9; text-align: center; position: relative; min-height: 280px; display: flex; flex-direction: column; justify-content: space-between;">
                                                <div style="font-weight: 600; color: #333; font-size: 14px; margin-bottom: 8px;">Foto {{ $index + 1 }}</div>
                                                
                                                {{-- Preview Area --}}
                                                <div style="flex-grow: 1; display: flex; align-items: center; justify-content: center; margin-bottom: 8px; min-height: 180px; overflow: hidden; position: relative;">
                                                    <img src="{{ base_url($foto->file_path) }}" style="max-width: 100%; max-height: 180px; object-fit: contain; cursor: pointer;" onclick="showPhotoModal(this, 'Foto {{ $index + 1 }}')">
                                                </div>

                                                {{-- View Button --}}
                                                <div style="display: flex; gap: 6px;">
                                                    <button type="button" class="btn btn-sm btn-info" style="flex: 1; background-color: #17a2b8; color: white; border-color: #17a2b8;" onclick="showPhotoModal(this.parentElement.parentElement.querySelector('img'), 'Foto {{ $index + 1 }}')">
                                                        <i class="fa fa-eye"></i> Lihat
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="col-sm-12">
                                        <p class="text-muted text-center">Tidak ada foto</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="box-footer">
            <a href="{{ ci_route('kehadiran_catatan_harian') }}" class="btn btn-social btn-default btn-sm">
                <i class="fa fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

@endsection

@push('scripts')
<script>
    // Helper function untuk show image dengan SweetAlert
    function showPhotoModal(imgElement, title) {
        const src = imgElement.src || imgElement.getAttribute('src');
        Swal.fire({
            title: title,
            html: '<img src="' + src + '" style="max-width: 90%; max-height: 500px; object-fit: contain; display: block; margin: 0 auto;">',
            confirmButtonText: 'Tutup',
            didOpen: (modal) => {
                modal.querySelector('.swal2-html-container').style.textAlign = 'center';
            }
        });
    }
</script>
@endpush
