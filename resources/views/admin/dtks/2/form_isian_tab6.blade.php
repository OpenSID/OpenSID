@push('css')
    <style>
        .text-14 {
            font-size: 14px;
            font-weight: normal;
        }
    </style>
@endpush
{!! form_open('', 'class="" id="form-6"') !!}
<input type="hidden" name='tipe_save' value='bagian6'>
<div class="row">
    <div class="form-group col-sm-12">
        <textarea name="catatan" id="catatan" class="form-control" rows="15">{{ $dtks->catatan ?? '' }}</textarea>
    </div>

    <hr class="col-sm-12">
    <div class="col-sm-12 text-center">
        <button type="reset" class="btn btn-social btn-danger btn-sm"><i class='fa fa-times'></i>Batal</button>
        <button type="button" class="next-prev-bagian-6 btn btn-social btn-default btn-sm"><i class='fa fa-arrow-left'></i> Sebelumnya</button>
        <button type="button" class="next-prev-bagian-6 btn btn-social btn-default btn-sm">Selanjutnya <i class="fa fa-arrow-right"></i></button>
        <button type="submit" class="btn btn-social btn-info btn-sm"><i class="fa fa-check"></i>Simpan</button>
    </div>
</div>
</form>
@push('scripts')
    <script>
        $(document).ready(function() {
            $('.next-prev-bagian-6').on('click', function() {
                let is_valid = is_form_valid($(`#form-6`).attr('id'));
                if (!is_valid) {
                    return false;
                }

                let form = $('#form-6').serializeArray();
                $.ajax({
                    type: 'POST',
                    url: "{{ ci_route('dtks.save') . '/' . $dtks->id }}",
                    data: form,
                });

                let selajutnya = $(this).text().includes("Selanjutnya");
                if (selajutnya) {
                    $(`#nav-bagian-7`).trigger('click');
                } else {
                    $(`#nav-bagian-5`).trigger('click');
                }
            });
            $('#form-6').on('submit', function(ev) {
                ev.preventDefault();

                let form = $('#form-6').serializeArray();
                ajax_save_dtks("{{ ci_route('dtks.save') . '/' . $dtks->id }}", form);
            });
        });
    </script>
@endpush
