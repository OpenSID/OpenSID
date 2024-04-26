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
                @foreach ($list_dokumen as $data)
                    <tr>
                        <td class="padat">{{ $loop->iteration }}</td>
                        <td class="aksi"><a href="{{ ci_route('penduduk.unduh_berkas', $data->id) }}" class="btn bg-purple btn-sm" title="Unduh Dokumen"><i class="fa fa-download"></i></a></td>
                        <td>{{ $data->nama }}</td>
                        <td class="padat">{{ tgl_indo2($data->tgl_upload) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
