@extends('admin.layouts.index')
@include('admin.layouts.components.asset_validasi')
@section('title')
    <h1>
        Pengaturan Garis
        <small>{{ $aksi }} Data</small>
    </h1>
@endsection

@section('breadcrumb')
    <li><a href="{{ ci_route('garis.index') }}"> Pengaturan Garis</a></li>
    <li class="active">{{ $aksi }} Data</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    <div class="row">
        <div class="col-md-3">
            @include('admin.peta.nav')
        </div>
        <div class="col-md-9">
            {!! form_open_multipart($form_action, 'class="form-horizontal" id="validasi"') !!}
            <div class="box box-info">
                <div class="box-header with-border">
                    <x-kembali-button judul="Kembali Ke Daftar Pengaturan Garis" url="garis/index" />
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label class="control-label col-sm-3">Nama Garis / Properti</label>
                        <div class="col-sm-7">
                            <input name="nama" class="form-control input-sm nomor_sk required" maxlength="100" type="text" value="{{ $garis->nama }}" />
                        </div>
                    </div>
                    
                    <!-- DROPDOWN JENIS (ROOT) -->
                    <div class="form-group">
                        <label class="control-label col-sm-3">Jenis</label>
                        <div class="col-sm-7">
                            <select class="form-control input-sm select2 required" id="jenis" name="jenis">
                                <option value="">Pilih Jenis</option>
                                @foreach ($list_jenis as $data)
                                    <option value="{{ $data->id }}" @selected($data->id == $parent)>{{ $data->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <!-- DROPDOWN KATEGORI (CHILD) -->
                    <div class="form-group">
                        <label class="control-label col-sm-3">Kategori</label>
                        <div class="col-sm-7">
                            <select class="form-control input-sm select2 required" id="ref_line" name="ref_line">
                                <option value="">Pilih Kategori</option>
                                @foreach ($list_kategori as $data)
                                    <option value="{{ $data->id }}" @selected($data->id == $garis->ref_line)>{{ $data->nama }}</option>
                                @endforeach
                            </select>
                            <p class="help-block small text-muted">Pilih Jenis terlebih dahulu untuk menampilkan Kategori</p>
                        </div>
                    </div>
                    
                    <?php if ($garis->foto_garis) : ?>
                    <div class="form-group">
                        <label class="control-label col-sm-3"></label>
                        <div class="col-sm-7">
                            <img class="attachment-img img-responsive img-circle" src="{{ $garis->foto_garis }}" alt="Foto">
                        </div>
                    </div>
                    <?php endif; ?>
                    <div class="form-group">
                        <label class="control-label col-sm-3">Ganti Foto</label>
                        <div class="col-sm-7">
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control" id="file_path">
                                <input id="file" type="file" class="hidden" name="foto" accept=".gif,.jpg,.jpeg,.png,.webp">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-info " id="file_browser"><i class="fa fa-search"></i> Browse</button>
                                </span>
                            </div>
                            <p class="help-block small text-red">Kosongkan jika tidak ingin mengubah foto.</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Keterangan</label>
                        <div class="col-sm-7">
                            <textarea id="desk" name="desk" class="form-control input-sm required" style="height: 200px;white-space: pre-wrap;">{{ $garis->desk }}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="enabled">Status</label>
                        <div class="col-sm-6">
                            <select name="enabled" id="enabled" class="form-control input-sm required">
                                @foreach (\App\Enums\AktifEnum::all() as $value => $label)
                                <option value="{{ $value }}" @selected($garis->enabled==$value)>
                                    {{ $label }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class='box-footer'>
                    <div>
                        <button type='reset' class='btn btn-social btn-danger btn-sm'><i class='fa fa-times'></i>
                            Batal</button>
                        <button type='submit' class='btn btn-social btn-info btn-sm pull-right confirm'><i class='fa fa-check'></i> Simpan</button>
                    </div>
                </div>
            </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            // Simpan nilai kategori yang harus di-select (dari database saat edit)
            var selectedKategori = '{{ $garis->ref_line ?? "" }}';

            // Function untuk load kategori berdasarkan jenis
            function loadKategori(jenisId) {
                var $kategori = $('#ref_line');

                // Show loading state
                $kategori.html('<option value="">Memuat...</option>').prop('disabled', true);

                if (!jenisId) {
                    $kategori.html('<option value="">Pilih Kategori</option>').prop('disabled', false);
                    return;
                }

                // AJAX untuk mengambil kategori
                $.ajax({
                    url: "{{ ci_route('garis.ajax_get_kategori') }}",
                    type: 'GET',
                    data: {
                        jenis_id: jenisId
                    },
                    dataType: 'json',
                    success: function(response) {
                        // Reset dropdown
                        $kategori.html('<option value="">Pilih Kategori</option>');

                        if (response.success && response.data.length > 0) {
                            // Loop dan tambahkan option
                            $.each(response.data, function(key, value) {
                                var isSelected = (selectedKategori && value.id == selectedKategori);
                                var option = $('<option></option>')
                                    .attr('value', value.id)
                                    .text(value.nama);

                                // Set selected jika sesuai dengan data yang harus dipilih
                                if (isSelected) {
                                    option.prop('selected', true);
                                }

                                $kategori.append(option);
                            });
                        } else {
                            $kategori.html('<option value="">Tidak ada kategori</option>');
                        }

                        // Enable dropdown dan refresh select2
                        $kategori.prop('disabled', false);

                        // Refresh select2 dengan nilai yang sudah dipilih
                        if (typeof $kategori.select2 === 'function') {
                            $kategori.select2().trigger('change');
                        } else {
                            $kategori.trigger('change');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading kategori:', error);
                        $kategori.html('<option value="">Error memuat data</option>').prop('disabled', false);
                    }
                });
            }

            // Event saat dropdown Jenis berubah
            $('#jenis').on('change', function() {
                var jenisId = $(this).val();
                loadKategori(jenisId);
            });

            // Auto-load kategori saat edit (jika parent sudah ada)
            @if($parent > 0)
            var jenisId = $('#jenis').val();
            if (jenisId) {
                loadKategori(jenisId);
            }
            @endif
        });
    </script>
    @endpush
@endsection