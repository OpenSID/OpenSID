@include('admin.layouts.components.form_modal_validasi')
@php
    $sekarang = $log_status_dasar['tgl_peristiwa'] != '' ? $log_status_dasar['tgl_peristiwa'] : date('d-m-Y');
@endphp
<form action="{{ $form_action }}" method="post" id="validasi" class="tgl_lapor_peristiwa" enctype="multipart/form-data">
    <div class='modal-body'>
        <div class="box-body">
            <div class="form-group">
                <label for="status_dasar">Status Dasar</label>
                <select class="form-control select2 input-sm" disabled>
                    <option value="">{{ App\Models\LogPenduduk::kodePeristiwaAll($log_status_dasar['kode_peristiwa']) }}</option>
                </select>
            </div>
            @if ($log_status_dasar['kode_peristiwa'] == App\Models\LogPenduduk::MATI)
                <div class="form-group mati">
                    <label for="meninggal_di">Tempat Meninggal</label>
                    <input name="meninggal_di" class="form-control input-sm required" type="text" maxlength="50" placeholder="Tempat Meninggal" value="{{ $log_status_dasar['meninggal_di'] }}"></input>
                </div>
                <div class="form-group mati">
                    <label for="jam_mati">Jam Kematian</label>
                    <div class="input-group input-group-sm ">
                        <div class="input-group-addon">
                            <i class="fa fa-clock-o"></i>
                        </div>
                        <input
                            name="jam_mati"
                            id="jammenit_1"
                            class="form-control input-sm"
                            type="text"
                            maxlength="50"
                            placeholder="Jam Kematian"
                            value="{{ $log_status_dasar['jam_mati'] }}"
                        ></input>
                    </div>
                </div>
                <div class="form-group mati">
                    <label for="sebab">Penyebab Kematian</label>
                    <select id="sebab" name="sebab" class="form-control select2 input-sm required">
                        <option value="">Pilih Penyebab Kematian</option>
                        @foreach ($sebab as $key => $value)
                            <option value="{{ $key }}" @selected($key == $log_status_dasar['sebab'])>{{ $value }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mati">
                    <label for="penolong_mati">Yang menerangkan kematian</label>
                    <select id="penolong_mati" name="penolong_mati" class="form-control select2 input-sm required">
                        <option value="">Pilih Yang menerangkan kematian</option>
                        @foreach ($penolong_mati as $key => $value)
                            <option value="{{ $key }}" @selected($key == $log_status_dasar['penolong_mati'])>{{ $value }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mati">
                    <label for="anak_ke">Anak Ke</label>
                    <input
                        name="anak_ke"
                        class="form-control input-sm"
                        type="number"
                        min="1"
                        max="20"
                        placeholder="Anak Ke"
                        value="{{ $log_status_dasar->penduduk->kelahiran_anak_ke }}"
                    ></input>
                </div>
                <div class="form-group mati">
                    <label for="akta_mati">Nomor Akta Kematian</label>
                    <input name="akta_mati" class="form-control input-sm" type="text" maxlength="50" placeholder="Nomor Akta Kematian" value="{{ $log_status_dasar['akta_mati'] }}"></input>
                </div>
            @endif
            <div class="form-group mati">
                <label for="file">File Akta Kematian <code>(.jpg, .jpeg, .png, .pdf)</code></label>
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
            @if ($log_status_dasar['kode_peristiwa'] == App\Models\LogPenduduk::PINDAH_KELUAR)
                <div class="form-group pindah">
                    <label for="ref_pindah">Tujuan Pindah</label>
                    <select name="ref_pindah" class="form-control select2 input-sm required">
                        <option value="">Pilih Tujuan Pindah</option>
                        @foreach ($list_ref_pindah as $key => $val)
                            <option value="{{ $key }}" @selected($key == $log_status_dasar['ref_pindah'])>{{ $val }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="alamat_tujuan">Alamat Tujuan</label>
                    <textarea id="alamat_tujuan" name="alamat_tujuan" class="form-control input-sm required" placeholder="Alamat Tujuan" rows="5">{{ $log_status_dasar['alamat_tujuan'] }}</textarea>
                </div>
            @endif
            @if ($log_status_dasar['kode_peristiwa'] == App\Models\LogPenduduk::BARU_PINDAH_MASUK)
                <div class="form-group">
                    <label for="alamat_sebelumnya">Alamat Sebelumnya</label>
                    <textarea id="alamat_sebelumnya" name="alamat_sebelumnya" class="form-control input-sm required" placeholder="Alamat Sebelumnya" rows="5">{{ $log_status_dasar->penduduk->alamat_sebelumnya }}</textarea>
                </div>
            @endif
            <div class="form-group">
                <label for="tgl_peristiwa">Tanggal Peristiwa</label>
                <div class="input-group input-group-sm date">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <input class="form-control input-sm pull-right required tgl_minimal" id="tgl_1" name="tgl_peristiwa" type="text" data-tgl-lebih-besar="#tgl_lapor" value="{{ tgl_indo_out($log_status_dasar['tgl_peristiwa']) }}">
                </div>
            </div>
            <div class="form-group">
                <label for="tgl_lapor">Tanggal Lapor</label>
                <div class="input-group input-group-sm date">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar"></i>
                    </div>
                    <input class="form-control input-sm pull-right tgl_indo required" id="tgl_lapor" name="tgl_lapor" type="text" value="{{ tgl_indo_out($log_status_dasar['tgl_lapor']) }}">
                </div>
            </div>
            <div class="form-group">
                <label for="catatan">Catatan Peristiwa</label>
                <textarea id="catatan" name="catatan" class="form-control input-sm" placeholder="Catatan" rows="5" style="resize:none;">{{ $log_status_dasar['catatan'] }}</textarea>
                <span class="help-block"><code>*mati/hilang terangkan penyebabnya, pindah tuliskan alamat
                        pindah</code></span>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <?= batal() ?>
        <button type="submit" class="btn btn-social btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Simpan</button>
    </div>
</form>
<script type="text/javascript">
    $('document').ready(function() {
        $(".modal #file_browser").click(function(e) {
            e.preventDefault();
            $("#file").click();
        });
        $('#file').change(function() {
            $('#file_path').val($(this).val());
        });
    })

    $('#tgl_1').datetimepicker({
        format: 'DD-MM-YYYY',
        locale: 'id'
    });

    $('#tgl_lapor').datetimepicker({
        format: 'DD-MM-YYYY',
        locale: 'id'
    });

    setTimeout(function() {
        $("#tgl_lapor").rules('add', {
            tgl_lebih_besar: "input[name='tgl_peristiwa']",
            messages: {
                tgl_lebih_besar: "Tanggal lapor harus sama atau lebih besar dari tanggal peristiwa."
            }
        })
    }, 500);
</script>
