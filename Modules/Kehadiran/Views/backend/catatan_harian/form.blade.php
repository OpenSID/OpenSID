@extends('admin.layouts.index')
@include('admin.layouts.components.asset_form_request')

@php
    $isView = $isView ?? false;
@endphp

@section('title')
    <h1>
        {{ $action }} Catatan Harian Kerja
        <small>{{ $action }}</small>
    </h1>
@endsection

@section('breadcrumb')
    <li><a href="{{ route('kehadiran_catatan_harian.index') }}">Catatan Harian Kerja</a></li>
    <li class="active">{{ $action }}</li>
@endsection

@section('content')
    <div class="box box-info">
        <div class="box-header with-border">
            @include('admin.layouts.components.tombol_kembali', ['url' => ci_route('kehadiran_catatan_harian'), 'label' => 'Daftar Catatan Harian'])
        </div>

        @if($isView)
            <div class="box-body form-horizontal">
        @else
            {!! form_open($form_action, 'id="form_validasi" class="form-horizontal" enctype="multipart/form-data"') !!}
                {{-- Hidden input untuk track foto yang akan dihapus --}}
                <input type="hidden" id="fotos_to_delete" name="fotos_to_delete" value="">
            <div class="box-body">
        @endif
                <div class="form-group">
                    <label class="col-sm-2 control-label">Tanggal <span class="text-red">*</span></label>
                    <div class="col-sm-3">
                        <input type="date" name="tanggal" class="form-control input-sm" 
                            value="{{ old('tanggal', $catatan_harian?->tanggal?->format('Y-m-d') ?? date('Y-m-d')) }}"
                            max="{{ date('Y-m-d') }}"
                            {{ $isView ? 'disabled' : '' }}>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Nama Perangkat</label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control input-sm" value="{{ $pamong?->pamong_nama ?? '-' }}" disabled>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Jabatan</label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control input-sm" value="{{ $pamong?->jabatan?->nama ?? '-' }}" disabled>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Uraian Kegiatan <span class="text-red">*</span></label>
                    <div class="col-sm-9">
                        <textarea name="uraian_kegiatan" class="form-control input-sm" rows="4" 
                            placeholder="Deskripsi kegiatan yang dilakukan"
                            {{ $isView ? 'disabled' : '' }}>{{ old('uraian_kegiatan', $catatan_harian?->uraian_kegiatan ?? '') }}</textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Lokasi Kegiatan <span class="text-red">*</span></label>
                    <div class="col-sm-9">
                        <input type="text" name="lokasi_kegiatan" class="form-control input-sm" 
                            placeholder="Tempat kegiatan berlangsung" 
                            value="{{ old('lokasi_kegiatan', $catatan_harian?->lokasi_kegiatan ?? '') }}"
                            {{ $isView ? 'disabled' : '' }}>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Hasil yang Diharapkan</label>
                    <div class="col-sm-9">
                        <textarea name="hasil_diharapkan" class="form-control input-sm" rows="3" 
                            placeholder="Hasil yang diharapkan (opsional)"
                            {{ $isView ? 'disabled' : '' }}>{{ old('hasil_diharapkan', $catatan_harian?->hasil_diharapkan ?? '') }}</textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-2 control-label">Foto Kegiatan</label>
                    <div class="col-sm-9">
                        {{-- Foto Container Panel --}}
                        <div class="panel panel-default">
                            <div class="panel-body" style="padding: 15px;">
                                {{-- Foto Cards Grid --}}
                                <div id="file-inputs-container" class="row">
                                    {{-- Existing Fotos --}}
                                    @if($catatan_harian && $catatan_harian->fotos->isNotEmpty())
                                        @foreach($catatan_harian->fotos->take(5) as $index => $foto)
                                            <div class="file-input-group col-sm-4 col-xs-6" style="margin-bottom: 15px;">
                                                <div style="border: 2px solid #17a2b8; border-radius: 6px; padding: 12px; background-color: #f9f9f9; text-align: center; position: relative; min-height: 280px; display: flex; flex-direction: column; justify-content: space-between;">
                                                    <div style="font-weight: 600; color: #333; font-size: 14px; margin-bottom: 8px;">Foto {{ $index + 1 }}</div>
                                                    
                                                    {{-- Preview Area --}}
                                                    <div class="photo-preview" style="flex-grow: 1; display: flex; align-items: center; justify-content: center; margin-bottom: 8px; min-height: 180px; overflow: hidden; position: relative;">
                                                        <img class="original-image" src="{{ base_url($foto->file_path) }}" style="max-width: 100%; max-height: 180px; object-fit: contain; cursor: pointer;" onclick="showPhotoWithNumber(this)">
                                                    </div>

                                                    @if($isView)
                                                        {{-- View Mode: Hanya tombol lihat --}}
                                                        <div class="button-group" style="display: flex; gap: 6px;">
                                                            <button type="button" class="btn btn-sm btn-info" style="flex: 1; background-color: #17a2b8; color: white; border-color: #17a2b8;" onclick="showPhotoWithNumber(this.parentElement.parentElement.querySelector('.original-image'))">
                                                                <i class="fa fa-eye"></i> Lihat
                                                            </button>
                                                        </div>
                                                    @else
                                                        {{-- Edit Mode: Tombol ganti dan hapus --}}
                                                        {{-- File Input (Hidden) --}}
                                                        <input type="file" class="file-input existing-file-input" name="fotos_replace[]" accept=".jpg,.jpeg,.png" style="display: none;">

                                                        <div class="button-group" style="display: flex; gap: 6px;">
                                                            <button type="button" class="btn btn-sm file-browser" style="flex: 1; background-color: #17a2b8; color: white; border-color: #17a2b8;">
                                                                <i class="fa fa-pencil"></i> Ganti
                                                            </button>
                                                            <button type="button" class="btn btn-sm btn-danger delete-foto" data-foto-id="{{ $foto->uuid }}" style="flex: 0 0 auto; padding: 6px 10px;">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif

                                    {{-- Upload Card Template (Initial) - Hanya tampil jika tidak ada foto dan tidak dalam view mode --}}
                                    @if(!$isView && (!$catatan_harian || $catatan_harian->fotos->isEmpty()))
                                    <div class="file-input-group col-sm-4 col-xs-6" style="margin-bottom: 15px;">
                                        <div style="border: 2px dashed #17a2b8; border-radius: 6px; padding: 12px; background-color: #f9f9f9; text-align: center; position: relative; min-height: 280px; display: flex; flex-direction: column; justify-content: space-between;">
                                            <div style="font-weight: 600; color: #333; font-size: 14px;">Foto 1</div>
                                            
                                            {{-- Preview Area --}}
                                            <div class="photo-preview" style="flex-grow: 1; display: flex; align-items: center; justify-content: center; margin-bottom: 8px; min-height: 180px; overflow: hidden; position: relative;">
                                                <div class="placeholder-text" style="text-align: center; color: #999; z-index: 1;">
                                                    <i class="fa fa-camera" style="font-size: 48px; margin-bottom: 5px; display: block;"></i>
                                                    <small style="font-size: 13px;">Pilih foto</small>
                                                </div>
                                                <img class="preview-image" style="position: absolute; max-width: 100%; max-height: 180px; object-fit: contain; display: none; cursor: pointer;" onclick="showPhotoWithNumber(this)">
                                            </div>

                                            {{-- Hidden File Input --}}
                                            <input type="file" class="hidden file-input" name="fotos[]" accept=".jpg,.jpeg,.png">

                                            {{-- Buttons --}}
                                            <div style="display: flex; gap: 6px;">
                                                <button type="button" class="btn btn-sm file-browser" style="flex: 1; background-color: #17a2b8; color: white; border-color: #17a2b8;">
                                                    <i class="fa fa-search"></i> Browse
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger btn-remove-file" disabled style="flex: 0 0 auto; padding: 6px 10px;">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                
                                {{-- Add Button - Hanya tampil jika tidak dalam view mode --}}
                                @if(!$isView)
                                <div style="margin-top: 12px;">
                                    <button type="button" class="btn btn-success btn-sm" id="btn-add-file">
                                        <i class="fa fa-plus"></i> Tambah Foto
                                    </button>
                                    <small class="text-muted" style="margin-left: 10px;">
                                        Max {{ max_upload() }}MB • JPG, JPEG, PNG • Max 5 foto
                                    </small>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if(! $isView)
                {{-- Edit Mode: Tombol Batal dan Simpan --}}
                <div class="box-footer">
                    <button type="reset" class="btn btn-social btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
                    <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
                </div>
                </form>
            @endif
        </div>


