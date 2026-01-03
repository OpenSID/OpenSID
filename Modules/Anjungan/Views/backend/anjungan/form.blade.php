@extends('admin.layouts.index')
@include('admin.layouts.components.asset_validasi')

@section('title')
    <h1>
        Anjungan
        <small>{{ $action }} Data</small>
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ ci_route('anjungan') }}">Anjungan</a></li>
    <li class="active">{{ $action }} Data</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="box box-info">
        <div class="box-header with-border">
            @include('admin.layouts.components.tombol_kembali', ['url' => ci_route('anjungan'), 'label' => 'Anjungan'])

        </div>
        <div class="box-body">
            {!! form_open($form_action, 'class="form-horizontal" id="validasi"') !!}
            <div class="box-body">
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="ip_address">IP Address</label>
                    <div class="col-sm-7">
                        <input
                            id="ip_address"
                            class="form-control input-sm ip_address"
                            type="text"
                            placeholder="IP address statis untuk anjungan"
                            onkeyup="wajib()"
                            name="ip_address"
                            value="{{ $anjungan->ip_address ?? null }}"
                        >
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="ip_address">Mac Address</label>
                    <div class="col-sm-7">
                        <input
                            id="mac_address"
                            class="form-control input-sm mac_address"
                            type="text"
                            placeholder="00:1B:44:11:3A:B7"
                            onkeyup="wajib()"
                            name="mac_address"
                            value="{{ $anjungan->mac_address ?? null }}"
                        >
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="id_pengunjung">ID Pengunjung</label>
                    <div class="col-sm-7">
                        <input
                            id="id_pengunjung"
                            class="form-control input-sm alfanumerik"
                            type="text"
                            onkeyup="wajib()"
                            placeholder="ad02c373c2a8745d108aff863712fe92"
                            name="id_pengunjung"
                            value="{{ $anjungan->id_pengunjung ?? null }}"
                        >
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="ip_address">IP Address Printer</label>
                    <div class="col-sm-7">
                        <input class="form-control input-sm ip_address" type="text" placeholder="IP address statis untuk printer anjungan" name="printer_ip" value="{{ $anjungan->printer_ip }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="ip_address">Port Address Printer</label>
                    <div class="col-sm-7">
                        <input class="form-control input-sm" type="text" placeholder="Port address statis untuk printer anjungan" name="printer_port" value="{{ $anjungan->printer_port }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="keterangan">Keterangan</label>
                    <div class="col-sm-7">
                        <textarea name="keterangan" class="form-control input-sm" maxlength="300" placeholder="Keterangan" rows="3" style="resize:none;">{{ $anjungan->keterangan }}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="keyboard">Keyboard Virtual</label>
                    <div class="btn-group col-sm-7" data-toggle="buttons">
                        <label id="sx1" class="btn btn-info btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label {{ jecho($anjungan->keyboard, '1', 'active') }}">
                            <input type="radio" name="keyboard" class="form-check-input" type="radio" value="1" {{ jecho($anjungan->keyboard, '1', 'checked') }}> Aktif
                        </label>
                        <label id="sx2" class="btn btn-info btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label {{ jecho($anjungan->keyboard != '1', true, 'active') }}">
                            <input type="radio" name="keyboard" class="form-check-input" type="radio" value="0" {{ jecho($anjungan->keyboard != '1', true, 'checked') }}> Tidak Aktif
                        </label>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="permohonan_surat_tanpa_akun">Permohonan Surat Tanpa Akun</label>
                    <div class="btn-group col-sm-7" data-toggle="buttons">
                        <label id="btnAktifSuratTanpaAkun" class="btn btn-info btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label {{ jecho($anjungan->permohonan_surat_tanpa_akun, '1', 'active') }}">
                            <input type="radio" name="permohonan_surat_tanpa_akun" class="form-check-input" type="radio" value="1" {{ jecho($anjungan->permohonan_surat_tanpa_akun, '1', 'checked') }}> Aktif
                        </label>
                        <label class="btn btn-info btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label {{ jecho($anjungan->permohonan_surat_tanpa_akun != '1', true, 'active') }}">
                            <input type="radio" name="permohonan_surat_tanpa_akun" class="form-check-input" type="radio" value="0" {{ jecho($anjungan->permohonan_surat_tanpa_akun != '1', true, 'checked') }}> Tidak Aktif
                        </label>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <button type="reset" class="btn btn-social btn-danger btn-sm" onclick="reset_form($(this).val());"><i class="fa fa-times"></i> Batal</button>
                <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i>
                    Simpan</button>
            </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            wajib();

            $('#btnAktifSuratTanpaAkun').on('click', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Aktifkan Permohonan Surat Tanpa Akun?',
                    html: `
                        <div class="text-left">
                            <p>
                                Dengan mengaktifkan fitur ini, warga <strong>dapat mengajukan permohonan surat tanpa akun</strong>, dengan terlebih dahulu melakukan verifikasi sebagai berikut:
                            </p>
                            <ul>
                                <li>Melakukan <strong>pindai KTP-el</strong>. Sistem akan menampilkan data penduduk hasil pemindaian, dan warga harus mengonfirmasi bahwa itu adalah dirinya.</li>
                                <li>Atau, mengetik <strong>nama depan</strong> secara lengkap, memilih data penduduk dari hasil pencarian, lalu memasukkan <strong>tanggal lahir</strong> yang benar untuk verifikasi identitas.</li>
                            </ul>
                            <p>
                                Setelah berhasil diverifikasi, warga dapat langsung membuat permohonan surat dan mencetaknya di Anjungan tanpa akun.
                            </p>
                            <p class="text-danger">
                                <strong>Peringatan:</strong> Fitur ini berpotensi menimbulkan <strong>pelanggaran privasi</strong> jika tidak disosialisasikan dan diawasi dengan baik. Pastikan perangkat dan lingkungan Anjungan tetap aman dan tidak disalahgunakan.
                            </p>
                        </div>
                    `,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, aktifkan',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (!result.isConfirmed) {
                        const radioGroup = $('input[name="permohonan_surat_tanpa_akun"]');
                        const tidakAktif = radioGroup.filter('[value="0"]');

                        // Reset semua opsi
                        radioGroup.prop("checked", false).closest('label').removeClass('active');

                        // Pilih "Tidak Aktif"
                        tidakAktif.prop("checked", true)
                            .closest('label').addClass('active')
                            .find('input').trigger('change');
                    }
                });
            });
        });

        function reset_form() {
            var keyboard = "{{ $anjungan->keyboard }}";
            var status = "{{ $anjungan->status }}";

            if (keyboard == 1) {
                $("#sx1").addClass('active');
                $("#sx2").removeClass('active');
            } else {
                $("#sx1").removeClass('active');
                $("#sx2").addClass('active');
            }

            if (status == 1) {
                $("#sx3").addClass('active');
                $("#sx4").removeClass('active');
            } else {
                $("#sx3").removeClass('active');
                $("#sx4").addClass('active');
            }
        };

        function wajib() {
            if ($("#ip_address").val().length > 0) {
                // $("#ip_address").addClass('required');
                $("#mac_address").removeClass('required');
                $("#id_pengunjung").removeClass('required');
            } else if ($("#mac_address").val().length > 0) {
                // $("#mac_address").addClass('required');
                $("#ip_address").removeClass('required');
                $("#id_pengunjung").removeClass('required');
            } else if ($("#id_pengunjung").val().length > 0) {
                // $("#id_pengunjung").addClass('required');
                $("#ip_address").removeClass('required');
                $("#mac_address").removeClass('required');
            } else {
                $("#ip_address").addClass('required');
            }
        }
    </script>
@endpush
