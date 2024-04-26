<div class="box box-primary">
    <div class="box-body box-profile">
        <img class="penduduk" id="foto" src="{{ AmbilFoto($foto, '', $id_sex) }}" alt="Foto Penduduk">
        <br />
        @if (isset($show_dimensi) && $show_dimensi)
            <div class="row" style="margin-bottom: 8px">
                <label for="" class="col-sm-6">Lebar (px)</label>
                <div class="col-sm-6">
                    <input name="lebar" class="form-control input-sm bilangan" type="number" value="200" placeholder="200">
                </div>
            </div>
            <div class="row" style="margin-bottom: 8px">
                <label for="" class="col-sm-6">Tinggi (px)</label>
                <div class="col-sm-6">
                    <input name="tinggi" class="form-control input-sm bilangan" type="number" value="200" placeholder="200">
                </div>
            </div>
        @endif

        <div class="input-group input-group-sm text-center">
            <input type="file" class="hidden" id="file" name="foto" accept=".jpg,.jpeg,.png">
            <input type="text" class="hidden" id="file_path" name="foto">
            <input type="hidden" name="old_foto" id="old_foto" value="<?= $foto ?>">
            <span class="input-group-btn">
                <button type="button" class="btn btn-info btn-block btn-mb-5" id="file_browser"><i class="fa fa-upload"></i> Unggah</button>
                <button type="button" class="btn btn-danger btn-block btn-mb-5" onclick="kamera();" id="ambil_kamera"><i class="fa fa-camera"></i> Kamera</button>
                @if (!empty($penduduk['id']))
                    <a href="#" data-href="{{ ci_route('penduduk.foto_bawaan', $penduduk['id']) }}" class="btn btn-warning btn-block" title="Kembalikan" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-undo"></i> Kembalikan</a>
                @endif
            </span>
        </div>
    </div>
</div>
@include('admin.layouts.components.capture')
@include('admin.layouts.components.konfirmasi_hapus')

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#confirm-delete').on('shown.bs.modal', function(ev) {
                $(this).find('.modal-footer #ok-delete').html(
                    '<i class="fa fa-undo"></i> Kembalikan')
            })
        });
    </script>
@endpush
