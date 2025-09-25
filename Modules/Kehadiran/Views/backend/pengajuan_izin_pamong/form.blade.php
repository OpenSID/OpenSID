@include('admin.layouts.components.datetime_picker')
@extends('admin.layouts.index')

@section('title')
    <h1>
        Pengajuan Izin
        <small>{{ $action }} Data</small>
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ ci_route('kehadiran_pengajuan_izin_pamong') }}">Daftar Pengajuan Izin</a></li>
    <li class="active">{{ $action }} Data</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="box box-info">
        <div class="box-header with-border">
            @include('admin.layouts.components.tombol_kembali', ['url' => ci_route('kehadiran_pengajuan_izin_pamong'), 'label' => 'Daftar Pengajuan Izin'])
        </div>
        {!! form_open($form_action, 'class="form-horizontal" id="validasi" enctype="multipart/form-data"') !!}
         <div class="box-body">
            @if(isset($kehadiran_pengajuan_izin) && $kehadiran_pengajuan_izin->id)
                <input type="hidden" name="id" value="{{ $kehadiran_pengajuan_izin->id }}">
            @endif
            
            <div class="form-group">
                <label class="col-sm-3 control-label" for="jenis_izin">Jenis Izin <span class="text-red">*</span></label>
                <div class="col-sm-7">
                    <select name="jenis_izin" id="jenis_izin" class="form-control input-sm select2 required">
                        <option value="">Pilih Jenis Izin</option>
                        @foreach(\Modules\Kehadiran\Enums\JenisIzin::detailedOptions() as $key => $option)
                        <option value="{{ $key }}" data-description="{{ $option['description'] }}"
                            data-max-days="{{ $option['max_days'] }}"
                            {{ (old('jenis_izin', $kehadiran_pengajuan_izin->jenis_izin ?? '') == $key) ? 'selected' : '' }}>
                            {{ $option['label'] }}
                        </option>
                        @endforeach
                    </select>
                    <small class="text-muted" id="jenis-description"></small>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-3 control-label" for="tanggal_mulai">Tanggal Mulai <span class="text-red">*</span></label>
                <div class="col-sm-7">
                    <div class="input-group input-group-sm date">
                        <div class="input-group-addon" style="border-radius: 5px 0 0 5px">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" name="tanggal_mulai" id="tanggal_mulai" class="form-control input-sm datepicker required" 
                            placeholder="Tanggal Mulai Izin" style="border-radius: 0 5px 5px 0" readonly required
                            value="{{ old('tanggal_mulai', isset($kehadiran_pengajuan_izin->tanggal_mulai) ? $kehadiran_pengajuan_izin->tanggal_mulai?->format('d-m-Y') : '') }}">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label" for="tanggal_selesai">Tanggal Selesai <span class="text-red">*</span></label>
                <div class="col-sm-7">
                    <div class="input-group input-group-sm date">
                        <div class="input-group-addon" style="border-radius: 5px 0 0 5px">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" name="tanggal_selesai" id="tanggal_selesai" class="form-control input-sm datepicker required" 
                            placeholder="Tanggal Selesai Izin" style="border-radius: 0 5px 5px 0" readonly required
                            value="{{ old('tanggal_selesai', isset($kehadiran_pengajuan_izin->tanggal_selesai) ? $kehadiran_pengajuan_izin->tanggal_selesai?->format('d-m-Y') : '') }}">
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label" for="keterangan">Keterangan <span class="text-red">*</span></label>
                <div class="col-sm-7">
                    <textarea name="keterangan" id="keterangan" class="form-control input-sm required" rows="3" maxlength="500" style="resize:none;" 
                        placeholder="Jelaskan alasan pengajuan izin...">{{ old('keterangan', $kehadiran_pengajuan_izin->keterangan ?? '') }}</textarea>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label" for="lampiran">Lampiran <span class="text-red" id="lampiran-required" style="display: none;">*</span></label>
                <div class="col-sm-7">
                    @if(isset($kehadiran_pengajuan_izin->lampiran) && $kehadiran_pengajuan_izin->lampiran)
                        <div>
                            <label class="control-label">File Saat Ini:</label><br>
                            <i class="fa fa-file-pdf-o pop-up-file" aria-hidden="true" style="font-size: 60px; cursor: pointer; color: red;" 
                               data-title="Berkas {{ ucfirst($kehadiran_pengajuan_izin->jenis_izin ?? 'Lampiran') }}" 
                               data-url="{{ base_url('desa/upload/pengajuan_izin/' . $kehadiran_pengajuan_izin->lampiran) }}" 
                               title="{{ $kehadiran_pengajuan_izin->lampiran }}"></i>
                            <br><small class="text-info">{{ $kehadiran_pengajuan_izin->lampiran }}</small>
                            <input type="hidden" name="existing_lampiran" value="{{ $kehadiran_pengajuan_izin->lampiran }}">
                        </div>                                                
                    @endif
                    
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control file-path" name="file_path" placeholder="Pilih file..." readonly>
                        
                        <input type="file" class="hidden file-input" name="lampiran" accept=".pdf,.jpg,.jpeg,.png">
                        
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-info btn-flat file-browser">
                                <i class="fa fa-search"></i> Browse
                            </button>
                        </span>
                    </div>
                    <label class="help-block error" style="display: none"></label>
                    <label class="control-label text-danger">
                        Batas maksimal pengunggahan file: <strong>{{ max_upload() }} MB</strong>.
                        Hanya mendukung format dokumen (.pdf, .doc, .docx, .jpg, .jpeg, .png).
                        <span id="lampiran-note"></span>
                    </label>
                </div>
            </div>
        </div>
        <div class="box-footer">
            <button type="reset" class="btn btn-social btn-danger btn-sm" onclick="reset_form($(this).val());"><i class="fa fa-times"></i> Batal</button>
            <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
        </div>
        </form>
    </div>
