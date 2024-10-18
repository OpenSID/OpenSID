@include('admin.layouts.components.form_modal_validasi')
<form id="validasi" action="{{ $form_action }}" method="POST">
    <div class="modal-body">
        <div class="form-group">
            <p style="margin-bottom: 30px">Pecah semua anggota keluarga dan masukkan ke keluarga baru. Misalnya, pada
                kasus kepala keluarga meninggal, di mana perlu dibuat kartu keluarga baru untuk anggota keluarga yang
                ditinggal.</p>
        </div>
        <div class="form-group">
            <h5><b>Keluarga Lama</b></h5>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover tabel-rincian">
                    <tbody>
                        <tr>
                            <td class='padat'>Nomor Kartu Keluarga</td>
                            <td width="1%">:</td>
                            <td>{{ $keluarga->no_kk }}</td>
                        </tr>
                        <tr>
                            <td>Kepala Keluarga</td>
                            <td>:</td>
                            <td>{{ $keluarga->kepalaKeluarga->nik . ' - ' . $keluarga->kepalaKeluarga->nama }}</td>
                        </tr>
                        <tr>
                            <td>Alamat</td>
                            <td>:</td>
                            <td>{{ $keluarga->kepalaKeluarga->alamat_wilayah }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class='form-group'>
            <h5><b>Keluarga Baru</b></h5>
            <label for="no_kk"> Nomor Kartu Keluarga<code id="tampil_nokk" style="display: none;"> (Sementara)
                </code></label>
            <div class="input-group input-group-sm">
                <span class="input-group-addon">
                    <input type="checkbox" title="Centang jika belum memiliki No. KK" id="nokk_sementara">
                </span>
                <input id="no_kk" name="no_kk" class="form-control input-sm required no_kk" type="text" placeholder="Nomor KK" value="{{ $no_kk }}"></input>
            </div>
        </div>
        <div class="form-group">
            <label>Kepala Keluarga Baru</label>
            <select name="nik_kepala" class="form-control input-sm required" style="width:100%;">
                <option value=""> ----- Pilih Kepala Keluarga ----- </option>
                @foreach ($keluarga->anggota->filter(static fn($q) => $q->kk_level != \App\Enums\SHDKEnum::KEPALA_KELUARGA)->all() as $data)
                    <option value="{{ $data->id }}">
                        {{ $data->nik . ' - ' . $data->nama . ' (' . \App\Enums\SHDKEnum::valueOf($data->kk_level) . ')' }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="modal-footer">
        <button type="reset" class="btn btn-social btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
        <button type="submit" class="btn btn-social btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Simpan</button>
    </div>
</form>
<script type="text/javascript">
    $(document).ready(function() {
        $('#nokk_sementara').change(function() {
            var nokk_sementara = '{{ $nokk_sementara }}';
            var nokk_asli = '{{ $no_kk }}';
            if ($('#nokk_sementara').prop('checked')) {
                $('#no_kk').removeClass('no_kk');
                $('#no_kk').val(nokk_sementara);
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
