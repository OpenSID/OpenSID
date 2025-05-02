<div class="form-group konfirmasi">
    <label for="keperluan" class="col-sm-3 control-label">Tempat / Tanggal Lahir / Umur</label>
    <div class="col-sm-4">
        <input class="form-control input-sm" type="text" value="{{ strtoupper($individu->tempatlahir) }}" disabled>
    </div>
    <div class="col-sm-2">
        <input class="form-control input-sm" type="text" value="{{ strtoupper(tgl_indo($individu->tanggallahir)) }}" disabled>
    </div>
    <div class="col-sm-2">
        <input class="form-control input-sm" type="text" value="{{ strtoupper($individu->umur) }} TAHUN" disabled>
    </div>
</div>
<div class="form-group konfirmasi">
    <label for="keperluan" class="col-sm-3 control-label">Alamat</label>
    <div class="col-sm-8">
        <input class="form-control input-sm" type="text" value="{{ strtoupper($individu->alamat_wilayah) }}" disabled>
    </div>
</div>
<div class="form-group konfirmasi">
    <label for="keperluan" class="col-sm-3 control-label">Pendidikan / Warga Negara /Agama</label>
    <div class="col-sm-4">
        <input class="form-control input-sm" type="text" value="{{ strtoupper($individu->pendidikanKK->nama) }}" disabled>
    </div>
    <div class="col-sm-2">
        <input class="form-control input-sm" type="text" value="{{ strtoupper($individu->warganegara->nama) }}" disabled>
    </div>
    <div class="col-sm-2">
        <input class="form-control input-sm" type="text" value="{{ strtoupper($individu->agama->nama) }}" disabled>
    </div>
</div>
<div class="form-group konfirmasi">
    <label for="persyaratan" class="col-sm-3 control-label">Dokumen Kelengkapan / Syarat</label>
    <div class="col-sm-8">
        <a href="#" class="btn btn-social btn-success btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" data-remote="false" data-toggle="modal" data-target="#daftar-dokumen-{{ $individu->id }}" data-title="Daftar Dokumen"><i
                class="fa fa-book"
            ></i> Daftar Dokumen</a>
        <a href="{{ site_url("penduduk/dokumen/{$individu->id}") }}" class="@disabled(empty($individu->id)) btn btn-social btn-primary btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block" target="_blank"><i class="fa fa-gears"></i> Manajemen Dokumen
        </a>
    </div>
</div>

<div class="modal fade in" id="daftar-dokumen-{{ $individu->id }}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Daftar Dokumen</h4>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered dataTable table-striped table-hover tabel-daftar">
                        <thead class="bg-gray disabled color-palette">
                            <tr>
                                <th>No</th>
                                <th>Aksi</th>
                                <th>Nama Dokumen</th>
                                <th>Tgl. Unggah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($list_dokumen as $key => $data)
                                <tr>
                                    <td class="padat">{{ $key + 1 }}</td>
                                    <td class="aksi"><a href="{{ site_url("penduduk/unduh_berkas/{$data['id']}") }}" class="btn bg-purple btn-sm" title="Unduh Dokumen"><i class="fa fa-download"></i></a></td>
                                    <td>{{ $data['nama'] }}</td>
                                    <td class="padat">{{ tgl_indo2($data['tgl_upload']) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td class="padat" colspan="4">Data tidak tersedia</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
