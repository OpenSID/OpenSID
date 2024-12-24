@extends('layanan_mandiri.layouts.index')

@section('content')
    <form id="validasi" action="{{ $form_action }}" method="POST">
        <div class="box box-solid">
            <div class="box-header with-border bg-green">
                <h4 class="box-title">Surat</h4>
            </div>
            <div class="box-body box-line">
                <h4><b>PERMOHONAN SURAT</b></h4>
                <input type="hidden" id="id_permohonan" name="id_permohonan" value="{{ $permohonan['id'] }}" />
            </div>
            <div class="box-body box-line">
                @if ($permohonan)
                    <div class="alert alert-warning" role="alert">
                        <span style="font-size: larger;">Lengkapi permohonan surat tanggal {{ $permohonan['updated_at'] }}</span>
                    </div>
                @endif
                <div class="form-group">
                    <label for="nama_surat" class="col-sm-3 control-label">Jenis Surat Yang Dimohon</label>
                    <div class="col-sm-9">
                        <select class="form-control select2 required" name="id_surat" id="id_surat">
                            <option value=""> -- Pilih Jenis Surat -- </option>
                            @foreach ($menu_surat_mandiri as $data)
                                <option value="{{ $data['id'] }}" {{ $data['id'] == $permohonan['id_surat'] ? 'selected' : '' }}>
                                    {{ $data['nama'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="keterangan_tambahan" class="col-sm-3 control-label">Keterangan Tambahan</label>
                    <div class="col-sm-9">
                        <textarea class="form-control {{ $cek_anjungan['keyboard'] == 1 ? 'kbvtext' : '' }}" name="keterangan" id="keterangan" placeholder="Ketik di sini untuk memberikan keterangan tambahan.">{{ $permohonan['keterangan'] }}</textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label for="no_hp_aktif" class="col-sm-3 control-label">No. HP aktif</label>
                    <div class="col-sm-9">
                        <input
                            class="form-control bilangan_spasi required {{ $cek_anjungan['keyboard'] == 1 ? 'kbvnumber' : '' }}"
                            type="text"
                            name="no_hp_aktif"
                            id="no_hp_aktif"
                            placeholder="Ketik No. HP"
                            maxlength="14"
                            value="{{ $permohonan['no_hp_aktif'] ?? $ci->is_login->telepon }}"
                        />
                    </div>
                </div>
            </div>
        </div>

        <!-- Kelengkapan Dokumen Yang Dibutuhkan -->
        <div class="box box-solid">
            <div class="ada_syarat" style="display: none">
                <div class="box-header with-border bg-green">
                    <h4 class="box-title">SYARAT SURAT</h4>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-data" id="syarat_surat" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Syarat</th>
                                    <th>Dokumen Melengkapi Syarat</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <button type="reset" class="btn btn-social btn-sm btn-danger"><i class="fa fa-times"></i> Batal</button>
                <button type="submit" class="btn btn-social btn-primary btn-sm pull-right" id="isi_form"><i class="fa fa-file-text"></i>Isi Form</button>
            </div>
        </div>
    </form>

    <div class="modal fade in" id="dialog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header btn-danger">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel"><i class="fa fa-exclamation-triangle"></i> &nbsp;Peringatan
                    </h4>
                </div>
                <div class="modal-body">
                    <p id="kata_peringatan"></p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-social btn-danger btn-sm" data-dismiss="modal"><i class='fa fa-sign-out'></i>
                        Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function cek_perhatian(elem) {
            if ($(elem).val() == '-1') {
                $(elem).next('.perhatian').show();
            } else {
                $(elem).next('.perhatian').hide();
            }
        }

        $(function() {
            $(document).on("keydown", ":input:not(textarea):not(:submit)", function(event) {
                if (event.key === "Enter" && !$("#validasi").valid()) {
                    event.preventDefault();
                    return false;
                }
            });
        });
    </script>
@endpush
