@include('admin.pengaturan_surat.asset_tinymce')

@push('css')
    <style>
        #modal-tab {
            background: rgba(0, 128, 0, 0);
            position: absolute;
            float: left;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
        }
    </style>
@endpush

<div class="tab-pane" id="template-surat">

    @include('admin.pengaturan_surat.kembali')

    <div class="box-body">
        <div class="form-group">
            <textarea name="template_desa" data-filemanager='<?= json_encode(['external_filemanager_path'=> base_url() . 'assets/filemanager/', 'filemanager_title' => 'Responsive Filemanager', 'filemanager_access_key' => $session->fm_key]) ?>' data-salintemplate="isi" class="form-control input-sm editor required">{{ $suratMaster->template_desa ?? $suratMaster->template }}</textarea>
        </div>
    </div>
</div>
<div class="tab-pane" id="form-isian">

    @include('admin.pengaturan_surat.kembali', ['data' => 1])

    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs customized-tab" id="tabs">
            <li data-name="utama" class="active"><a href="#form-utama" data-toggle="tab">Utama</a></li>
            @forelse ($kategori_nama as $item)
                <li id="list-{{ $item }}" data-name="{{ $item }}"><a href="#tab-{{ $item }}"
                        data-toggle="tab">{{ str_replace('_', ' ', $item) }}</a></li>
            @empty
            @endforelse
        </ul>
        <div class="tab-content custom">
            <div class="tab-pane active" id="form-utama">
                <div class="box-body">
                    <button type="button" class="utama-delete btn btn-danger btn-sm pull-right hide"
                        onclick="deleteTab(event)"><i class="fa fa-times-circle"></i> Hapus Bagian Form</button>
                    <h5><b>Sumber Data</b></h5>
                    <div class="table-responsive">
                        <table class="table table-hover table-striped sumber-data">
                            <tbody>
                                <tr style="font-weight: bold;">
                                    <td>Data Kategori</td>
                                    <td>Pilihan</td>
                                </tr>

                                <tr>
                                    <td>Data Individu</td>
                                    <td>
                                        @php $desa_pend = strtoupper(setting('sebutan_desa')) @endphp
                                        <select id="data_utama" class="form-control input-sm" name="data_utama">
                                            <option value="1" @selected(1 == $suratMaster->form_isian->individu->data)>PENDUDUK
                                                {{ $desa_pend }}
                                            </option>
                                            <option value="2" @selected(2 == $suratMaster->form_isian->individu->data)>PENDUDUK LUAR
                                                {{ $desa_pend }}
                                            </option>
                                        </select>
                                    </td>
                                </tr>

                                <tr id="orang-tua">
                                    <td>Data Orang Tua</td>
                                    <td>
                                        <select id="data_orang_tua" class="form-control input-sm" name="data_orang_tua">
                                            <option value="0" @selected(0 == $suratMaster->form_isian->individu->data_orang_tua)>TIDAK</option>
                                            <option value="1" @selected(1 == $suratMaster->form_isian->individu->data_orang_tua)>YA</option>
                                        </select>
                                    </td>
                                </tr>

                                <tr id="data-pasangan">
                                    <td>Data Pasangan</td>
                                    <td>
                                        <select id="data_pasangan" class="form-control input-sm" name="data_pasangan">
                                            <option value="0" @selected(0 == $suratMaster->form_isian->individu->data_pasangan)>TIDAK</option>
                                            <option value="1" @selected(1 == $suratMaster->form_isian->individu->data_pasangan)>YA</option>
                                        </select>
                                    </td>
                                </tr>

                                <tr class="warga_desa">
                                    <td>Jenis Kelamin</td>
                                    <td>
                                        <select class="form-control input-sm" name="individu_sex">
                                            <option value="">SEMUA</option>
                                            @foreach ($form_isian['daftar_jenis_kelamin'] as $key => $data)
                                                <option value="{{ $key }}" @selected($key == $suratMaster->form_isian->individu->sex)>
                                                    {{ $data }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>

                                <tr class="warga_desa">
                                    <td>Jenis Peristiwa</td>
                                    <td>
                                        <select class="form-control input-sm" name="individu_status_dasar">
                                            <option value="">SEMUA</option>
                                            @foreach ($form_isian['daftar_status_dasar'] as $key => $data)
                                                <option value="{{ $key }}" @selected($key == $suratMaster->form_isian->individu->status_dasar)>
                                                    {{ $data }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>

                                <tr class="warga_desa">
                                    <td>Status Hubungan Dalam Keluarga (SHDK)</td>
                                    <td>
                                        <select id="individu_kk_level" class="form-control input-sm"
                                            name="individu_kk_level">
                                            <option value="">SEMUA</option>
                                            @foreach ($form_isian['daftar_shdk'] as $key => $data)
                                                <option value="{{ $key }}" @selected($key == $suratMaster->form_isian->individu->kk_level)>
                                                    {{ $data }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <hr>

                    @include('admin.pengaturan_surat.kode_isian')
                </div>
            </div>
            @forelse ($kategori_nama as $item)
                @php
                    $kategori = $kategori_isian[$item];
                @endphp
                <div class="tab-pane" id="tab-{{ $item }}">
                    <input type="hidden" name="kategori[]" value="{{ $item }}">
                    <div class="box-body">
                        <button type="button" class="btn btn-danger btn-sm pull-right"
                            data-kategori="{{ $item }}" onclick="deleteTab(event)"><i
                                class="fa  fa-times-circle"></i> Hapus Bagian Form</button>
                        <h5 class="sumber-data-title"><b>Sumber Data</b></h5>
                        <div class="table-responsive">
                            <table class="table table-hover table-striped sumber-data">
                                <tbody>
                                    <tr style="font-weight: bold;">
                                        <td>Data Kategori</td>
                                        <td>Pilihan</td>
                                    </tr>

                                    <tr>
                                        <td>Data Individu</td>
                                        <td>
                                            @php $desa_pend = strtoupper(setting('sebutan_desa')) @endphp
                                            <select id="data_utama" class="form-control input-sm kategori select2"
                                                name="kategori_data_utama[{{ $item }}]">
                                                <option value="1" @selected(1 == $suratMaster->form_isian->$item->data)>PENDUDUK
                                                    {{ $desa_pend }}
                                                </option>
                                                <option value="2" @selected(2 == $suratMaster->form_isian->$item->data)>PENDUDUK LUAR
                                                    {{ $desa_pend }}
                                                </option>
                                            </select>
                                        </td>
                                    </tr>

                                    <tr class="warga_desa">
                                        <td>Jenis Kelamin</td>
                                        <td>
                                            <select class="form-control input-sm kategori select2"
                                                name="kategori_individu_sex[{{ $item }}]">
                                                <option value="">SEMUA</option>
                                                @foreach ($form_isian['daftar_jenis_kelamin'] as $key => $data)
                                                    <option value="{{ $key }}" @selected($key == $suratMaster->form_isian->$item->sex)>
                                                        {{ $data }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>

                                    <tr class="warga_desa">
                                        <td>Jenis Peristiwa</td>
                                        <td>
                                            <select class="form-control input-sm select2 kategori"
                                                name="kategori_individu_status_dasar[{{ $item }}]">
                                                <option value="">SEMUA</option>
                                                @foreach ($form_isian['daftar_status_dasar'] as $key => $data)
                                                    <option value="{{ $key }}" @selected($key == $suratMaster->form_isian->$item->status_dasar)>
                                                        {{ $data }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>

                                    <tr class="warga_desa">
                                        <td>Status Hubungan Dalam Keluarga (SHDK)</td>
                                        <td>
                                            <select id="individu_kk_level"
                                                class="form-control input-sm select2 kategori"
                                                name="kategori_individu_kk_level[{{ $item }}]">
                                                <option value="">SEMUA</option>
                                                @foreach ($form_isian['daftar_shdk'] as $key => $data)
                                                    <option value="{{ $key }}" @selected($key == $suratMaster->form_isian->$item->kk_level)>
                                                        {{ $data }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <hr>

                        @include('admin.pengaturan_surat.kategori_isian', ['key_kategori' => $item])
                    </div>
                </div>
            @empty

            @endforelse
        </div>
    </div>
</div>
<div class="modal fade" id="modal-tab" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Tambah Kategori</h4>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="id_rtm">Nama Kategori</label>
                        <input type="text" class="form-control" id="nama_kategori" placeholder="Nama Kategori"
                            value="">
                        <label for="nama_kategori" generated="true" class="error" id="error_category"></label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="reset" class="btn btn-social btn-danger btn-sm"><i class="fa fa-times"></i>
                        Batal</button>
                    <button type="submit" class="btn btn-social btn-info btn-sm" id="btn-new-tab"
                        data-backdrop="false" data-dismiss="modal"><i class="fa fa-check"></i> Simpan</button>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {

            var num = 0;

            $('#btn-new-tab').click(function(e) {
                var checkInput = validateInputCategory($('#nama_kategori').val())
                // console.log(checkInput);
                if (checkInput == 'huruf') {
                    $('#error_category').show()
                    $('#error_category').text('Maksimal 20 huruf.')
                    return false;
                }
                if (checkInput == 'angka') {
                    $('#error_category').show()
                    $('#error_category').text('Tidak boleh ada angka.')
                    return false;
                }
                var nama_kategori = $('#nama_kategori').val().replace(/ /g, '_')
                $('#nama_kategori').val('')
                $('#modal-tab').modal('hide')
                $('#modal-tab').css('display', 'none')
                num++
                e.preventDefault()
                var newTabId = 'tab-' + nama_kategori
                var oldname, newname
                $("#form-utama").clone(true)
                    .map(function() {
                        editElm = $(this)
                            .attr('id', newTabId)
                            .attr('data-id', num)
                            .find('select')
                            .end()
                            .find("#dragable-form-utama")
                            .attr('id', `dragable-${nama_kategori}`)
                            .end();

                        // console.log(editElm[0].querySelector('.sumber-data'));
                        var utama_delete_btn = editElm[0].querySelector('.utama-delete')
                        utama_delete_btn.dataset.kategori = nama_kategori
                        utama_delete_btn.classList.remove('hide')
                        // console.log(utama_delete_btn);
                        var elsumberData = editElm[0].querySelector('.sumber-data')
                        var elkodeIsian = editElm[0].querySelector('.kode-isian')
                        var elorangTua = editElm[0].querySelector('#orang-tua')
                        elorangTua.remove()
                        var elpasangan = editElm[0].querySelector('#data-pasangan')
                        elpasangan.remove()
                        // console.log(elkodeIsian);
                        if (elsumberData != null) {
                            // console.log(321);
                            var selects = editElm[0].querySelectorAll('.sumber-data select');

                            // Menghapus semua atribut dan kelas "select2" dari setiap elemen <select>
                            selects.forEach((elselect2) => {
                                oldname = elselect2.getAttribute('name')
                                newname = `kategori_${oldname}[${nama_kategori}]`
                                // if (oldname == 'data_utama') elselect2.disabled = true
                                elselect2.name = newname
                                elselect2.classList.add('kategori')
                                if (elselect2.classList.contains('select2')) {

                                    elselect2.classList.remove('select2')
                                    elselect2.classList.remove('select2-hidden-accessible')
                                    elselect2.classList.remove('required')
                                    if (elselect2.nextElementSibling != null) elselect2
                                        .nextElementSibling.remove();
                                    // elselect2.nextElementSibling.remove()
                                    elselect2.removeAttribute('data-select2-id')
                                }
                                // console.log(elselect2);
                            });
                        }
                        if (elkodeIsian != null) {
                            var elganda = editElm[0].querySelector('#gandakan-0');
                            // console.log(elganda);
                            // elganda.classList.remove('duplikasi');
                            elganda.classList.add('duplikasi-' + nama_kategori);
                            elganda.classList.add('kategori');
                            elganda.id = 'gandakan-' + nama_kategori + '-0';
                            // console.log(elkodeIsian.querySelectorAll('tr.duplikasi'));
                            // use foreach tr, jika itu tr pertama maka sesuaikan dengan tab data, jika bukan delete
                            var trkode = elkodeIsian.querySelectorAll('tr.duplikasi');
                            trkode.forEach((tr, index) => {
                                var kategori_isian = tr.querySelectorAll(
                                    '.kode-isian input, .kode-isian textarea, .kode-isian select, .kode-isian'
                                );
                                //// console.log(kategori_isian);
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
                        // console.log(elbutton);
                        return editElm;
                    });
                // loadSelect()
                //// console.log(editElm[0]);
                var newNavItem = $(
                    `<li id="list-${nama_kategori}" data-name="${nama_kategori}"><a href="#${newTabId}" data-toggle="tab">${nama_kategori.replace(/_/g, ' ')}</a></li>`
                );

                var inputHidden = $('<input>').attr({
                    type: 'hidden',
                    name: 'kategori[]',
                    value: nama_kategori
                });

                $('.nav-tabs.customized-tab').append(newNavItem);
                $('.tab-content .custom').append(inputHidden)
                $('.tab-content .custom').append(editElm);
                newNavItem.find('a').tab('show');
            });

        });

        function loadSelect() {
            // console.log('load select');
            $('.kategori').select2()
        }

        function hideModal() {
            $('#modal-tab').modal('hide')
        }

        function deleteTab(event) {
            var clicked = event.target;
            // console.log(clicked.dataset.kategori);
            var id = clicked.dataset.kategori
            $(`#tab-${id}`).remove()
            var ulElement = document.getElementsByClassName('customized-tab')[0];
            // console.log(ulElement);
            var liElements = ulElement.getElementsByTagName('li');
            var activeLiElement = ulElement.querySelector('.active');
            var activeLiIndex = Array.prototype.indexOf.call(liElements, activeLiElement);
            var previousLiElement = liElements[activeLiIndex - 1];
            activeLiElement.classList.remove('.active')
            if (previousLiElement) {
                previousLiElement.classList.add('active');
            }
            var prevName = previousLiElement.dataset.name
            // console.log(previousLiElement.dataset.name);
            $(`#list-${id}`).remove()
            if (prevName == 'utama') {
                $(`#tab-${id}`).removeClass('active')
                $(`#form-${prevName}`).addClass('active')
            } else {
                $(`#tab-${prevName}`).show()
            }
        }

        function validateInputCategory(input) {
            // Menghilangkan spasi di awal dan akhir input
            // var trimmedInput = input.trim();
            var trimmedInput = input
            // Memeriksa apakah input hanya terdiri dari huruf
            var lettersOnly = /^[A-Za-z _]+$/;
            var isLettersOnly = lettersOnly.test(trimmedInput);

            // Memeriksa panjang input
            var isWithinMaxLength = trimmedInput.length <= 20;

            if (!isLettersOnly) return 'angka'

            if (!isWithinMaxLength) return 'huruf'

            // Mengembalikan hasil validasi
            return isLettersOnly && isWithinMaxLength;
        }

        // function checkInputCategory(input) {

        // }
    </script>
@endpush
