<form id="validasi" action="{{ $form_action }}" method="POST" enctype="multipart/form-data">
    <div class="modal-body">
        <div class="form-group">
            <label for="nama">Nama Dokumen</label>
            <input id="nama" name="nama" class="form-control input-sm nomor_sk required" type="text" placeholder="Nama Dokumen" value="{{ $dokumen['nama'] }}" />
            <input type="hidden" name="id_pend" value="{{ $penduduk['id'] }}" />
        </div>
        <div class="form-group">
            <label for="nama_dokumen">Jenis Dokumen</label>
            <select class="form-control required input-sm" name="id_syarat" id="id_syarat">
                <option value=""> -- Pilih Jenis Dokumen -- </option>
                @foreach ($jenis_syarat_surat as $data)
                    <option value="{{ $data['ref_syarat_id'] }}" @selected($data['ref_syarat_id'] == $dokumen['id_syarat'])>{{ $data['ref_syarat_nama'] }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="file">Pilih File:</label>
            <div class="input-group input-group-sm">
                <input type="text" class="form-control" id="file_path" name="satuan">
                <input type="file" class="hidden {{ $dokumen['id'] ? '' : 'required' }}" id="file" name="satuan" accept=".jpg,.jpeg,.png,.pdf" />
                <span class="input-group-btn">
                    <button type="button" class="btn btn-info " id="file_browser"><i class="fa fa-search"></i> Browse</button>
                </span>
            </div>
            <span class="help-block"><code>Kosongkan jika tidak ingin mengubah dokumen. Ukuran maksimal <strong>{{ max_upload() }} MB</strong>.</code></span>
        </div>
        <div class="form-group" id="dok_warga">
            <div class="input-group input-group-sm">
                <input type="checkbox" name="dok_warga" value="1" data="{!! json_encode($dokumen) !!}" {{ jecho($dokumen['dok_warga'], 1, 'checked') }}>
                <label>Boleh diubah oleh warga melalui Layanan Mandiri</label>
            </div>
        </div>
        @if (!empty($kk))
            <hr>
            <p><strong>Centang jika dokumen yang diupload berlaku juga untuk anggota keluarga di bawah ini. </strong></p>
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped table-sm">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">NIK</th>
                            <th scope="col">Nama</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($kk as $item)
                            @if ($item['nik'] != $penduduk['nik'])
                                <tr>
                                    <td><input type='checkbox' name='anggota_kk[]' value="{{ $item['id'] }}" {{ $item['checked'] ?? '' }} /></td>
                                    <td>{{ $item['nik'] }}</td>
                                    <td>{{ $item['nama'] }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
    <div class="modal-footer">
        {!! batal() !!}
        <button type="submit" class="btn btn-social btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Simpan</button>
    </div>
</form>
<script>
    $(document).ready(function() {
        $('#file_browser').click(function(e) {
            e.preventDefault();
            $('#file').click();
        });

        $('#file').change(function() {
            $('#file_path').val($(this).val());
        });

        $('#file_path').click(function() {
            $('#file_browser').click();
        });
    });
</script>
@include('admin.layouts.components.form_modal_validasi')
