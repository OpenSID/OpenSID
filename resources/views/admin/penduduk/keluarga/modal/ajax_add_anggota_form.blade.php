@include('admin.layouts.components.form_modal_validasi')
<form action="{{ $form_action }}" method="post" id="validasi">
    <div class='modal-body'>
        <div class="row">
            <div class="col-sm-12">
                <div class="box box-danger">
                    <div class="box-body">
                        <div class="table-responsive">
                            <table id="tabel2" class="table table-bordered dataTable table-hover nowrap">
                                <thead class="bg-gray disabled color-palette">
                                    <tr>
                                        <th>No</th>
                                        <th>NIK</th>
                                        <th>Nama</th>
                                        <th>Hubungan</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($main as $data)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $data->nik }}</td>
                                            <td>{{ $data->nama }}</td>
                                            <td>{{ \App\Enums\SHDKEnum::valueOf($data->kk_level) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="box box-danger">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="nik">NIK / Nama Penduduk (dari penduduk yang tidak memiliki No. KK)</label>
                            @if (!$penduduk->isEmpty())
                                <select class="form-control input-sm select2 required" id="nik" name="nik" style="width:100%;">
                                    <option option value="">-- Silakan Cari NIK / Nama Penduduk --</option>
                                    @foreach ($penduduk as $data)
                                        <option value="{{ $data->id }}">NIK
                                            :{{ $data->nik . ' - ' . $data->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                <div class="alert alert-danger" role="alert">
                                    <p>Tidak ada penduduk 'lepas' yang bukan anggota Keluarga. Silakan masukkan dulu
                                        data
                                        penduduk yang akan ditambahkan atau pecahkan dari KK yang ada. Pastikan penduduk
                                        tersebut tidak diisi data nomor KK-nya.</p>
                                </div>
                            @endif
                        </div>
                        @if (!$penduduk->isEmpty())
                            <div class="form-group">
                                <label for="kk_level">Hubungan Keluarga</label>
                                <select class="form-control input-sm select2 required" id="kk_level" name="kk_level" style="width:100%;">
                                    <option option value="">-- Silakan Cari Hubungan Keluarga --</option>
                                    @foreach ($hubungan as $data)
                                        <option value="{{ $data->id }}">{{ $data->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="reset" class="btn btn-social btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i> Tutup</button>
            @if (!$penduduk->isEmpty())
                <button type="submit" class="btn btn-social btn-info btn-sm" id="ok"><i class='fa fa-check'></i> Simpan</button>
            @endif
        </div>
    </div>
</form>
