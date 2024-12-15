@extends('kehadiran::frontend.layouts.index')

@section('content')

    <div class="row vertical-align" style="background-color: #ffffff">
        <div class="col-sm-8 hidden-xs" style="padding: 0px;">
            @include('kehadiran::frontend.left')
        </div>
        <div class="col-sm-4 col-xm-4">
            <div class="row">
                <div class="col-xm-12 text-center" style="padding-top:100px; padding-left: 25px; padding-right: 25px;">
                    <img class="user-image" src="{{ AmbilFoto($masuk['foto'], '', $masuk['sex']) }}" alt="Foto Penduduk" height="120px">
                    @if ($success != 0)
                        <div class="alert alert-success alert-dismissible fade in" role="alert">
                            <strong>{{ 'Rekam Kehadiran ' . ($kehadiran ? 'Masuk' : 'Keluar') . ' Berhasil' }}</strong>
                        </div>
                        <div class="alert alert-warning alert-dismissible fade in" role="alert">
                            Halaman akan keluar otomatis dalam 5 detik
                        </div>
                    @endif
                </div>
                <div class="col-xm-12 text-center">
                    <h2>{{ $masuk['pamong_nama'] }}</h2>
                    <h4>{{ $masuk['jabatan'] }}</h4>
                </div>
                <div class="col-xm-12 text-center">
                    {!! form_open_multipart(ci_route('kehadiran.check-in-out'), 'name="check" id="validasi"') !!}
                    <div class="checkbox">
                        @if (!$kehadiran && !$success)
                            <input type="hidden" name="status_kehadiran" value="hadir">
                            <button id="hadir" class="btn btn-success btn-small">Rekam Masuk</button>
                        @endif

                        @if ($kehadiran && !$success)
                            <div class="form-group">
                                <select name="status_kehadiran">
                                    <option value="tidak berada di kantor">Absen Keluar</option>
                                    @foreach ($alasan as $item)
                                        <option value="{{ strtolower($item->alasan) }}">{{ ucwords(strtolower($item->alasan)) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button id="keluar" class="btn btn-danger btn-small">Keluar</button>
                        @endif
                    </div>
                    </form>
                </div>
                <div class="col-xm-12 text-center">
                    <a class="btn bg-olive margin" href="{{ ci_route('kehadiran.logout') }}">Selesai</a>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(function() {
            var success = "{{ $success }}";
            var url = "{{ ci_route('kehadiran.logout') }}";

            if (success) {
                setTimeout(function() {
                    location.href = url;
                }, 5000);
            }

            var waktu = "{{ $kehadiran->jam_masuk }}";
            var sekarang = "{{ date('H:i') }}";

            if (waktu < sekarang) {
                $('#hadir').click(function() {
                    $('form[name="check"]').submit();
                })
            } else {
                $('#keluar').click(function() {
                    var box = confirm("Anda masuk kurang dari 1 menit, apakah anda yakin ingin keluar?");
                    if (box == true)
                        $('form[name="check"]').submit();
                    else
                        return false;
                })
            }
        });
    </script>
@endpush
