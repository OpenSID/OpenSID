@extends('layanan_mandiri.layouts.index')

@section('content')
    <div class="box box-solid">
        <div class="box-header with-border bg-yellow">
            <h4 class="box-title">Pesan</h4>
        </div>
        <div class="box-body box-line">
            <div class="form-group">
                <a href="{{ ci_route('layanan-mandiri.' . $tujuan) }}" class="btn bg-aqua btn-social">
                    <i class="fa fa-arrow-circle-left"></i>Kembali ke {{ ucwords(spaceunpenetration($tujuan)) }}
                </a>
            </div>
        </div>
        <div class="box-body box-line">
            <h4><b>BACA PESAN</b></h4>
        </div>
        <div class="box-body">
            <form id="validasi" action="{{ ci_route('layanan-mandiri.pesan.balas') }}" method="post">
                <div class="form-group">
                    <label for="owner">{{ $owner }}</label>
                    <input type="text" class="form-control" value="{{ $pesan['owner'] }}" readonly>
                </div>
                <div class="form-group">
                    <label for="subjek">Subjek</label>
                    <input type="text" class="form-control" name="subjek" value="{{ $pesan['subjek'] }}" readonly>
                </div>
                <div class="form-group">
                    <label for="pesan">Isi Pesan</label>
                    <textarea class="form-control" readonly>{{ $pesan['komentar'] }}</textarea>
                </div>
                @if ($kat == 2)
                    <!-- Tombol balas hanya untuk kotak masuk -->
                    <hr />
                    <div class="form-group">
                        <button type="submit" class="btn bg-green btn-social">
                            <i class="fa fa-reply"></i>Balas Pesan
                        </button>
                        @if ($pesan['permohonan'] && $permohonan['status'] == 0)
                            <a href="{{ url('layanan-mandiri/surat/buat/' . $pesan['permohonan']) }}" class="btn btn-social bg-navy" title="Lengkapi Surat">
                                <i class="fa fa-info-circle"></i>Lengkapi Surat
                            </a>
                        @endif
                    </div>
                @endif
            </form>
        </div>
    </div>
@endsection
