@extends('layanan_mandiri.layouts.index')

@section('content')
    <div class="box box-solid">
        <div class="box-header with-border bg-blue">
            <h4 class="box-title">DOKUMEN</h4>
        </div>
        <div class="box-body box-line">
            <div class="form-group">
                <a href="{{ site_url('layanan-mandiri/dokumen') }}" class="btn bg-aqua btn-social"><i class="fa fa-arrow-circle-left "></i>Kembali ke Dokumen</a>
            </div>
        </div>
        <div class="box-body box-line">
            <h4><b>{{ $aksi }} DOKUMEN</b></h4>
        </div>

        <div class="box-body">
            @if (session('notif'))
                @php
                    $alertClass = session('notif')['status'] == 'success' ? 'alert-success' : 'alert-danger';
                @endphp
                <div class="alert {{ $alertClass }}" role="alert">
                    {{ session('notif')['pesan'] }}
                </div>
            @endif
            <form id="validasi" action="{{ $form_action }}" method="POST" enctype="multipart/form-data">
                <input type="number" class="hidden" name="id_pend" value="{{ $id_pend }}" />
                <div class="form-group">
                    <label for="nama_dokumen">Nama Dokumen</label>
                    <input id="nama_dokumen" name="nama" class="form-control required {{ $cek_anjungan['keyboard'] == 1 ? 'kbvtext' : '' }}" type="text" placeholder="Nama Dokumen" value="{{ $dokumen['nama'] }}" />
                </div>
                <div class="form-group">
                    <label for="jenis">Jenis Dokumen</label>
                    <select class="form-control select2 required" name="id_syarat" id="id_syarat">
                        <option value=""> -- Pilih Jenis Dokumen -- </option>
                        @foreach ($jenis_syarat_surat as $data)
                            <option value="{{ $data->ref_syarat_id }}" {{ $data->ref_syarat_id == $dokumen['id_syarat'] ? 'selected' : '' }}>
                                {{ $data->ref_syarat_nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="file">File Dokumen</label>
                    <div class="input-group">
                        <input type="text" class="form-control @if (!$dokumen['id']) required @endif" id="file_path" name="satuan" readonly />
                        <input type="file" class="hidden" id="file" name="satuan" accept=".jpg,.jpeg,.png,.pdf" />
                        <input type="hidden" name="old_file" value="{{ $dokumen['satuan'] }}" />
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-danger btn-flat" onclick="kamera();" id="ambil_kamera"><i class="fa fa-camera"></i> Kamera</button>
                            <button type="button" class="btn btn-info btn-flat" id="file_browser"><i class="fa fa-search"></i> Browse</button>
                        </span>
                    </div>
                </div>
                <span class="help-block"><code>Kosongkan jika tidak ingin mengubah dokumen. Ukuran maksimal <strong>{{ max_upload() }} MB</strong>.</code></span>
                </hr>
                @if (!empty($kk))
                    <p><strong>Centang jika dokumen yang diupload berlaku juga untuk anggota keluarga di bawah ini. </strong></p>
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-data">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>NIK</th>
                                    <th>Nama</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kk[0]->anggota as $row => $item)
                                    @if ($item['nik'] != $nik)
                                        <tr>
                                            <td class="padat">
                                                <input type="checkbox" name="anggota_kk[]" value="{{ $item['id'] }}" {{ in_array($item['id'], $anggota ?? []) ? 'checked' : '' }}>
                                            </td>
                                            <td>{{ $item['nik'] }}</td>
                                            <td>{{ $item['nama'] }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <hr>
                @endif

                <button type="submit" class="btn btn-social btn-info"><i class="fa fa-check"></i> Simpan</button>
            </form>
        </div>
    </div>
    @include('admin.layouts.components.capture')
@endsection
