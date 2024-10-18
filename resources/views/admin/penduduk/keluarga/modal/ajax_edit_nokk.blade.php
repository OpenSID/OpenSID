@include('admin.layouts.components.form_modal_validasi')
<form action="{{ $form_action }}" method="post" id="validasi">
    <div class="modal-body">
        <div class="form-group">
            <label for="no_kk">Nomor KK <code id="tampil_nokk" style="display: none;"> (Sementara) </code></label>
            <div class="input-group input-group-sm">
                <span class="input-group-addon">
                    <input type="checkbox" title="Centang jika belum memiliki No. KK" id="nokk_sementara" @checked($cek_nokk == '0')>
                </span>
                <input
                    id="no_kk"
                    name="no_kk"
                    class="form-control input-sm required no_kk"
                    type="text"
                    placeholder="Nomor KK"
                    value="{{ $kk->no_kk }}"
                    @readonly($cek_nokk == '0')
                ></input>
            </div>
            <input name="id" type="hidden" value="{{ $kk->id }}">
        </div>
        <div class="form-group">
            <label for="alamat">Alamat </label>
            <textarea id="alamat" name="alamat" class="form-control input-sm alamat" maxlength="200" placeholder="Alamat Jalan/Perumahan" rows="3">{{ $kk->alamat }}</textarea>
        </div>
        <div class="row">
            <div class="form-group col-sm-6">
                <label for="dusun">{{ ucwords(setting('sebutan_dusun')) }} </label>
                <select id="dusun" class="form-control input-sm required">
                    <option value="">Pilih {{ ucwords(setting('sebutan_dusun')) }}</option>
                    @foreach ($wilayah as $keyDusun => $dusun)
                        <option value="{{ $keyDusun }}" @selected($keyDusun == $kk->wilayah->dusun)>{{ $keyDusun }}</option>
                    @endforeach
                </select>
            </div>
            <div id="isi_rw" class="form-group col-sm-3">
                <label for="rw">RW</label>
                <select id="rw" class="form-control input-sm required">
                    <option class="placeholder" value="">Pilih RW</option>
                    @foreach ($wilayah as $keyDusun => $dusun)
                        <optgroup value="{{ $keyDusun }}" label="{{ $keyDusun }}" @disabled($keyDusun != $kk->wilayah->dusun)>
                            @foreach ($dusun as $keyRw => $rw)
                                <option value="{{ $keyDusun }}__{{ $keyRw }}" @selected($kk->wilayah->rw == $keyRw && $kk->wilayah->dusun == $keyDusun)>
                                    {{ $keyRw }}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
            </div>
            <div id="isi_rt" class="form-group col-sm-3">
                <label for="rt">RT</label>
                <select id="id_cluster" name="id_cluster" class="form-control input-sm required">
                    <option class="placeholder" value="">Pilih RT </option>
                    @foreach ($wilayah as $keyDusun => $dusun)
                        @foreach ($dusun as $keyRw => $rw)
                            <optgroup value="{{ $keyDusun }}__{{ $keyRw }}" label="{{ $keyRw }}" @disabled($keyDusun != $kk->wilayah->dusun || $keyRw != $kk->wilayah->rw)>
                                @foreach ($rw as $rt)
                                    <option value="{{ $rt->id }}" @selected($kk->id_cluster == $rt->id)>
                                        {{ $rt->rt }}</option>
                                @endforeach
                            </optgroup>
                        @endforeach
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="tgl_cetak_kk">Tanggal Cetak Kartu Keluarga <code> (Contoh : 31/12/1980 )</code> </label>
            <div class="input-group input-group-sm date">
                <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                </div>
                <input id="tgl_1" name="tgl_cetak_kk" class="form-control input-sm datepicker" type="text" value="{{ $kk->tgl_cetak_kk ? $kk->tgl_cetak_kk->format('d-m-Y') : '' }}" />
            </div>
        </div>
        <div class="form-group">
            <label for="kelas_sosial">Kelas Sosial</label>
            <select id="kelas_sosial" name="kelas_sosial" class="form-control input-sm">
                <option value="">Pilih Tingkatan Keluarga Sejahtera</option>
                @foreach ($keluarga_sejahtera as $data)
                    <option value="{{ $data->id }}" @selected($kk->kelas_sosial == $data->id)>
                        {{ strtoupper($data->nama) }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="modal-footer">
        @if ($kk->kepalaKeluarga->status_dasar == 1)
            {!! batal() !!}
            <button type="submit" class="btn btn-social btn-info btn-sm"><i class="fa fa-check"></i>
                Simpan</button>
        @else
            <button id="tutup" type="reset" class="btn btn-social btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
        @endif
    </div>
</form>
<script type="text/javascript">
    $(document).ready(function() {
        @if ($kk->kepalaKeluarga->status_dasar != 1)
            $("#validasi :input").prop("disabled", true);
            $("#tutup").prop("disabled", false);
        @endif

        $('#validasi #dusun').change(function() {
            let _label = $(this).find('option:selected').val()
            $('#validasi #rw').find(`optgroup`).prop('disabled', 1)
            if ($(this).val()) {
                $('#validasi #rw').closest('div').show()
                $('#validasi #rw').find(`optgroup[value="${_label}"]`).prop('disabled', 0)
            } else {
                $('#validasi #rw').closest('div').hide()
                $('#validasi #rw').find(`optgroup`).prop('disabled', 1)
            }
            $('#validasi #rw').val('')
            $('#validasi #rw').trigger('change')
        })

        $('#validasi #rw').change(function() {
            let _label = $(this).find('option:selected').val()
            $('#validasi #id_cluster').find(`optgroup`).prop('disabled', 1)
            if ($(this).val()) {
                $('#validasi #id_cluster').closest('div').show()
                $('#validasi #id_cluster').find(`optgroup[value="${_label}"]`).prop('disabled', 0)
            } else {
                $('#validasi #id_cluster').closest('div').hide()
                $('#validasi #id_cluster').find(`optgroup`).prop('disabled', 1)
            }
            $('#validasi #id_cluster').val('')
            $('#validasi #id_cluster').trigger('change')
        })

        $('#nokk_sementara').change(function() {
            var cek_nokk = '{{ $cek_nokk }}';
            var nokk_sementara_berikut = '{{ $nokk_sementara }}';
            var nokk_asli = '{{ $kk->no_kk }}';

            if ($('#nokk_sementara').prop('checked')) {
                $('#no_kk').removeClass('no_kk');
                if (cek_nokk != '0') $('#no_kk').val(nokk_sementara_berikut);
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
        $('#validasi select').select2()

        $('.datepicker').datepicker({
            weekStart: 1,
            language: 'id',
            format: 'dd-mm-yyyy',
            autoclose: true
        });
    });
</script>