@endsection

@push('scripts')
<script>
    const MAX_FILE_INPUTS = 5;

    // Helper function untuk show image dengan SweetAlert
    function showImageSweetAlert(imageSrc, title) {
        Swal.fire({
            title: title,
            html: '<img src="' + imageSrc + '" style="max-width: 90%; max-height: 500px; object-fit: contain; display: block; margin: 0 auto;">',
            confirmButtonText: 'Tutup',
            didOpen: (modal) => {
                modal.querySelector('.swal2-html-container').style.textAlign = 'center';
            }
        });
    }

    // Wrapper function untuk get foto number dari card dan show image
    function showPhotoWithNumber(imgElement) {
        const $card = $(imgElement).closest('.file-input-group');
        const headerText = $card.find('div').filter(function() {
            return $(this).text().trim().match(/^Foto \d+$/);
        }).text().trim();
        showImageSweetAlert(imgElement.src, headerText);
    }

    // Renumber semua foto cards agar selalu berurut
    function renumberPhotoCards() {
        const $container = $('#file-inputs-container');
        const $groups = $container.find('.file-input-group');
        
        $groups.each(function(index) {
            // Find the div that contains "Foto" text and update it
            $(this).find('div').filter(function() {
                return $(this).text().trim().match(/^Foto \d+$/);
            }).text('Foto ' + (index + 1));
        });
    }

    // Check jika tidak ada existing photos, tampilkan card upload kosong
    function ensureUploadCardExists() {
        const $container = $('#file-inputs-container');
        const numExistingPhotos = $container.find('[data-foto-id]').length;
        
        if (numExistingPhotos === 0) {
            // Check apakah sudah ada card upload
            const hasUploadCard = $container.find('.file-input-group').length > 0;
            
            if (!hasUploadCard) {
                // Add empty upload card
                const $uploadCard = `
                    <div class="file-input-group col-sm-4 col-xs-6" style="margin-bottom: 15px;">
                        <div style="border: 2px dashed #17a2b8; border-radius: 6px; padding: 12px; background-color: #f9f9f9; text-align: center; position: relative; min-height: 280px; display: flex; flex-direction: column; justify-content: space-between;">
                            <div style="font-weight: 600; color: #333; font-size: 14px;">Foto 1</div>
                            
                            <div class="photo-preview" style="flex-grow: 1; display: flex; align-items: center; justify-content: center; margin-bottom: 8px; min-height: 180px; overflow: hidden; position: relative;">
                                <div class="placeholder-text" style="text-align: center; color: #999; z-index: 1;">
                                    <i class="fa fa-camera" style="font-size: 48px; margin-bottom: 5px; display: block;"></i>
                                    <small style="font-size: 13px;">Pilih foto</small>
                                </div>
                                <img class="preview-image" style="position: absolute; max-width: 100%; max-height: 180px; object-fit: contain; display: none; cursor: pointer;" onclick="showPhotoWithNumber(this)">
                            </div>

                            <input type="file" class="hidden file-input" name="fotos[]" accept=".jpg,.jpeg,.png">

                            <div style="display: flex; gap: 6px;">
                                <button type="button" class="btn btn-sm file-browser" style="flex: 1; background-color: #17a2b8; color: white; border-color: #17a2b8;">
                                    <i class="fa fa-search"></i> Browse
                                </button>
                                <button type="button" class="btn btn-sm btn-danger btn-remove-file" disabled style="flex: 0 0 auto; padding: 6px 10px;">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                $container.append($uploadCard);
            }
        }
    }

    // Update visibility tombol remove dan status tombol add
    function updateFileInputUI() {
        const $container = $('#file-inputs-container');
        const $groups = $container.find('.file-input-group');
        const count = $groups.length;

        // Enable/disable tombol remove
        $groups.each(function(index) {
            const $removeBtn = $(this).find('.btn-remove-file');
            if (count > 1) {
                $removeBtn.prop('disabled', false).removeClass('disabled');
            } else {
                $removeBtn.prop('disabled', true).addClass('disabled');
            }
        });

        // Tampilkan/sembunyikan tombol add
        if (count >= MAX_FILE_INPUTS) {
            $('#btn-add-file').button('disable').prop('disabled', true).addClass('disabled');
        } else {
            $('#btn-add-file').button('enable').prop('disabled', false).removeClass('disabled');
        }
    }

    // Tambah file input baru
    $(document).on('click', '#btn-add-file', function() {
        const $container = $('#file-inputs-container');
        const $groups = $container.find('.file-input-group');
        const count = $groups.length;

        if (count < MAX_FILE_INPUTS) {
            // Hitung berdasarkan existing fotos + new cards
            const numExistingFotos = $container.find('[data-foto-id]').length;
            const numNewCards = $groups.length - numExistingFotos;
            const fotoNumber = numExistingFotos + numNewCards + 1;
            
            const $newGroup = `
                <div class="file-input-group col-sm-4 col-xs-6" style="margin-bottom: 15px;">
                    <div style="border: 2px dashed #17a2b8; border-radius: 6px; padding: 12px; background-color: #f9f9f9; text-align: center; position: relative; min-height: 280px; display: flex; flex-direction: column; justify-content: space-between;">
                        <div style="font-weight: 600; color: #333; font-size: 14px; margin-bottom: 8px;">Foto ${fotoNumber}</div>
                        
                        {{-- Preview Area --}}
                        <div class="photo-preview" style="flex-grow: 1; display: flex; align-items: center; justify-content: center; margin-bottom: 8px; min-height: 180px; overflow: hidden; position: relative;">
                            <div class="placeholder-text" style="text-align: center; color: #999; z-index: 1;">
                                <i class="fa fa-camera" style="font-size: 48px; margin-bottom: 5px; display: block;"></i>
                                <small style="font-size: 13px;">Pilih foto</small>
                            </div>
                            <img class="preview-image" style="position: absolute; max-width: 100%; max-height: 180px; object-fit: contain; display: none; cursor: pointer;" onclick="showPhotoWithNumber(this)">
                        </div>

                        {{-- Hidden File Input --}}
                        <input type="file" class="hidden file-input" name="fotos[]" accept=".jpg,.jpeg,.png">

                        {{-- Buttons --}}
                        <div style="display: flex; gap: 6px;">
                            <button type="button" class="btn btn-sm file-browser" style="flex: 1; background-color: #17a2b8; color: white; border-color: #17a2b8;">
                                <i class="fa fa-search"></i> Browse
                            </button>
                            <button type="button" class="btn btn-sm btn-danger btn-remove-file" style="flex: 0 0 auto; padding: 6px 10px;">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
            $container.append($newGroup);
            renumberPhotoCards();
            updateFileInputUI();
        }
    });

    // Hapus file input
    $(document).on('click', '.btn-remove-file', function(e) {
        e.preventDefault();
        $(this).closest('.file-input-group').fadeOut(300, function() {
            $(this).remove();
            renumberPhotoCards();
            ensureUploadCardExists();
            updateFileInputUI();
        });
    });

    // Browse file
    $(document).on('click', '.file-browser', function(e) {
        e.preventDefault();
        $(this).closest('.file-input-group').find('.file-input').click();
    });

    // Update preview saat file dipilih
    $(document).on('change', '.file-input', function() {
        const file = this.files[0];
        if (!file) return;

        const $group = $(this).closest('.file-input-group');
        const $preview = $group.find('.photo-preview');
        const $placeholderText = $group.find('.placeholder-text');
        const $previewImage = $group.find('.preview-image');

        // Create image preview
        const reader = new FileReader();
        reader.onload = function(e) {
            $previewImage.attr('src', e.target.result).show();
            $placeholderText.hide();
        };
        reader.readAsDataURL(file);
    });

    // Handle delete foto - Collect untuk batch delete saat form submit
    $(document).on('click', '.delete-foto', function() {
        const fotoId = $(this).data('foto-id');
        const $fotoElement = $(this).closest('.file-input-group');

        Swal.fire({
            icon: 'question',
            title: 'Hapus Foto?',
            text: 'Foto akan dihapus setelah Anda klik Simpan.',
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#999'
        }).then((result) => {
            if (result.isConfirmed) {
                // Add foto ID to deletion list
                const currentList = $('#fotos_to_delete').val();
                const newList = currentList ? currentList + ',' + fotoId : fotoId;
                $('#fotos_to_delete').val(newList);

                // Remove element dari DOM
                $fotoElement.fadeOut(300, function() {
                    $(this).remove();
                    // Renumber remaining photos
                    renumberPhotoCards();
                    // Ensure ada card upload jika tidak ada foto
                    ensureUploadCardExists();
                    updateFileInputUI();
                });

                // Show info message
                Swal.fire({
                    icon: 'info',
                    title: 'Ditandai untuk Dihapus',
                    text: 'Foto akan dihapus saat Anda klik Simpan.',
                    toast: true,
                    position: 'top-end',
                    timer: 2000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
            }
        });
    });

    // Handle existing photo file change (for replacing photos)
    $(document).on('change', '.existing-file-input', function() {
        const file = this.files[0];
        if (!file) return;

        const $group = $(this).closest('.file-input-group');
        const $originalImage = $group.find('.original-image');
        const $previewImage = $group.find('.preview-image');
        const $placeholderText = $group.find('.placeholder-text');
        const $fileInput = this;
        const fotoId = $group.find('.delete-foto').data('foto-id');

        // Create image preview
        const reader = new FileReader();
        reader.onload = function(e) {
            $originalImage.attr('src', e.target.result).show();
            $previewImage.hide();
            if ($placeholderText.length) $placeholderText.hide();
        };
        reader.readAsDataURL(file);

        // Add foto ID to deletion list immediately jika foto existing
        if (fotoId) {
            const currentList = $('#fotos_to_delete').val();
            const newList = currentList ? currentList + ',' + fotoId : fotoId;
            $('#fotos_to_delete').val(newList);
        }

        // Reset file input
        $fileInput.value = '';
    });

    // Handle form submit - delete photos in list
    $(document).on('submit', '#form_validasi', function(e) {
        const fotosToDelete = $('#fotos_to_delete').val();
        
        if (!fotosToDelete) {
            // No photos to delete, continue normally
            return true;
        }

        // Prevent default submission
        e.preventDefault();

        const form = this;
        const fotosArray = fotosToDelete.split(',');

        // Show confirmation
        Swal.fire({
            icon: 'question',
            title: 'Proses Perubahan Foto?',
            text: 'Akan menghapus ' + fotosArray.length + ' foto yang ditandai. Lanjutkan?',
            showCancelButton: true,
            confirmButtonText: 'Ya, Lanjutkan',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#17a2b8',
            cancelButtonColor: '#999'
        }).then((result) => {
            if (result.isConfirmed) {
                // Delete photos via AJAX
                $.ajax({
                    url: SITE_URL + '/kehadiran_catatan_harian/delete-fotos-batch',
                    type: 'POST',
                    data: {
                        fotos: fotosArray
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        // Now submit the form
                        form.submit();
                    },
                    error: function(xhr) {
                        const errorMsg = xhr.responseJSON?.message || 'Gagal menghapus foto lama';
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: errorMsg
                        });
                    }
                });
            }
        });
    });

    // Initialize UI on page load
    $(document).ready(function() {
        renumberPhotoCards();
        ensureUploadCardExists();
        updateFileInputUI();

        // Store original photo container HTML untuk reset
        const originalPhotosHtml = $('#file-inputs-container').html();

        // Handle reset button
        $('#form_validasi').on('reset', function(e) {
            setTimeout(function() {
                // Restore original photos HTML
                $('#file-inputs-container').html(originalPhotosHtml);
                
                // Clear fotos_to_delete
                $('#fotos_to_delete').val('');
                
                // Reinitialize UI
                renumberPhotoCards();
                ensureUploadCardExists();
                updateFileInputUI();
            }, 50);
        });
    });
</script>
@endpush
