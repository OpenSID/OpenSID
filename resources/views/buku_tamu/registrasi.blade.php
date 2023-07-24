@extends('buku_tamu.index')

@push('css')
    <style>
        #camera {
            position: relative;
            width: 100%;
            float: left;
            text-align: center;
            margin: 0 0 20px;
        }
    </style>
@endpush
@section('content')
    <div class="col-xl-8 mb-5 mb-xl-10">
        <div class="card card-flush h-lg-100">
            <div class="card-header pt-7">
                <div class="card-title">
                    <span class="svg-icon svg-icon-1 me-2">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M20 14H18V10H20C20.6 10 21 10.4 21 11V13C21 13.6 20.6 14 20 14ZM21 19V17C21 16.4 20.6 16 20 16H18V20H20C20.6 20 21 19.6 21 19ZM21 7V5C21 4.4 20.6 4 20 4H18V8H20C20.6 8 21 7.6 21 7Z"
                                fill="currentColor"></path>
                            <path opacity="0.3"
                                d="M17 22H3C2.4 22 2 21.6 2 21V3C2 2.4 2.4 2 3 2H17C17.6 2 18 2.4 18 3V21C18 21.6 17.6 22 17 22ZM10 7C8.9 7 8 7.9 8 9C8 10.1 8.9 11 10 11C11.1 11 12 10.1 12 9C12 7.9 11.1 7 10 7ZM13.3 16C14 16 14.5 15.3 14.3 14.7C13.7 13.2 12 12 10.1 12C8.10001 12 6.49999 13.1 5.89999 14.7C5.59999 15.3 6.19999 16 7.39999 16H13.3Z"
                                fill="currentColor"></path>
                        </svg>
                    </span>
                    <h2>Registrasi Tamu</h2>
                </div>
            </div>
            <div class="card-body pt-5">
                {!! form_open($aksi, ['id' => 'mainform']) !!}
                <div class="row g-6 g-xl-9">
                    <div class="col-md-9 col-xl-9">
                        <div class="row row-cols-1 row-cols-sm-2 rol-cols-md-1 row-cols-lg-2">
                            <div class="col">
                                <div class="fv-row mb-7 fv-plugins-icon-container">
                                    <input type="text" class="form-control form-control-solid nama required" name="nama"
                                        placeholder="Nama" maxlength="50" autocomplete="off">
                                </div>
                            </div>
                            <div class="col">
                                <div class="fv-row mb-7 fv-plugins-icon-container">
                                    <input type="text" class="form-control form-control-solid bilangan required" name="telepon"
                                        placeholder="Telepon" maxlength="20" pattern="[0-9]+" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class="row row-cols-1 row-cols-sm-2 rol-cols-md-1 row-cols-lg-2">
                            <div class="col">
                                <div class="fv-row mb-7 fv-plugins-icon-container">
                                    <input type="text" class="form-control form-control-solid required" name="instansi"
                                        placeholder="Asal Instansi" maxlength="100" autocomplete="off">
                                </div>
                            </div>
                            <div class="col">
                                <div class="fv-row mb-7 fv-plugins-icon-container">
                                    <select class="form-select form-control form-control-solid alamat required" data-control="select2"
                                        name="jenis_kelamin" placeholder="Jenis Kelamin">
                                        <option value="">Jenis Kelamin</option>
                                        @foreach (\App\Enums\JenisKelaminEnum::all() as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="fv-row mb-7">
                            <textarea class="form-control form-control-solid alamat required" name="alamat" placeholder="Alamat" maxlength="500" rows="5"
                                 ></textarea>
                        </div>
                        <div class="row row-cols-1 row-cols-sm-2 rol-cols-md-1 row-cols-lg-2">
                            <div class="col">
                                <div class="fv-row mb-7 fv-plugins-icon-container">
                                    <select class="form-control form-control-solid select2 " name="id_bidang"
                                        placeholder="Bertemu" required>
                                        <option value="">Berkunjung</option>
                                        @foreach ($bertemu as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="fv-row mb-7 fv-plugins-icon-container">
                                    <select class="form-control form-control-solid select2 " name="id_keperluan"
                                        placeholder="Keperluan" required>
                                        <option value="">Keperluan</option>
                                        @foreach ($keperluan as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-xl-3">
                         <div class="row">
                            <div class="position-relative">
                                <div id="camera" class="col-md-12 top-50 translate-middle start-50"></div>
                                <input type="hidden" id="foto" name="foto">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-6 col-xm-12">
                                <button type="reset" class="btn btn-danger w-100 py-3 mb-5">Batal</button>
                               
                            </div>
                             <div class="col-md-12 col-sm-6 col-xm-12">
                                 <button type="submit" id="simpan" class="btn btn-primary w-100 mb-5">Simpan</button>
                             </div>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('js/webcam.min.js') }}"></script>
    <script>
        // konfigursi webcam
        Webcam.set({
            width: 180,
            height: 180,
            image_format: 'jpg',
            jpeg_quality: 100
        });
        Webcam.attach('#camera');

        Webcam.on('error', function(err) {
            $("#camera").hide();
        });

        $("#simpan").click(function() {
            Webcam.snap(function(data_uri) {
                $("#foto").val(data_uri);
            });
        });
    </script>
@endpush
