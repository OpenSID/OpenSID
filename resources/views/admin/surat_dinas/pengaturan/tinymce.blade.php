@include('admin.pengaturan_surat.asset_tinymce')

<div class="tab-pane" id="template-surat">

    @include('admin.surat_dinas.pengaturan.kembali')

    <div class="box-body">
        <div class="form-group">
            <textarea name="template_desa" data-filemanager='{!! json_encode(['external_filemanager_path' => base_url('assets/kelola_file/'), 'filemanager_title' => 'Responsive Filemanager', 'filemanager_access_key' => $session->fm_key]) !!}' data-urlkodeisian="surat_dinas" data-urlsalintemplate="surat_dinas" data-salintemplate="isi" class="form-control input-sm editor required">{{ $suratDinas->template_desa ?? $suratDinas->template }}</textarea>
        </div>
    </div>
</div>

<div class="tab-pane" id="form-isian">
    @include('admin.surat_dinas.pengaturan.kembali')

    <div class="box-body">
        <a id="btn-new-tab" class="btn btn-social btn-primary btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-plus"></i>Tambah Bagian Form</a>
        <hr>

        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs customized-tab" id="tabs">
                <li data-name="utama" class="active">
                    <a href="#form-utama" data-toggle="tab">{{ $suratDinas->form_isian->individu->judul ?? 'Utama' }}</a>
                </li>
                @forelse ($kategori as $item => $value)
                    @if ($item == 'individu')
                        @continue
                    @endif
                    <li class="ui-list-tab" id="list-{{ $item }}" data-name="{{ $item }}">
                        <a id="nav-tab-{{ $item }}" href="#tab-{{ $item }}" data-toggle="tab">{{ $value }}</a>
                        <input type="hidden" name="kategori[]" value="{{ $item }}">
                    </li>
                @empty
                @endforelse
            </ul>
            <div class="tab-content custom">
                <div class="tab-pane active" id="form-utama">
                    <div class="box-body">
                        <button type="button" class="utama-delete btn btn-danger btn-sm pull-right hide" onclick="deleteTab(event)"><i class="fa fa-times-circle"></i> Hapus Bagian Form</button>
                        <div class="row">
                            <label for="isi-judul" class="col-sm-2">Judul Bagian</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control input-sm required judul isi-judul" name="judul" value="{{ $suratDinas->form_isian->individu->judul ?: 'Utama' }}" minlength="3" maxlength="20">
                            </div>
                        </div>
                        <div class="row" style="margin-top: 5px">
                            <label for="isi-label" class="col-sm-2" title="Isi dengan karakter - untuk sembunyikan label">Label Bagian</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control input-sm required isi-label" name="label" value="{{ $suratDinas->form_isian->individu->label ?? 'Keterangan' }}" minlength="1" maxlength="30">
                            </div>
                        </div>
                        <div class="row" style="margin-top: 5px">
                            <label for="isi-label" class="col-sm-2">Info Bagian</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control input-sm isi-info" name="info" value="{{ $suratDinas->form_isian->individu->info ?? '' }}" maxlength="100">
                            </div>
                        </div>
                        <div class="row" style="margin-top: 5px">
                            <label for="isi-prefix" class="col-sm-2">Prefix Bagian</label>
                            <div class="col-sm-8">
                                <input
                                    type="text"
                                    class="form-control input-sm required prefix_tinymce isi-prefix"
                                    name="prefix"
                                    value="{{ strtolower($suratDinas->form_isian->individu->prefix ?? 'individu') }}"
                                    minlength="3"
                                    maxlength="50"
                                    readonly
                                >
                            </div>
                        </div>
                        <hr>

                        @include('admin.surat_dinas.pengaturan.kode_isian')
                    </div>
                </div>
                @forelse ($kategori_nama as $item)
                    @php
                        $kategori = $kategori_isian[$item];
                    @endphp
                    <div class="tab-pane" id="tab-{{ $item }}">
                        <div class="box-body">
                            <button type="button" class="btn btn-danger btn-sm pull-right" data-kategori="{{ $item }}" onclick="deleteTab(event)"><i class="fa  fa-times-circle"></i> Hapus Bagian Form</button>
                            <div class="row">
                                <label for="isi-judul" class="col-sm-2">Judul Bagian</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control input-sm required judul_tinymce isi-judul" name="kategori_judul[{{ $item }}]" value="{{ $suratDinas->form_isian->$item->judul ?? ucwords(str_replace('_', ' ', $item)) }}" minlength="3" maxlength="20">
                                </div>
                            </div>
                            <div class="row" style="margin-top: 5px">
                                <label for="isi-label" class="col-sm-2" title="Isi dengan karakter - untuk sembunyikan label">Label Bagian</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control input-sm required judul isi-label" name="kategori_label[{{ $item }}]" value="{{ $suratDinas->form_isian->$item->label ?? ucwords(str_replace('_', ' ', $item)) }}" minlength="1" maxlength="30">
                                </div>
                            </div>
                            <div class="row" style="margin-top: 5px">
                                <label for="isi-label" class="col-sm-2">Info Bagian</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control input-sm isi-info" name="kategori_info[{{ $item }}]" value="{{ $suratDinas->form_isian->$item->info ?? '' }}" maxlength="100">
                                </div>
                            </div>
                            <div class="row" style="margin-top: 5px">
                                <label for="isi-prefix" class="col-sm-2">Prefix Bagian</label>
                                <div class="col-sm-8">
                                    <input type="text" class="form-control input-sm required prefix_tinymce isi-prefix" name="kategori_prefix[{{ $item }}]" value="{{ strtolower($suratDinas->form_isian->$item->prefix ?? $item) }}" minlength="3" maxlength="50">
                                </div>
                            </div>
                            <hr>

                            @include('admin.surat_dinas.pengaturan.kategori_isian', ['key_kategori' => $item])
                        </div>
                    </div>
                @empty
                @endforelse
            </div>
        </div>
    </div>
