@extends('layanan_mandiri.layouts.index')

@section('content')
    <div class="box box-solid">
        <div class="box-header with-border bg-yellow">
            <h4 class="box-title">Pesan</h4>
        </div>
        <div class="box-body box-line">
            <div class="form-group">
                @include('admin.layouts.components.tombol_kembali_layanan_mandiri', ['url' => ci_route('layanan-mandiri.' . $tujuan), 'label' => ucwords(spaceunpenetration($tujuan))])

            </div>
        </div>
        <div class="box-body box-line">
            <h4><b>{{ $kat == 2 ? 'BALAS' : 'TULIS' }} PESAN</b></h4>
        </div>
        <div class="box-body">
            <!-- Notifikasi -->
            @if (session('notif') && session('notif')['data'])
                <div class="alert alert-danger" role="alert">
                    {{ session('notif')['pesan'] }}
                </div>
            @endif
            <form id="validasi" action="{{ url('layanan-mandiri/pesan/kirim') }}" method="post">
                <div class="form-group">
                    <label for="subjek">Subjek</label>
                    <input
                        type="text"
                        @class([
                            'form-control',
                            'required' => $kat != 2,
                            'kbvtext' => $cek_anjungan['keyboard'] == 1,
                        ])
                        name="subjek"
                        placeholder="Subjek"
                        value="{{ $subjek ?? session('notif')['data']['subjek'] }}"
                        @readonly($kat == 2)
                    >
                </div>
                <div class="form-group">
                    <label for="pesan">Isi Pesan</label>
                    <textarea
                        @class([
                            'form-control',
                            'required',
                            'kbvtext' => $cek_anjungan['keyboard'] == 1,
                        ])
                        name="pesan"
                        placeholder="Isi Pesan"
                    >{{ session('notif')['data']['pesan'] ?? '' }}</textarea>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn bg-green btn-social">
                        <i class="fa fa-send-o"></i>Kirim Pesan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
