@include('admin.layouts.components.form_modal_validasi')
@php
    $sekarang = $log_status_dasar['tgl_peristiwa'] != '' ? $log_status_dasar['tgl_peristiwa'] : date('d-m-Y');
@endphp
<form action="{{ $form_action }}" method="post" id="validasi" class="tgl_lapor_peristiwa" enctype="multipart/form-data">
    <div class='modal-body'>
        <div class="box box-danger">
            <div class="box-body">
                <div class="form-group">
                    <label for="status_dasar">Status Dasar Baru</label>
                    <select id="status_dasar" name="status_dasar" class="form-control select2 input-sm required">
                        <option value="">Pilih Status Dasar</option>
                        @foreach ($list_status_dasar as $key => $value)
                            <option value="{{ $key }}" @selected($key == $nik['status_dasar_id'])>
                                {{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mati">
                    <label for="meninggal_di">Tempat Meninggal</label>
                    <input name="meninggal_di" class="form-control input-sm" type="text" maxlength="50" placeholder="Tempat Meninggal"></input>
                </div>
                <div class="form-group mati">
                    <label for="jam_mati">Jam Kematian</label>
                    <div class="input-group input-group-sm ">
                        <div class="input-group-addon">
                            <i class="fa fa-clock-o"></i>
                        </div>
                        <input name="jam_mati" id="jammenit_1" class="form-control input-sm" type="text" maxlength="50" placeholder="Jam Kematian"></input>
                    </div>
                </div>
                <div class="form-group mati">
                    <label for="sebab">Penyebab Kematian</label>
                    <select id="sebab" name="sebab" class="form-control select2 input-sm required">
                        <option value="">Pilih Penyebab Kematian</option>
                        @foreach ($sebab as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mati">
                    <label for="penolong_mati">Yang Menerangkan Kematian</label>
                    <select id="penolong_mati" name="penolong_mati" class="form-control select2 input-sm required">
                        <option value="">Pilih Yang Menerangkan Kematian</option>
                        @foreach ($penolong_mati as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mati">
                    <label for="anak_ke">Anak Ke-</label>
                    <input name="anak_ke" class="form-control input-sm" type="number" min="1" placeholder="Anak Ke" value="{{ $nik['kelahiran_anak_ke'] }}"></input>
                </div>
                <div class="form-group mati">
                    <label for="akta_mati">Nomor Akta Kematian</label>
                    <input name="akta_mati" class="form-control input-sm" type="text" maxlength="50" placeholder="Nomor Akta Kematian"></input>
                </div>
                <div class="form-group mati">
                    <label for="file">File Akta Kematian : <code>(.jpg, .jpeg, .png, .pdf)</code></label>
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" id="file_path" name="satuan">
                        <input type="file" class="hidden" id="file" name="nama_file" accept=".jpg,.jpeg,.png,.pdf">
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-info" id="file_browser"><i class="fa fa-search"></i> Cari</button>
                        </span>
                    </div>
                    <span class="help-block"><code>Kosongkan jika tidak ingin mengubah dokumen. Ukuran maksimal
                            <strong>{{ max_upload() }} MB</strong>.</code></span>
                </div>
                <div class="form-group pindah">
                    <div class="form-group">
                        <label for="ref_pindah">Tujuan Pindah</label>
                        <select name="ref_pindah" class="form-control select2 input-sm required">
                            <option value="">Pilih Tujuan Pindah</option>
                            @foreach ($list_ref_pindah as $key => $value)
                                <option value="{{ $key }}" @selected($key == $nik['ref_pindah'])>{{ $value }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="alamat_tujuan">Alamat Tujuan</label>
                        <textarea id="alamat_tujuan" name="alamat_tujuan" class="form-control input-sm" placeholder="Alamat Tujuan" style="height: 50px;"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="tgl_peristiwa">Tanggal Peristiwa</label>
                    <div class="input-group input-group-sm date">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input class="form-control input-sm pull-right required tgl_minimal" id="tgl_1" name="tgl_peristiwa" type="text" data-tgl-lebih-besar="#tgl_lapor" value="{{ $sekarang }}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="tgl_lapor">Tanggal Lapor</label>
                    <div class="input-group input-group-sm date">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input class="form-control input-sm pull-right tgl_indo required" id="tgl_lapor" name="tgl_lapor" type="text" value="{{ $sekarang }}">
                    </div>
                </div>
                <div class="form-group">
                    <label for="catatan">Catatan Peristiwa</label>
                    <textarea id="catatan" name="catatan" class="form-control input-sm" placeholder="Catatan" rows="5"></textarea>
                    <p class="help-block">*mati/hilang terangkan penyebabnya, pindah tuliskan alamat pindah</p>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="reset" class="btn btn-social btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
            <button type="submit" class="btn btn-social btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Simpan</button>
        </div>
    </div>
</form>
<script>
    $('#tgl_1').datetimepicker({
        format: 'DD-MM-YYYY',
        locale: 'id'
    });

    $('#tgl_lapor').datetimepicker({
        format: 'DD-MM-YYYY',
        locale: 'id'
    });

    $('document').ready(function() {
        $(".modal #file_browser").click(function(e) {
            e.preventDefault();
            $("#file").click();
        });
        $('.modal #status_dasar').change(function() {
            if ($(this).val() == '3' || $(this).val() == '2') {
                if ($(this).val() == '3') {
                    $('.pindah').show();
                    $("select[name='ref_pindah']").addClass('required');
                    $("textarea[name='alamat_tujuan']").addClass('required');
                    $('.mati').hide();
                    $("input[name='meninggal_di']").removeClass('required');
                    $("input[name='jam_mati']").removeClass('required');
                    $("select[name='sebab']").removeClass('required');
                    $("select[name='penolong_mati']").removeClass('required');
                    $("input[name='anak_ke']").removeClass('required').removeAttr("min");;
                } else {
                    $('.mati').show();
                    $("input[name='meninggal_di']").addClass('required');
                    $('.pindah').hide();
                    $("select[name='ref_pindah']").removeClass('required');
                    $("textarea[name='alamat_tujuan']").removeClass('required');
                    $("input[name='jam_mati']").show().addClass('required');
                    $("select[name='sebab']").addClass('required');
                    $("select[name='penolong_mati']").addClass('required');
                    $("input[name='anak_ke']").addClass('required').attr("min", 1);
                }
            } else {
                $('.pindah').hide();
                $("select[name='ref_pindah']").removeClass('required');
                $("textarea[name='alamat_tujuan']").removeClass('required');
                $('.mati').hide();
                $("input[name='meninggal_di']").removeClass('required');
                $("input[name='jam_mati']").removeClass('required');
                $("select[name='sebab']").removeClass('required');
                $("select[name='penolong_mati']").removeClass('required');
                $("input[name='anak_ke']").removeClass('required').removeAttr("min");;
            }
        });
        $('.modal #status_dasar').trigger('change');

        setTimeout(function() {
            $("#tgl_lapor").rules('add', {
                tgl_lebih_besar: "input[name='tgl_peristiwa']",
                messages: {
                    tgl_lebih_besar: "Tanggal lapor harus sama atau lebih besar dari tanggal peristiwa."
                }
            })
        }, 500);
    });
</script>
