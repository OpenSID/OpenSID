{!! form_open_multipart(ci_route('notif.update_setting'), 'class="form-group" id="main_setting"') !!}
    <div class="modal-body">
        @foreach ($list_setting as $key => $pengaturan)
            @if ($pengaturan->kategori == $kategori_pengaturan)
                @php
                    $value = $pengaturan->toComponentData();
                    $componentView = view()->exists("admin.pengaturan.components.{$value['type']}")
                        ? "admin.pengaturan.components.{$value['type']}"
                        : "admin.pengaturan.components.input-text";
                    $requiredIfAttr = !empty($value['required_if']) ? "data-required-if='" . json_encode($value['required_if']) . "'" : '';
                @endphp
                <div class="form-group" id="form_{{ $pengaturan->key }}" {!! $requiredIfAttr !!}>
                    <label for="{{ $pengaturan->key }}">{{ SebutanDesa($pengaturan->judul) }}</label>
                    @include($componentView, ['value' => $value, 'pengaturan' => $pengaturan])
                    <p class="help-block">
                        <code>{!! SebutanDesa($pengaturan->keterangan) !!}</code>
                    </p>
                </div>
            @endif
        @endforeach
    </div>
    <div class="modal-footer">
        {!! batal() !!}
        <button type="submit" class="btn btn-social btn-info btn-sm"><i class="fa fa-check"></i> Simpan</button>
    </div>
</form>

@push('scripts')
    <script src="{{ asset('js/validasi.js') }}"></script>
    <script>
        $(document).ready(function() {
            $("#main_setting").validate({
                errorElement: "label",
                errorClass: "error",
                highlight: function(element) {
                    $(element).closest(".form-group").addClass("has-error");
                },
                unhighlight: function(element) {
                    $('.select2').on("select2:close", function(e) {
                        $(this).valid();
                    });

                    $(element).closest(".form-group").removeClass("has-error");
                },
                errorPlacement: function(error, element) {
                    if (element.parent('.input-group').length) {
                        error.insertAfter(element.parent());
                    } else if (element.hasClass('select2')) {
                        error.insertAfter(element.next('span'));
                    } else {
                        error.insertAfter(element);
                    }
                }
            });

            // Toggle visibilitas password untuk komponen input-password.
            $(document)
                .off('click', '.show-hide-password')
                .on('click', '.show-hide-password', function() {
                    const $toggle = $(this);
                    const $input = $toggle.closest('.input-group').find('input').first();
                    const $icon = $toggle.find('i').first();

                    if ($input.length === 0) {
                        return;
                    }

                    const isPassword = $input.attr('type') === 'password';
                    $input.attr('type', isPassword ? 'text' : 'password');

                    if ($icon.length > 0) {
                        $icon.toggleClass('fa-eye', isPassword);
                        $icon.toggleClass('fa-eye-slash', !isPassword);
                    }
                })
                .off('click', '[id^="file_browser"]')
                .on('click', '[id^="file_browser"]', function() {
                    const $group = $(this).closest('.input-group');
                    $group.find('input[type="file"]').trigger('click');
                })
                .off('change', '.input-group input[type="file"]')
                .on('change', '.input-group input[type="file"]', function() {
                    const $fileInput = $(this);
                    const $group = $fileInput.closest('.input-group');
                    const fileName = $fileInput.val().split('\\').pop();
                    $group.find('input[type="text"]').val(fileName);
                });

            // Generic handler untuk required_if
            $('[data-required-if]').each(function() {
                const $formGroup = $(this);
                const config = $formGroup.data('required-if');

                if (!config || !config.field || config.value === undefined) {
                    return;
                }

                // Coba selector langsung (#key), fallback ke #input_key untuk jenis input-text/number/url
                let $triggerField = $(`#${config.field}`);

                if ($triggerField.length === 0) {
                    $triggerField = $(`#input_${config.field}`);
                }

                if ($triggerField.length === 0) {
                    return;
                }

                const $targetInput = $formGroup.find('input, select, textarea').first();

                function toggleRequired() {
                    const currentValue = $triggerField.val();
                    // Jika form group trigger sedang hidden (karena required_if chain), ikut hidden
                    const triggerGroupHidden = $triggerField.closest('.form-group').is(':hidden');
                    const matched = !triggerGroupHidden && String(config.value) == String(currentValue);

                    if (matched) {
                        $formGroup.show();
                        if (!config.optional) {
                            $targetInput.addClass('required');
                        } else {
                            $targetInput.removeClass('required');
                        }
                    } else {
                        $formGroup.hide();
                        $targetInput.removeClass('required');
                    }

                    // Propagasi ke field yang mungkin bergantung pada field ini (chaining)
                    $targetInput.trigger('change');
                }

                // Initial state
                toggleRequired();

                // Listen for changes
                $triggerField.on('select2:select change', function() {
                    toggleRequired();
                });
            });
        });
    </script>
@endpush
