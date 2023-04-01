<h5><b>Kode Isian</b></h5>
<div class="table-responsive">
    <table class="table table-hover table-striped">
        <tbody>
            <tr style="font-weight: bold;">
                <td>TIPE</td>
                <td>NAMA</td>
                <td>PLACEHOLDER</td>
                <td>ATRIBUT</td>
                <td class="isian-pilihan">PILIHAN</td>
                <td>AKSI</td>
            </tr>
            @php $jumlah_isian = 0; @endphp
            @foreach ($suratMaster->kode_isian as $key => $value)
                @if (!$value->statis)
                    @php $jumlah_isian++; @endphp
                    <tr class="duplikasi" id="gandakan-{{ $key }}" data-id="{{ $key }}">
                        <td>
                            <select class="form-control input-sm pilih_tipe" name="tipe_kode[]">
                                <option value="" selected>Pilihan Tipe</option>
                                @foreach ($attributes as $attr_key => $attr_value)
                                    <option value="{{ $attr_key }}" @selected($attr_key == $value->tipe)>{{ $attr_value }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td><input type="text" name="nama_kode[]" class="form-control input-sm isian"
                                value="{{ $value->nama }}" placeholder="Masukkan Nama" @disabled($value->tipe == '')>
                        </td>
                        <td><input type="text" name="deskripsi_kode[]" class="form-control input-sm isian"
                                value="{{ $value->deskripsi }}" placeholder="Masukkan Placeholder"
                                @disabled($value->tipe == '')></td>
                        <td>
                            <textarea class="form-control input-sm isian isian-atribut" name="atribut_kode[]" rows="5"
                                placeholder="Masukkan Atribut" @disabled($value->tipe == '')>{{ $value->atribut }}</textarea>
                        </td>
                        <td>
                            <textarea class="form-control input-sm isian isian-pilihan @display($value->tipe != 'select-otomatis')" name="pilihan_kode[]" rows="5"
                                placeholder="Masukkan Pilihan" @disabled($value->tipe == '')>{{ json_encode($value->pilihan) }}</textarea>
                            <select class="form-control input-sm isian isian-referensi @display($value->tipe == 'select-otomatis')"
                                name="referensi_kode[]" placeholder="Masukkan Pilihan" @disabled($value->tipe == '')>
                                <option value="" selected>Pilihan Referensi</option>
                                @foreach (\App\Enums\ReferensiEnum::all() as $label => $val)
                                    <option value="{{ $val }}" @selected($val == $value->refrensi)>{{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td width="1%">
                            <button type="button" class="btn btn-danger btn-sm hapus-kode"><i
                                    class='fa fa-trash-o'></i></button>
                        </td>
                    </tr>
                @endif
            @endforeach
            @if ($jumlah_isian == 0)
                <tr class="duplikasi" id="gandakan-0" data-id="0">
                    <td>
                        <select class="form-control input-sm pilih_tipe" name="tipe_kode[]">
                            <option value="" selected>Pilihan Tipe</option>
                            @foreach ($attributes as $attr_key => $attr_value)
                                <option value="{{ $attr_key }}" @selected($attr_key == 1)>
                                    {{ $attr_value }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="text" name="nama_kode[]" class="form-control input-sm isian"
                            placeholder="Masukkan Nama" @disabled($value->tipe == '')></td>
                    <td><input type="text" name="deskripsi_kode[]" class="form-control input-sm isian"
                            placeholder="Masukkan Placeholder" @disabled($value->tipe == '')></td>
                    <td>
                        <textarea class="form-control input-sm isian isian-atribut" name="atribut_kode[]" rows="5"
                            placeholder="Masukkan Atribut" @disabled($value->tipe == '')>{{ $value->atribut }}</textarea>
                    </td>
                    <td>
                        <textarea class="form-control input-sm isian isian-pilihan" name="pilihan_kode[]" rows="5"
                            placeholder="Masukkan Pilihan" @disabled($value->tipe == '')>{{ (string) $value->atribut }}</textarea>
                        <select class="form-control input-sm isian isian-referensi @display($value->tipe == 'select-otomatis')"
                            name="referensi_kode[]" placeholder="Masukkan Pilihan" @disabled($value->tipe == '')>
                            <option value="" selected>Pilihan Referensi</option>
                            @foreach (\App\Enums\ReferensiEnum::all() as $key => $value)
                                <option value="{{ $value }}">{{ $key }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td class="padat">
                        <button type="button" class="btn btn-danger btn-sm hapus-kode"><i
                                class="fa fa-trash-o"></i></button>
                    </td>
                </tr>
            @endif
            <tr>
                <td colspan="5">
                    <button type="button" class="btn btn-success btn-sm btn-block tambah-kode"><i
                            class="fa fa-plus"></i></button>
                </td>
            </tr>
        </tbody>
    </table>
</div>
@push('scripts')
    <script>
        $(document).ready(function() {
            var counter = $(".duplikasi:last").data("id");
            $("#gandakan-" + counter).find("button").hide();

            $('.tambah-kode').on('click', function() {
                var editElm;
                counter++;
                $("#gandakan-0").clone(true)
                    .map(function() {
                        editElm = $(this)
                            .attr('id', 'gandakan-' + counter)
                            .attr('data-id', counter)
                            .find('select')
                            .end();
                    });

                if ($("#gandakan-" + (counter - 1)).length) {
                    $("#gandakan-" + (counter - 1)).after(editElm);
                } else {
                    $("#gandakan-0").after(editElm);
                }

                $("#gandakan-" + counter + " option:selected").removeAttr('selected');
                $("#gandakan-" + counter).find('input').val('');
                $("#gandakan-" + counter).find('select').change(0);
                $("#gandakan-" + counter).find('textarea').val('');
                $("#gandakan-" + counter).find('.isian').prop("disabled", true);

                $('.duplikasi').find("button").show();
                $("#gandakan-" + counter).find("button").hide();
            });

            $('.hapus-kode').on('click', function() {
                $(this).parents('.duplikasi').remove();
            });

            $('.pilih_tipe').on('change', function() {
                var tipe = $(this).val();
                var atribut = '';
                var option = '{}';
                var parents = $(this).parents('.duplikasi');
                var isian_atribut = parents.find('.isian-atribut');
                var isian_pilihan = parents.find('.isian-pilihan');
                var isian_referensi = parents.find('.isian-referensi');
                var isian = parents.find('.isian');

                if (tipe == '') {
                    atribut = 'Masukkan Atribut';
                    option = 'Masukkan Pilihan';
                    isian.prop("disabled", true);
                    isian.removeClass('required');
                    isian_referensi.addClass('hide');
                } else {
                    isian.prop("disabled", false);
                    isian.addClass('required');
                    isian_atribut.removeClass('required');
                    isian_referensi.addClass('hide');

                    if (tipe == 'select-manual') {
                        atribut = 'size="5"';
                        option =
                            `["Laki-laki","Perempuan"]`;

                        isian_pilihan.prop("disabled", false);
                        isian_pilihan.val(option);
                        isian_pilihan.removeClass('hide');
                        isian_pilihan.addClass('required');
                        isian_referensi.addClass('hide');
                        isian_referensi.removeClass('required');
                    } else {
                        option = '{}';
                        isian_referensi.addClass('hide');
                        isian_referensi.removeClass('required');
                        isian_pilihan.removeClass('hide');
                        isian_pilihan.removeClass('required');
                        if (tipe == 'select-otomatis') {
                            atribut = 'size="5"';
                            isian_referensi.removeClass('hide');
                            isian_referensi.addClass('required');
                            isian_pilihan.addClass('hide');
                            isian_pilihan.removeClass('required');
                        } else if (tipe == 'text') {
                            atribut = 'minlength="5" maxlength="50"';
                        } else if (tipe == 'number') {
                            atribut = 'min="1" max="100" step="1"';
                        } else if (tipe == 'email') {
                            atribut = 'minlength="5" maxlength="50"';
                        } else if (tipe == 'url') {
                            atribut = 'minlength="5" maxlength="50"';
                        } else if (tipe == 'date') {
                            atribut = 'min="2021-01-01" max="2021-12-31"';
                        } else if (tipe == 'time') {
                            atribut = 'min="00:00" max="23:59"';
                        } else {
                            atribut = 'minlength="5" maxlength="50" rows="5"';
                        }
                        isian_pilihan.prop("disabled", true);
                        isian_pilihan.removeClass('required');
                    }
                }

                isian_atribut.attr("placeholder", atribut);
                isian_pilihan.attr("placeholder", option);
                $(this).parents('.duplikasi').find('.isian').val('');
            });
        });
    </script>
@endpush
