@include('admin.layouts.components.datetime_picker')
@include('admin.layouts.components.token')

@stack('css')

<form action="{{ $form_action }}" method="post" id="mainform" enctype="multipart/form-data">
    <div class="modal-body">
        @include('admin.layouts.components.notifikasi')
        @yield('form-fields')
    </div>

    @if ($penduduk['status_dasar_id'] == 1 || !isset($penduduk['status_dasar_id']))
        <div class="modal-footer">
            {!! batal() !!}
            <button
                type="submit"
                class="btn btn-social btn-info btn-sm pull-right"
            >
                <i class="fa fa-check"></i> Simpan
            </button>
        </div>
    @endif
</form>

<script>
    $(document).ready(function () {
        let onSubmit = false;
        const $modal = $('#modal-ubah-biodata');

        $modal.on('hide.bs.modal', function (e) {
            if (onSubmit) {
                e.preventDefault();
            }
        });

        $('#mainform').validate({
            errorElement: "label",
            errorClass: "error",
            highlight: function (element) {
                $(element).closest(".form-group").addClass("has-error");
            },
            unhighlight: function (element) {
                $(element).closest(".form-group").removeClass("has-error");
            },
            errorPlacement: function (error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else if (element.hasClass('select2')) {
                    error.insertAfter(element.next('span'));
                } else {
                    error.insertAfter(element);
                }
            },
            submitHandler: function (form) {
                const $form = $(form);
                const formData = new FormData(form);
                const $submitBtn = $form.find('button[type="submit"]');
                const originalHtml = $submitBtn.html();

                $submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Simpan');
                onSubmit = true;

                $.ajax({
                    url: $form.attr('action'),
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        onSubmit = false;
                        $modal.modal('hide');
                        loadDataPenduduk($(`select[name="${$modal.data('kategori')}[nik]"]`));

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: 'Penduduk berhasil diubah',
                            timer: 2000,
                            showConfirmButton: false
                        });
                    },
                    error: function (xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: xhr.responseJSON?.message ?? 'Terjadi kesalahan saat menyimpan.'
                        });
                    },
                    complete: function () {
                        $submitBtn.prop('disabled', false).html(originalHtml);
                        onSubmit = false;
                    }
                });

                return false;
            }
        });

        $('button[type="reset"]').click(function (e) {
            e.preventDefault();
            $(this).closest('form').trigger('reset');
            $(this).closest('form').find('.select2').trigger('change');
        });
    });
</script>

@stack('scripts')
