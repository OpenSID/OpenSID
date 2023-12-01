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
<!-- Mulai Kolom Kanan -->
<div class="area-content">
    <div class="area-content-inner">
        <div class="head-content difle-l">
            <div class="head-content-icon difle-c">
                <svg viewBox="0 0 24 24">
                    <path d="M21.7,13.35L20.7,14.35L18.65,12.3L19.65,11.3C19.86,11.09 20.21,11.09 20.42,11.3L21.7,12.58C21.91,12.79 21.91,13.14 21.7,13.35M12,18.94L18.06,12.88L20.11,14.93L14.06,21H12V18.94M12,14C7.58,14 4,15.79 4,18V20H10V18.11L14,14.11C13.34,14.03 12.67,14 12,14M12,4A4,4 0 0,0 8,8A4,4 0 0,0 12,12A4,4 0 0,0 16,8A4,4 0 0,0 12,4Z" />
                </svg>
            </div>
            <h1>Register Tamu</h1>
        </div>
        {!! form_open($aksi, ['id' => 'mainform']) !!}
        <div class="grider">

            <!-- Mulai Form Isian -->

            <div class="content-form">
                <div class="grider mlr-min1vh">
                    <div class="col-input">
                        <div class="form-group">
                            <label>Nama</label>
                            <input type="text" class="form-control required nama required" maxlength="50" placeholder="Masukkan Nama" name="nama" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="col-input">
                        <div class="form-group">
                            <label>No. Telp/HP</label>
                            <input type="text" class="form-control bilangan telepon required" name="telepon" placeholder="Masukkan No. Telp./HP" maxlength="20" pattern="[0-9]+" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="col-input">
                        <div class="form-group">
                            <label>Asal Instansi</label>
                            <input type="text" class="form-control required" placeholder="Asal Instansi" name="instansi" maxlength="100" autocomplete="off" required>
                        </div>
                    </div>
                    <div class="col-input">
                        <div class="form-group">
                            <label>Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-control form-select select2 alamat required" data-bs-placeholder="Jenis Kelamin" data-control="select2" required>
                                <option label="Pilih">Jenis Kelamin</option>
                                @foreach (\App\Enums\JenisKelaminEnum::all() as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Alamat</label>
                    <textarea class="form-control" name="alamat" placeholder="Alamat" maxlength="500"></textarea>
                </div>
                <div class="content-form-bottom" style="align-self: end;">
                    <div class="grider mlr-min1vh">
                        <div class="col-input">
                            <div class="form-group">
                                <label>Bertemu</label>
                                <select class="form-control select2 required" name="id_bidang" placeholder="Bertemu" required>
                                    <option label="Pilih" value="">Pilih</option>
                                    @foreach ($bertemu as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-input">
                            <div class="form-group">
                                <label>Keperluan</label>
                                <select name="id_keperluan" class="form-control form-select select2" data-bs-placeholder="Keperluan" required>
                                    <option label="Pilih" value="">Keperluan</option>
                                    @foreach ($keperluan as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Batas Form Isian -->

            <div class="capture">

                <!-- Mulai Capture Foto -->
                <div class="capture-box">
                    <div class="capture-image" style="display:none"></div>
                    <div class="capture-live"></div>
                </div>

                <div class="difle-c" id="capture">
                    <div class="get-image difle-c">
                    <svg viewBox="0 0 24 24"><path d="M4,4H7L9,2H15L17,4H20A2,2 0 0,1 22,6V18A2,2 0 0,1 20,20H4A2,2 0 0,1 2,18V6A2,2 0 0,1 4,4M12,7A5,5 0 0,0 7,12A5,5 0 0,0 12,17A5,5 0 0,0 17,12A5,5 0 0,0 12,7M12,9A3,3 0 0,1 15,12A3,3 0 0,1 12,15A3,3 0 0,1 9,12A3,3 0 0,1 12,9Z"></path></svg>Capture
                    </div>
                </div>

                <input type="hidden" id="foto" name="foto">

                <!-- Batas Capture Foto -->

                <div class="capture-bottom" style="align-self: end;">
                    <div class="d-grid" style="margin:0;padding:0;">
                        <button class="btn knob bg-c1" type="submit" id="simpan" disabled><svg viewBox="0 0 24 24">
                                <path d="M9,20.42L2.79,14.21L5.62,11.38L9,14.77L18.88,4.88L21.71,7.71L9,20.42Z" />
                            </svg>Simpan</button>
                        <button class="btn knob bg-c2" type="reset"><svg viewBox="0 0 24 24">
                                <path d="M20 6.91L17.09 4L12 9.09L6.91 4L4 6.91L9.09 12L4 17.09L6.91 20L12 14.91L17.09 20L20 17.09L14.91 12L20 6.91Z" />
                            </svg>Batal</button>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </div>
</div>
<!-- Batas Kolom Kanan -->


@endsection
@push('scripts')
<script src="{{ asset('js/webcam.min.js') }}"></script>
<script>
    // konfigursi webcam
    Webcam.set({
        image_format: 'jpg',
        jpeg_quality: 100
    });
    Webcam.attach('.capture-box');

    Webcam.on('error', function(err) {
        $(".capture-image").hide();
    });

    $("#capture").click(function () {
        Webcam.snap( function(data_uri) {
            let height = $('.capture-box').height()
            let width = $('.capture-box').width()
            $("#foto").val(data_uri);
            $('.capture-box').prepend('<img src="'+data_uri+'" style="position: absolute;height: '+height+'px;width:'+width+'px;padding: 10px;border-radius: 4px;"/>'); 
            $('.capture-box').find('video').hide()
            $("#simpan").prop("disabled", false);
        } );
        $(this).remove()
    })
</script>
@endpush

