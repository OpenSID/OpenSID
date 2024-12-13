@extends('bukutamu::frontend.index')

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
                        <path
                            d="M21.7,13.35L20.7,14.35L18.65,12.3L19.65,11.3C19.86,11.09 20.21,11.09 20.42,11.3L21.7,12.58C21.91,12.79 21.91,13.14 21.7,13.35M12,18.94L18.06,12.88L20.11,14.93L14.06,21H12V18.94M12,14C7.58,14 4,15.79 4,18V20H10V18.11L14,14.11C13.34,14.03 12.67,14 12,14M12,4A4,4 0 0,0 8,8A4,4 0 0,0 12,12A4,4 0 0,0 16,8A4,4 0 0,0 12,4Z"
                        />
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
                                <input
                                    type="text"
                                    class="form-control required nama required"
                                    maxlength="50"
                                    placeholder="Masukkan Nama"
                                    name="nama"
                                    autocomplete="off"
                                    required
                                >
                            </div>
                        </div>
                        <div class="col-input">
                            <div class="form-group">
                                <label>Asal Instansi</label>
                                <input
                                    type="text"
                                    class="form-control required"
                                    placeholder="Asal Instansi"
                                    name="instansi"
                                    maxlength="100"
                                    autocomplete="off"
                                    required
                                >
                            </div>
                        </div>
                        <div class="col-input">
                            <div class="form-group">
                                <label>Jenis Kelamin</label>
                                <select name="jenis_kelamin" class="form-control form-select required" data-bs-placeholder="Jenis Kelamin" required>
                                    <option label="Pilih">Jenis Kelamin</option>
                                    @foreach (\App\Enums\JenisKelaminEnum::all() as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-input">
                            <div class="form-group">
                                <label>Bertemu</label>
                                <select class="form-control required" name="id_bidang" placeholder="Bertemu" required>
                                    <option label="Pilih" value="">Pilih</option>
                                    @foreach ($bertemu as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-input">
                            <div class="form-group">
                                <label>No. Telp/HP</label>
                                <input
                                    type="text"
                                    class="form-control bilangan telepon required"
                                    name="telepon"
                                    placeholder="Masukkan No. Telp./HP"
                                    maxlength="20"
                                    pattern="[0-9]+"
                                    autocomplete="off"
                                    required
                                >
                            </div>
                        </div>
                        <div class="col-input">
                            <div class="form-group">
                                <label>Keperluan</label>
                                <select name="keperluan" class="form-control form-select" data-bs-placeholder="Keperluan" required>
                                    <option label="Pilih" value="">Keperluan</option>
                                    @foreach ($keperluan as $key => $value)
                                        <option value="{{ $value }}">{{ $value }}</option>
                                    @endforeach
                                    <option value="0">Lainnya</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-input" id="alamat" style="width: 100%">
                            <div class="form-group">
                                <label>Alamat</label>
                                <textarea class="form-control" name="alamat" placeholder="Alamat" maxlength="500"></textarea>
                            </div>
                        </div>
                        <div class="col-input" id="lainnya" style="display: none;">
                            <div class="form-group">
                                <label>Keperluan Lainnya</label>
                                <textarea class="form-control" name="keperluan_lainnya" placeholder="Masukkan Keperluan Lainnya" maxlength="500"></textarea>
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
                            @if ($kamera)
                                <svg viewBox="0 0 24 24">
                                    <path d="M4,4H7L9,2H15L17,4H20A2,2 0 0,1 22,6V18A2,2 0 0,1 20,20H4A2,2 0 0,1 2,18V6A2,2 0 0,1 4,4M12,7A5,5 0 0,0 7,12A5,5 0 0,0 12,17A5,5 0 0,0 17,12A5,5 0 0,0 12,7M12,9A3,3 0 0,1 15,12A3,3 0 0,1 12,15A3,3 0 0,1 9,12A3,3 0 0,1 12,9Z">
                                    </path>
                                </svg>Capture
                            @else
                                <svg
                                    fill="#000000"
                                    height="64px"
                                    width="64px"
                                    version="1.1"
                                    id="Layer_1"
                                    xmlns="http://www.w3.org/2000/svg"
                                    xmlns:xlink="http://www.w3.org/1999/xlink"
                                    viewBox="0 0 512 512"
                                    xml:space="preserve"
                                >
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <g transform="translate(1 1)">
                                            <g>
                                                <g>
                                                    <path
                                                        d="M255-1C114.2-1-1,114.2-1,255s115.2,256,256,256s256-115.2,256-256S395.8-1,255-1z M255,16.067 c63.054,0,120.598,24.764,163.413,65.033l-65.336,64.802L334.36,97.987c-0.853-2.56-4.267-5.12-7.68-5.12H185.027 c-3.413,0-5.973,1.707-7.68,5.12L156.013,152.6h-48.64c-17.067,0-30.72,13.653-30.72,30.72v168.96 c0,17.067,13.653,30.72,30.72,30.72h6.653l-34.26,33.981C40.285,374.319,16.067,317.354,16.067,255 C16.067,123.587,123.587,16.067,255,16.067z M314.733,255c0,33.28-26.453,59.733-59.733,59.733 c-13.563,0-25.99-4.396-35.957-11.854l84.125-83.438C310.449,229.34,314.733,241.616,314.733,255z M195.267,255 c0-33.28,26.453-59.733,59.733-59.733c13.665,0,26.174,4.467,36.179,12.028l-84.183,83.495 C199.613,280.852,195.267,268.487,195.267,255z M303.374,195.199C290.201,184.558,273.399,178.2,255,178.2 c-42.667,0-76.8,34.133-76.8,76.8c0,18.17,6.206,34.779,16.61,47.877l-63.576,63.057H106.52c-7.68,0-13.653-5.973-13.653-13.653 V183.32c0-7.68,5.973-13.653,13.653-13.653h54.613c3.413,0,6.827-2.56,7.68-5.12l21.333-54.613h129.707l19.404,49.675 L303.374,195.199z M206.848,314.974C219.987,325.509,236.703,331.8,255,331.8c42.667,0,76.8-34.133,76.8-76.8 c0-18.068-6.138-34.592-16.436-47.655l37.988-37.678h49.274c7.68,0,13.653,5.973,13.653,13.653v168.96 c0,7.68-5.973,13.653-13.653,13.653H155.469L206.848,314.974z M255,493.933c-62.954,0-120.415-24.686-163.208-64.843L138.262,383 H403.48c17.067,0,30.72-13.653,31.573-30.72V183.32c0-17.067-13.653-30.72-30.72-30.72H370.56l59.865-59.376 c39.368,42.639,63.509,99.521,63.509,161.776C493.933,386.413,386.413,493.933,255,493.933z"
                                                    >
                                                    </path>
                                                    <path d="M383,186.733c-9.387,0-17.067,7.68-17.067,17.067c0,9.387,7.68,17.067,17.067,17.067s17.067-7.68,17.067-17.067 C400.067,194.413,392.387,186.733,383,186.733z">
                                                    </path>
                                                </g>
                                            </g>
                                        </g>
                                    </g>
                                </svg>
                            @endif
                        </div>
                    </div>

                    <input type="hidden" id="foto" name="foto">
                    <!-- Batas Capture Foto -->

                    <div class="capture-bottom" style="align-self: end;">
                        <div class="d-grid" style="margin:0;padding:0;">
                            <button class="btn knob bg-c1" type="submit" id="simpan" {{ $kamera ? 'disabled' : '' }}><svg viewBox="0 0 24 24">
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
    @if ($kamera)
        <script src="{{ asset('js/webcam.min.js') }}"></script>
        <script>
            // konfigursi webcam
            Webcam.set({
                image_format: 'jpg',
                jpeg_quality: 100,

                noInterfaceFoundText: 'Kamera tidak terditeksi / tidak didukung mohon periksa kembali dan pastikan website anda menggunakan ssl/https.',
            });
            Webcam.attach('.capture-box');

            Webcam.on('error', function(err) {
                if (err == 'NotAllowedError: Permission denied') {
                    err =
                        'Anda tidak memberikan izin untuk menggunakan kamera, mohon periksa kembali dan pastikan website anda menggunakan ssl/https.';
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Permintaan Akses Kamera',
                    text: err
                })
            });

            $("#capture").click(function() {
                Webcam.snap(function(data_uri) {
                    let height = $('.capture-box').height()
                    let width = $('.capture-box').width()
                    $("#foto").val(data_uri);
                    $('.capture-box').prepend('<img src="' + data_uri + '" style="position: absolute;height: ' +
                        height + 'px;width:' + width + 'px;padding: 10px;border-radius: 4px;"/>');
                    $('.capture-box').find('video').hide()
                    $("#simpan").prop("disabled", false);
                });
                $(this).remove()
            })
        </script>
    @endif
    <script>
        $('select[name="keperluan"]').change(function() {
            if ($(this).val() === '0') {
                $('#lainnya').show()
                $('textarea[name="keperluan_lainnya"]').attr('required', 'required')
                $('#alamat').attr('style', 'width: calc(50% - 2vh);')
            } else {
                $('#lainnya').hide()
                $('textarea[name="keperluan_lainnya"]').removeAttr('required')
                $('#alamat').attr('style', 'width: 100%;')
            }
        })
    </script>
@endpush
