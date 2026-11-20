@extends('admin.layouts.index')
@include('admin.layouts.components.asset_form_request')
@section('title')
<h1>
    Lokasi
    <small>{{ $aksi }} Data</small>
</h1>
@endsection

@section('breadcrumb')
<li><a href="{{ ci_route('plan.index') }}"> Lokasi</a></li>
<li class="active">{{ $aksi }} Data</li>
@endsection

@section('content')
@include('admin.layouts.components.notifikasi')
<div class="row">
    <div class="col-md-3">
        @include('admin.peta.nav')
    </div>
    <div class="col-md-9">
        {!! form_open_multipart($form_action, 'class="form-horizontal" id="form_validasi"') !!}
        <div class="box box-info">
            <div class="box-header with-border">
                <x-kembali-button judul="Kembali Ke Daftar Lokasi" url="plan/index" />
            </div>
            <div class="box-body">
                <div class="form-group">
                    <label class="control-label col-sm-3">Nama Lokasi / Properti <span class="text-red">*</span></label>
                    <div class="col-sm-7">
                        <input name="nama" class="form-control input-sm" maxlength="{{ PEMETAAN_NAMA_MAX_LENGTH }}" type="text" value="{{ $plan?->nama ?? '' }}" />
                    </div>
                </div>

                <!-- DROPDOWN JENIS (ROOT) -->
                <div class="form-group">
                    <label class="control-label col-sm-3">Jenis <span class="text-red">*</span></label>
                    <div class="col-sm-7">
                        <select class="form-control input-sm select2" id="jenis" name="jenis">
                            <option value="">Pilih Jenis</option>
                            @foreach ($list_jenis as $data)
                            <option value="{{ $data->id }}" @selected($data->id == $parent)>{{ $data->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- DROPDOWN KATEGORI (CHILD) -->
                <div class="form-group">
                    <label class="control-label col-sm-3">Kategori <span class="text-red">*</span></label>
                    <div class="col-sm-7">
                        <select class="form-control input-sm select2" id="ref_point" name="ref_point">
                            <option value="">Pilih Kategori</option>
                            @foreach ($list_kategori as $data)
                            <option value="{{ $data->id }}" @selected($data->id == ($plan?->ref_point ?? 0))>{{ $data->nama }}</option>
                            @endforeach
                        </select>
                        <code class="text-red small">Pilih Jenis terlebih dahulu untuk menampilkan Kategori</code>
                    </div>
                </div>

                <?php if ($plan?->foto_lokasi) : ?>
                    <div class="form-group">
                        <label class="control-label col-sm-3"></label>
                        <div class="col-sm-7">
                            <img class="attachment-img img-responsive img-circle" src="{{ $plan->foto_lokasi }}" alt="Foto">
                        </div>
                    </div>
                <?php endif; ?>
                <div class="form-group">
                    <label class="control-label col-sm-3">{{ $aksi == 'Ubah' ? 'Ganti Foto' : 'Tambah Foto Lokasi' }}</label>
                    <div class="col-sm-7">
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control" id="file_path">
                            <input id="file" type="file" class="hidden" name="foto" accept=".gif,.jpg,.jpeg,.png,.webp">
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-info " id="file_browser"><i class="fa fa-search"></i> Browse</button>
                            </span>
                        </div>
                        <code class="text-red small">{{ $aksi == 'Ubah' ? 'Kosongkan jika tidak ingin mengubah foto.' : 'Format: gif, jpg, jpeg, png, webp' }}</code>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label">Keterangan <span class="text-red">*</span></label>
                    <div class="col-sm-7">
                        <textarea id="desk" name="desk" class="form-control input-sm" style="height: 200px;white-space: pre-wrap;">{{ $plan?->desk ?? '' }}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label" for="enabled">Status <span class="text-red">*</span></label>
                    <div class="col-sm-6">
                        <select name="enabled" id="enabled" class="form-control input-sm">
                            @foreach (\App\Enums\AktifEnum::all() as $value => $label)
                            <option value="{{ $value }}" @selected($plan->enabled==$value)>
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
        var selectedKategori = '{{ $plan->ref_point ?? "" }}';

        // Function untuk load kategori berdasarkan jenis
        function loadKategori(jenisId) {
            var $kategori = $('#ref_point');

            // Reset opsi tanpa destroy Select2
            $kategori.find('option:not(:first)').remove();
            $kategori.val('').trigger('change');

            if (!jenisId) {
                return;
            }

            // AJAX untuk mengambil kategori
            $.ajax({
                url: "{{ ci_route('plan.ajax_get_kategori') }}",
                method: 'POST',
                data: {
                    jenis_id: jenisId
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success && response.data.length > 0) {
                        $.each(response.data, function(key, value) {
                            var option = new Option(value.nama, value.id, false, false);
                            $kategori[0].appendChild(option); // bypass Select2's $.fn.append override
                        });

                        // Set nilai terpilih jika ada (mode edit)
                        if (selectedKategori) {
                            $kategori.val(selectedKategori).trigger('change');
                            selectedKategori = ''; // reset agar tidak dipaksa ulang saat jenis diubah
                        }
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error loading kategori:', error);
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
        // Trigger load kategori
        var jenisId = $('#jenis').val();
        if (jenisId) {
            loadKategori(jenisId);
        }
        @endif
    });
</script>
@endpush
@endsection