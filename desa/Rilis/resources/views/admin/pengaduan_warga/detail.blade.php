@extends('admin.layouts.index')

@section('title')
    <h1>
        Pengaduan
        <small>{{ $action }}</small>
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ ci_route('pengaduan_admin') }}">Daftar Pengaduan</a></li>
    <li class="active">{{ $action }}</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="box box-info">
        <div class="box-header with-border">
            <a href="{{ ci_route('pengaduan_admin') }}" class="btn btn-social btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left"></i> Kembali Ke Daftar Pengaduan</a>
        </div>
        <div class="box-body">
            <ul class="timeline timeline-inverse">
                <!-- timeline time label -->
                <li class="time-label">
                    <span class="bg-blue">
                        {{ tgl_indo($pengaduan->created_at) }}
                    </span>
                </li>
                <!-- /.timeline-label -->
                <!-- timeline item -->
                <li>
                    <i class="fa fa-user bg-blue"></i>

                    <div class="timeline-item">
                        <span class="time"><i class="fa fa-clock-o"></i> {{ $pengaduan->created_at->format('H:i') }}</span>

                        <h3 class="timeline-header"><b>{{ $pengaduan->nama }}</b> {{ $pengaduan->judul }}</h3>

                        <div class="timeline-body">
                            {{ $pengaduan->isi }}
                        </div>
                        <div class="timeline-footer">
                            <p><i class="fa fa-id-card"></i> {{ $pengaduan->nik }}</p>
                            <p><i class="fa fa-phone"></i> {{ $pengaduan->telepon }}</p>
                            <p><i class="fa fa-envelope"></i> {{ $pengaduan->email }}</p>
                        </div>
                    </div>
                </li>
                <!-- END timeline item -->
                <!-- timeline item -->
                @if ($pengaduan->foto)
                    <li>
                        <i class="fa fa-image bg-blue"></i>

                        <div class="timeline-item">
                            <div class="timeline-body">
                                <img class="img-responsive" src="{{ to_base64(LOKASI_PENGADUAN . $pengaduan->foto) }}">
                            </div>
                        </div>
                    </li>
                @endif
                <!-- END timeline item -->
                <!-- timeline time label -->
                @if (count($pengaduan->child) > 0)
                    @foreach ($pengaduan->child as $item)
                        <li class="time-label">
                            <span class="bg-green">
                                {{ tgl_indo($item->created_at) }}
                            </span>
                        </li>
                        <!-- /.timeline-label -->
                        <!-- timeline item -->
                        <li>
                            <i class="fa fa-comments bg-green"></i>

                            <div class="timeline-item">
                                <span class="time"><i class="fa fa-clock-o"></i> {{ $item->created_at->format('H:i') }}</span>

                                <h3 class="timeline-header"><a href="#">{{ $item->nama }}</a> Menanggapi</h3>

                                <div class="timeline-body">
                                    {{ $item->isi }}
                                </div>
                            </div>
                        </li>
                    @endforeach
                @endif
                <!-- END timeline item -->
                <li>
                    <i class="fa fa-clock-o bg-gray"></i>
                </li>
            </ul>
        </div>
    </div>
@endsection
