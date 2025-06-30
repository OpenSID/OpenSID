@include('admin.layouts.components.form_modal_validasi')
<form action="{{ $form_action }}" method="post" id="validasi">
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-danger">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="nik_kepala">Kepala Keluarga (dari penduduk yang tidak memiliki No. KK)</label>
                            @if ($penduduk)
                                <select class="form-control input-sm required select2" id="nik_kepala" name="nik_kepala" style="width:100%;">
                                    <option value="">-- Silakan Cari NIK / Nama Kepala Keluarga --</option>
                                    @foreach ($penduduk as $data)
                                        <option value="{{ $data['id'] }}">NIK
                                            :{{ $data['nik'] . ' - ' . $data['nama'] }}</option>
                                    @endforeach
                                </select>
                            @else
                                <div class="alert alert-danger" role="alert">
                                    <p>Tidak ada penduduk 'lepas' yang bukan anggota Keluarga. Silakan masukkan dulu
                                        data penduduk yang akan ditambahkan atau pecahkan dari KK yang ada. Pastikan
                                        penduduk tersebut tidak diisi data nomor KK-nya.</p>
                                </div>
                            @endif
                        </div>
                        @if ($penduduk)
                            <div class="form-group">
                                <label for="nik_kepala">Nomor Kartu Keluarga (KK) <code id="tampil_nokk" style="display: none;"> (Sementara) </code> </label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-addon">
                                        <input type="checkbox" title="Centang jika belum memiliki No. KK" id="nokk_sementara">
                                    </span>
                                    <input id="no_kk" name="no_kk" class="form-control input-sm required no_kk" type="text" placeholder="Nomor KK"></input>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="reset" class="btn btn-social btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
        @if ($penduduk)
            <button type="submit" class="btn btn-social btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Simpan</button>
        @endif
    </div>
</form>

<script type="text/javascript">
    $(document).ready(function() {
        $('#nokk_sementara').change(function() {
            var nokk_sementara_berikut = '{{ $nokk_sementara }}';
            var nokk_asli = '{{ $no_kk }}';

            if ($('#nokk_sementara').prop('checked')) {
                $('#no_kk').removeClass('no_kk');
                $('#no_kk').val(nokk_sementara_berikut);
                $('#no_kk').prop('readonly', true);
                $('#tampil_nokk').show();
            } else {
                $('#no_kk').addClass('no_kk');
                $('#no_kk').val(nokk_asli);
                $('#no_kk').prop('readonly', false);
                $('#tampil_nokk').hide();
            }
        });

        $('#nokk_sementara').change();

    });
</script>