</div>
@include('admin.surat_dinas.pengaturan.pindah_kode_modal')
@include('admin.surat_dinas.pengaturan.kaitkan_kode_modal')
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            var num = 0;
            $('#btn-new-tab').click(function(e) {
                var nama_kategori = Math.random().toString(36).replace(/[^a-z]+/g, '').substr(0, 5)
                num++
                e.preventDefault()
                var newTabId = 'tab-' + nama_kategori
                var oldname, newname
                // destroy select2 sebelum di clone
                $('#data_utama').select2('destroy')
                $('#data_utama').removeAttr('data-select2-id')
                $('#data_utama option').removeAttr('data-select2-id')

                $('#individu_kk_level').select2('destroy')
                $('#individu_kk_level').removeAttr('data-select2-id')
                $('#individu_kk_level option').removeAttr('data-select2-id')

                // konsepnya sama seperti kasus multi kk_level
                $('#individu_status_dasar').select2('destroy')
                $('#individu_status_dasar').removeAttr('data-select2-id')
                $('#individu_status_dasar option').removeAttr('data-select2-id')

                $("#form-utama").clone(true)
                    .map(function() {
                        editElm = $(this)
                            .attr('id', newTabId)
                            .attr('data-id', num)
                            .find('select')
                            .end()
                            .find("#dragable-form-utama")
                            .attr('id', `dragable-${nama_kategori}`)
                            .end()
                            .find('#utama_sebagai')
                            .attr('id', `${nama_kategori}_sebagai`)
                            .end();

                        var utama_isi_judul = editElm[0].querySelector('.isi-judul')
                        var utama_isi_label = editElm[0].querySelector('.isi-label')
                        var utama_isi_info = editElm[0].querySelector('.isi-info')
                        var utama_isi_prefix = editElm[0].querySelector('.isi-prefix')

                        utama_isi_judul.name = `kategori_judul[${nama_kategori}]`
                        utama_isi_prefix.name = `kategori_prefix[${nama_kategori}]`
                        utama_isi_info.name = `kategori_info[${nama_kategori}]`

                        utama_isi_judul.value = nama_kategori
                        utama_isi_label.value = nama_kategori
                        utama_isi_info.value = ''
                        utama_isi_prefix.value = nama_kategori

                        // utama_isi_judul.removeAttribute('readonly')
                        utama_isi_prefix.removeAttribute('readonly')

                        // utama_isi_judul.setAttribute('onkeyup', `$('#tab-${nama_kategori} .isi-prefix').text(this.value.toLowerCase().replace(/ /g, '_'))`);

                        var utama_delete_btn = editElm[0].querySelector('.utama-delete')
                        utama_delete_btn.dataset.kategori = nama_kategori
                        utama_delete_btn.classList.remove('hide')

                        var tabs = $('#form-isian #tabs').find('li')

                        var elkodeIsian = editElm[0].querySelector('.kode-isian')

                        var elLabel = editElm[0].querySelector('input.isi-label')
                        oldname = elLabel.getAttribute('name')
                        newname = `kategori_${oldname}[${nama_kategori}]`
                        elLabel.name = newname
                        elLabel.value = nama_kategori

                        if (elkodeIsian != null) {
                            var elganda = editElm[0].querySelector('#gandakan-0');

                            elganda.querySelector('.hapus-kode').setAttribute('title', 'Hapus Kode Isian')
                            elganda.querySelector('.pindah-kode').setAttribute('title', 'Pindah Kode Isian')
                            elganda.querySelector('.kaitkan-kode').setAttribute('title', 'Kaitkan Kode Isian')
                            // console.log(cek.attr)
                            elganda.classList.add('duplikasi-' + nama_kategori);
                            elganda.classList.add('kategori');
                            elganda.id = 'gandakan-' + nama_kategori + '-0';

                            // use foreach tr, jika itu tr pertama maka sesuaikan dengan tab data, jika bukan delete
                            var trkode = elkodeIsian.querySelectorAll('tr.duplikasi');
                            trkode.forEach((tr, index) => {
                                var kategori_isian = tr.querySelectorAll(
                                    '.kode-isian input, .kode-isian textarea, .kode-isian select, .kode-isian'
                                );
                                if (index == 0) {
                                    kategori_isian.forEach((elselect2) => {
                                        // elselect2.name = `pilihan_kode[${counter + 1}][]`
                                        var oldname = elselect2.getAttribute('name')
                                        // console.log(oldname);
                                        if (oldname != null) {
                                            var fixname = oldname.replace(/[\[\]0]/g,
                                                "");
                                            var fullname =
                                                `kategori_${fixname}[${nama_kategori}][]`
                                            // console.log(elselect2.classList)
                                            if (oldname == 'pilihan_kode[0][]') {
                                                fullname =
                                                    `kategori_${fixname}[${nama_kategori}][0][]`
                                                if (elselect2.classList.contains(
                                                        'select2')) {
                                                    // console.log(123);
                                                    elselect2.classList.remove(
                                                        'select2')
                                                    elselect2.classList.remove(
                                                        'select2-hidden-accessible')
                                                    elselect2.nextElementSibling
                                                        .remove()
                                                    elselect2.removeAttribute(
                                                        'data-select2-id')
                                                    elselect2.innerHTML = ''
                                                }
                                            }
                                            elselect2.name = fullname
                                            elselect2.value = ''
                                            elselect2.classList.add('kategori')
                                        }
                                    });
                                } else {
                                    tr.parentNode.removeChild(tr);
                                }
                            });
                        }
                        var elbutton = editElm[0].querySelector('.tambah-kode')
                        elbutton.dataset.type = 'gandakan-' + nama_kategori
                        elbutton.dataset.kategori = nama_kategori

                        editElm.find('input[name^=kategori_nama_kode]').on('change', function(e) {
                            $(this).closest('tr').find('input[name^=kategori_label_kode]').val($(this).val())
                        })

                        return editElm;
                    });

                var newNavItem = $(
                    `<li class="ui-list-tab" id="list-${nama_kategori}" data-name="${nama_kategori}">
                        <a id="nav-${newTabId}" href="#${newTabId}" data-toggle="tab">${nama_kategori.replace(/_/g, ' ')}</a>
                        <input type="hidden" name="kategori[]" value="${nama_kategori}">
                    </li>`
                );

                $('.nav-tabs.customized-tab').append(newNavItem);
                $('.tab-content .custom').append(editElm);
                /* buat lagi select2s*/
                $('#data_utama').select2()
                $('#data_utama-' + nama_kategori).select2()

                $('#individu_kk_level').select2()
                $('#individu_kk_level-' + nama_kategori).select2()
                $('#individu_status_dasar').select2()
                $('#individu_status_dasar-' + nama_kategori).select2()
                newNavItem.find('a').tab('show');
            });

            $(".customized-tab").sortable({
                cursor: 'row-resize',
                placeholder: 'ui-state-highlight',
                items: '.ui-list-tab'
            }).disableSelection();

            $('input.isi-judul').on('change', function() {
                let _idTab = $(this).closest('.tab-pane').attr('id')
                $(`#nav-${_idTab}`).text($(this).val())
            })

            $('input.isi-prefix').on('change', function() {
                let _tabContent = $(this).closest('.tab-pane')
                let _idTabAsli = _tabContent.attr('id').substr(4)
                let _prefix = $(this).val()
                _tabContent.attr('id', 'tab-' + _prefix)
                _tabContent.find('.box-body>button').attr('data-kategori', _prefix)
                $(this).attr('name', 'kategori_prefix[' + _prefix + ']')
                // rename semua element dalam tab tersebut
                _tabContent.find('.box-body').find('select, input, textarea').each(function() {
                    if (!$.isEmptyObject($(this).attr('name'))) {
                        $(this).attr('name', $(this).attr('name').replace(_idTabAsli, _prefix))
                    }
                })

                let _navTabElm = $('#form-isian #list-' + _idTabAsli)
                _navTabElm.find('a').attr('id', 'nav-tab-' + _prefix)
                _navTabElm.find('a').attr('href', '#tab-' + _prefix)
                _navTabElm.find('input').val(_prefix)
                _navTabElm.attr('data-name', _prefix)
                _navTabElm.attr('id', 'list-' + _prefix)
            })
        });

        function ubah_sebagai_dinamis(parent, tipe) {
            if (tipe == 2) {
                $('.isi-sebagai-data').val(0);
                $('#form-utama .isi-sebagai-data').val(1);
                $(parent + ' .isi-sebagai-data').val(tipe);
            }
        }

        function loadSelect() {
            // console.log('load select');
            // $('.kategori').select2()
        }

        function judulTab(value, tab) {
            $('#nav-tab-'.tab).text();
        }

        function deleteTab(event) {
            var clicked = event.target;
            let tabContent = $(clicked).closest('.tab-pane')
            let id = tabContent.attr('id').substr(4)
            let navTabElm = $('#form-isian #list-' + id)

            tabContent.remove()
            navTabElm.prev('li').find('a').click()
            navTabElm.remove()
        }
    </script>
@endpush
