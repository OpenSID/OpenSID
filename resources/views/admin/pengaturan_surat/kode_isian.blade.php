<h5><b>Kode Isian</b></h5>
<div class="table-responsive">
    <table class="table table-hover table-striped kode-isian">
        <tbody id="dragable-form-utama">
            <tr style="font-weight: bold;">
                <td>#</td>
                <td>TIPE</td>
                <td>NAMA</td>
                <td>LABEL</td>
                <td>PLACEHOLDER</td>
                <td class="padat">HARUS DIISI</td>
                <td>KOLOM</td>
                <td>ATRIBUT</td>
                <td class="isian-pilihan">PILIHAN</td>
                <td>AKSI</td>
            </tr>
            @php $jumlah_isian = 0; @endphp
            @foreach ($kode_isian as $key => $value)
                @if (!$value->statis)
                    @php $jumlah_isian++; @endphp
                    <tr class="duplikasi ui-sortable-handle" id="gandakan-{{ $key }}" data-id="{{ $key }}">
                        <td><i class="fa fa-sort-alpha-desc" aria-hidden="true"></i></td>
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
                        <td><input type="text" name="label_kode[]" class="form-control input-sm isian"
                            value="{{ $value->label ?? '' }}" placeholder="Masukkan Label" @disabled($value->tipe == '')>
                        </td>
                        <td><input type="text" name="deskripsi_kode[]" class="form-control input-sm isian"
                                value="{{ $value->deskripsi }}" placeholder="Masukkan Placeholder"
                                @disabled($value->tipe == '')>
                        </td>
                        <td class="text-center">
                            <input class="isian-required" type="checkbox" value="1" @checked($value->required)
                                @disabled($value->tipe == '') name="required_kode[{{ $key }}]">
                        </td>
                        <td class="text-center">
                            <select class="form-control input-sm" name="kolom[]">
                                <option value="" selected>Pilihan lebar kolom</option>
                                @foreach (range(1,12) as $item)
                                    <option value="{{ $item }}" @selected($item == $value->kolom)>col-{{ $item }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <textarea class="form-control input-sm isian isian-atribut" name="atribut_kode[]" rows="5"
                                placeholder="Masukkan Atribut" @disabled($value->tipe == '')>{{ $value->atribut }}</textarea>
                        </td>
                        <td>
                            <textarea
                                class="form-control input-sm isian isian-pilihan {{ $value->tipe == 'select-otomatis' || $value->tipe == 'select-manual' ? 'hide' : '' }}"
                                name="pilihan_kode[]" rows="5" placeholder="Masukkan Pilihan"
                                {{ $value->tipe != 'select-otomatis' || $value->tipe != 'select-manual' ? 'disabled' : '' }}>{{ json_encode($value->pilihan) }}
                            </textarea>
                            <select
                                class="{{ $value->tipe == 'select-manual' ? 'select2' : 'hide' }} form-control selectinput-sm isian select-manual"
                                name="pilihan_kode[{{ $jumlah_isian }}][]" multiple placeholder="Masukkan Pilihan"
                                @disabled($value->tipe == '')>
                                @foreach ($value->pilihan as $item)
                                    <option value="{{ $item }}" selected>{{ $item }}</option>
                                @endforeach
                            </select>
                            <select class="form-control input-sm isian isian-referensi @display($value->tipe == 'select-otomatis')"
                                name="referensi_kode[]" placeholder="Masukkan Pilihan" @disabled($value->tipe == '')>
                                <option value="" selected>Pilihan Referensi</option>
                                @foreach (\App\Enums\ReferensiEnum::all() as $label => $val)
                                    <option value="{{ $val }}" @selected($val == $value->refrensi)>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                        <td width="1%">
                            <button type="button" class="btn btn-danger btn-sm hapus-kode"><i
                                    class='fa fa-trash-o'></i></button>
                            &nbsp;
                            <button type="button" class="btn btn-warning btn-sm pindah-kode"><i
                                    class='fa fa-exchange'></i></button>
                        </td>
                    </tr>
                @endif
            @endforeach
            @if ($jumlah_isian == 0)
                <tr class="duplikasi ui-sortable-handle" id="gandakan-0" data-id="0">
                    <td><i class="fa fa-sort-alpha-desc" aria-hidden="true"></i></td>
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
                    <td><input type="text" name="label_kode[]" class="form-control input-sm isian"
                            placeholder="Masukkan Label" @disabled($value->tipe == '')>
                    </td>
                    <td><input type="text" name="deskripsi_kode[]" class="form-control input-sm isian"
                            placeholder="Masukkan Placeholder" @disabled($value->tipe == '')></td>
                    <td class="text-center"><input class="isian-required" type="checkbox" value="1"
                            @checked($value->required) @disabled($value->tipe == '')
                        name="required_kode[{{ $jumlah_isian }}]"></td>
                    <td class="text-center">
                        <select class="form-control input-sm" name="kolom[]">
                            <option value="" selected>Pilihan lebar</option>
                            @foreach (range(1,12) as $item)
                            <option value="{{ $item }}">col-{{ $item }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <textarea class="form-control input-sm isian isian-atribut" name="atribut_kode[]" rows="5"
                            placeholder="Masukkan Atribut" @disabled($value->tipe == '')>{{ $value->atribut }}</textarea>
                    </td>
                    <td>
                        <textarea class="form-control input-sm isian isian-pilihan  @display($value->tipe != 'select-manual')" name="pilihan_kode[]" rows="5"
                            placeholder="Masukkan Pilihan" @disabled($value->tipe == '')>{{ (string) $value->atribut }}</textarea>
                        <select class="form-control input-sm isian select-manual @display($value->tipe == 'select-manual')"
                            name="pilihan_kode[{{ $jumlah_isian }}][]" multiple placeholder="Masukkan Pilihan"
                            @disabled($value->tipe == '')>
                            {{-- @foreach (\App\Enums\ReferensiEnum::all() as $key => $value)
                        <option value="{{ $value }}">{{ $key }}</option>
                        @endforeach --}}
                        </select>
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
                        &nbsp;
                        <button type="button" class="btn btn-warning btn-sm pindah-kode"><i
                                class='fa fa-exchange'></i></button>
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
    <button type="button" class="btn btn-success btn-sm btn-block tambah-kode" data-type="utama"><i
            class="fa fa-plus"></i></button>
</div>
@push('scripts')
    <script>
        $(document).ready(function() {
            var counter = $(".duplikasi:last").data("id");
            // $("#gandakan-" + counter).find("button").hide();
            // default label = nama
            let pindahKodeElm

            $('input[name="nama_kode[]"]').on('change', function(e){
                $(this).closest('tr').find('input[name="label_kode[]"]').val($(this).val())
            })
            $('input[name^=kategori_nama_kode]').on('change', function(e){
                $(this).closest('tr').find('input[name^=kategori_label_kode]').val($(this).val())
            })                        

            $('.tambah-kode').on('click', function(e) {
                var type = this.dataset.type;                
                let _tabContent = $(this).closest('.tab-pane')                
                var kategori = _tabContent.attr('id').substr(4)
                var editElm;
                let tbody = $(this).prev('table').find('tbody')
                let cloneTarget = tbody.find('tr:last')
                
                let counter = new Date().getTime()
                cloneTarget.clone(true)
                    .map(function() {
                        editElm = $(this)
                            .attr('id', counter)
                            .attr('data-id', counter)
                            .find('select')
                            .end();
                        // Cek apakah elemen yang dikloning adalah Select2
                        var element = editElm[0]
                        if (editElm[0].querySelector('.select2-hidden-accessible') != null) {
                            var elselect2 = element.querySelector('.select2-hidden-accessible')
                            var fullname = `pilihan_kode[${counter}][]`
                            if (type != 'utama') {
                                fullname = `kategori_pilihan_kode[${kategori}][${counter + 1}][]`
                                console.log(fullname);
                            }
                            elselect2.innerHTML = ''
                            elselect2.name = fullname
                            elselect2.disabled = true
                            elselect2.classList.remove('select2')
                            elselect2.classList.remove('select2-hidden-accessible')
                            elselect2.classList.remove('required')
                            elselect2.nextElementSibling.remove()
                            elselect2.removeAttribute('data-select2-id')
                        } else if (editElm[0].querySelector('.select-manual') != null) {
                            var elselect2 = element.querySelector('.select-manual')
                            
                            var fullname = `pilihan_kode[${counter + 1}][]`
                            if (type != 'utama') {
                                fullname = `kategori_pilihan_kode[${kategori}][${counter + 1}][]`                                
                            }
                            elselect2.name = fullname
                        }
                        return editElm;
                    });
                                
                var req_name = `required_kode[${counter}]`
                if (type != 'utama') {
                    req_name = `kategori_required_kode[${kategori}][${counter}]`                    
                }
                editElm.find("option:selected").removeAttr('selected');
                editElm.find('input').val('');
                editElm.find('select').change(0);
                editElm.find('textarea').val('');
                editElm.find('.isian').prop("disabled", true);
                editElm.find('.isian-required')
                    .prop("checked", false)
                    .prop("disabled", true)
                    .attr('value', '1')
                    .attr('name', req_name);

                tbody.find("button").show();
                editElm.find("button").hide();
                tbody.append(editElm)
            });

            // pakai data-type selector
            $('.hapus-kode').on('click', function() {
                $(this).parents('.duplikasi').remove();
            });

            $('.pilih_tipe').on('change', function() {
                // if ($(this).hasClass('kategori')) {

                // }
                var tipe = $(this).val();
                var atribut = '';
                var option = '{}';
                var parents = $(this).parents('.duplikasi');
                console.log(parents);
                var isian_atribut = parents.find('.isian-atribut');
                var isian_pilihan = parents.find('.isian-pilihan').not('.select-manual');
                var isian_manual = parents.find('.select-manual');
                var isian_referensi = parents.find('.isian-referensi');
                var isian_required = parents.find('.isian-required');
                var isian = parents.find('.isian');

                if (tipe == '') {
                    atribut = 'Masukkan Atribut';
                    option = 'Masukkan Pilihan';
                    isian.prop("disabled", true);
                    isian_required.prop("disabled", true);
                    isian.removeClass('required');
                    isian_referensi.addClass('hide');

                    isian_manual.addClass('hide');
                    isian_manual.removeClass('required');
                } else {
                    isian.prop("disabled", false);
                    isian_required.prop("disabled", false);
                    isian.addClass('required');
                    isian_atribut.removeClass('required');
                    isian_referensi.addClass('hide');
                    isian_manual.addClass('hide');
                    isian_manual.removeClass('required');

                    if (tipe == 'select-manual') {
                        atribut = 'size="5"';
                        isian_manual.removeClass('hide')
                        isian_manual.addClass('required select2');

                        isian_manual.select2({
                            tags: true,
                            placeholder: "Masukkan Pilihan",
                            createTag: function(params) {
                                return {
                                    id: params.term,
                                    text: params.term,
                                    newOption: true
                                };
                            },
                            templateResult: function(data) {
                                var $result = $("<span></span>");
                                $result.text(data.text);

                                if (data.newOption) {
                                    $result.append(" <em>(Buat Baru)</em>");
                                }

                                return $result;
                            },
                            insertTag: function(data, tag) {
                                data.push(tag);
                            }
                        });

                        loadSelect2()

                        isian_referensi.addClass('hide');
                        isian_referensi.removeClass('required');
                        isian_pilihan.addClass('hide');
                        isian_pilihan.removeClass('show');
                        isian_pilihan.removeClass('required');
                    } else {
                        option = '{}';
                        isian_referensi.addClass('hide');
                        isian_referensi.removeClass('required');
                        isian_pilihan.removeClass('hide');
                        isian_pilihan.removeClass('required');

                        isian_manual.addClass('hide');
                        isian_manual.removeClass('required');
                        isian_manual.removeClass('select2')
                        if (isian_manual[0].classList.contains('select2-hidden-accessible') == true) {
                            isian_manual.removeAttr("data-select2-id").removeClass(
                                "select2-hidden-accessible").removeAttr("aria-hidden")
                            isian_manual[0].nextElementSibling.remove()
                        }

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
                        if (tipe == 'hari' || tipe == 'hari-tanggal') {
                            atribut = 'Masukkan atribut';
                            // isian_atribut.removeClass('required');
                            // isian_atribut.prop("disabled", true);;
                        }
                        isian_pilihan.prop("disabled", true);
                        isian_pilihan.addClass('required');
                    }
                }

                isian_atribut.attr("placeholder", atribut);
                isian_pilihan.attr("placeholder", option);
                $(this).parents('.duplikasi').find('.isian').val('');
            });

            function loadSelect2() {
                $('.isian.select2').select2({
                    tags: true,
                    placeholder: "Masukkan Pilihan",
                    createTag: function(params) {
                        return {
                            id: params.term,
                            text: params.term,
                            newOption: true
                        };
                    },
                    templateResult: function(data) {
                        var $result = $("<span></span>");
                        $result.text(data.text);

                        if (data.newOption) {
                            $result.append(" <em>(Buat Baru)</em>");
                        }

                        return $result;
                    },
                    insertTag: function(data, tag) {
                        data.push(tag);
                    }
                });
            }

            function pindahkanKodeIsian(elm){
                let _tr = pindahKodeElm.closest('tr')
                let _modal = $(elm).closest('.modal-dialog')
                let _tabSelected = _modal.find('.modal-body select').val()
                let _idAsal = _tr.closest('.tab-pane').attr('id')
                let _tabAsal = $('#form-isian #tabs').find('li>a[href="#'+_idAsal+'"]').closest('li')
                let _tabTujuan = $('#form-isian #tabs').find('li>a[href="'+_tabSelected+'"]').closest('li')
                let _nameElm, _nameElmBaru, _namaKodeUtama
                                
                // sesuaikan nama element, untuk kategori menggunakan kombinasi nama kategori_{nama_element}[{nama_kategori}][]
                _tr.find('input, select, textarea').each(function(){
                    _nameElm = $(this).attr('name')
                    if (_nameElm){
                        if (_tabAsal.attr('data-name') == 'utama') {
                            // tujuan pasti ke kategori                        
                            _namaKodeUtama = _nameElm.split('[')[0]
                            _nameElmBaru = `kategori_${_namaKodeUtama}[${_tabTujuan.attr('data-name')}]${_nameElm.substr(_namaKodeUtama.length)}`                        
                        }else {
                            if (_tabTujuan.attr('data-name') == 'utama'){
                                _nameElmBaru = _nameElm.replace('kategori_','').replace('['+_tabAsal.attr('data-name')+']', '')
                            }else {
                                // antar kategori
                                _nameElmBaru = _nameElm.replace('['+_tabAsal.attr('data-name')+']', '['+_tabTujuan.attr('data-name')+']')
                            }
                        }
                        
                        $(this).attr('name', _nameElmBaru)
                    }                    
                    
                })
                _tr.appendTo($(_tabSelected).find('table.kode-isian tbody'))
                _modal.find('button.close').click()
                $('#form-isian #tabs').find('li>a[href="'+_tabSelected+'"]').click()
            }

            loadSelect2();

            $("#dragable-form-utama").sortable({
                cursor: 'row-resize',
                placeholder: 'ui-state-highlight',
                items: '.ui-sortable-handle'
            }).disableSelection();
            
            $('.pindah-kode').on('click', function() {
                pindahKodeElm = $(this)
                $('#pindah_kode_modal').modal('show');
            });            

            $('.pindahkan-btn').on('click', function(){
                pindahkanKodeIsian($(this))
            })

            $('#pindah_kode_modal').on('show.bs.modal', function (event) {                
                var tabs = $('#form-isian #tabs').find('li')
                var tabPaneId = pindahKodeElm.closest('.tab-pane').attr('id')
                
                var content = ['<select class="form-control">']
                var modal = $(this);
                tabs.each(function(){
                    if (! $(this).find('a[href="#'+tabPaneId+'"]').length){
                        content.push(`<option value="${$(this).find('a').attr('href')}">${$(this).find('a').text()}</option>`)
                    }                    
                })
                content.push('</select>')             
                modal.find('.modal-body').html(content.join('')); // Set modal content
            });
        })
    </script>
@endpush