@endsection

@push('scripts')
@include('admin.layouts.components.validasi_form')
<script>
$(document).ready(function() {        

    // Pop-up file viewer
    $('.pop-up-file').on('click', function() {
        const fileUrl = $(this).data('url');
        const titleModal = $(this).data('title');
        
        Swal.fire({
            customClass: {
                popup: "swal-lg",
            },
            title: titleModal,
            html: `
                <object data="${fileUrl}" style="width: 100%;min-height: 400px;" type="application/pdf"></object>
                <div style="margin-top: 15px;">
                    <a href="${fileUrl}" download class="btn btn-primary">Unduh</a>
                    <button type="button" class="btn btn-danger" onclick="Swal.close()">Tutup</button>
                </div>
            `,
            showConfirmButton: false
        });
    });

    // Handle jenis izin change
    $('#jenis_izin').on('change', function() {
        let selectedOption = $(this).find('option:selected');
        let description = selectedOption.data('description');
        let maxDays = selectedOption.data('max-days');        
        
        // Handle mandatory document for sick leave
        if ($(this).val() === 'sakit') {
            $('#file').addClass('required');
            $('#lampiran-required').show();
            $('#lampiran-note').html('<br><strong>Wajib mengunggah surat dokter untuk izin sakit.</strong>');
        } else {
            $('#file').removeClass('required');
            $('#lampiran-required').hide();
            $('#lampiran-note').text('');
        }
    });

    // Trigger change on page load if value exists
    $('#jenis_izin').trigger('change');  
    
    setTimeout(function() {
            $("#tanggal_selesai").rules('add', {
                tgl_lebih_besar: "input[name='tanggal_mulai']",
                messages: {
                    tgl_lebih_besar: "Tanggal selesai harus sama atau lebih besar dari tanggal mulai."
                }
            })
        }, 500);
});
</script>
@endpush
