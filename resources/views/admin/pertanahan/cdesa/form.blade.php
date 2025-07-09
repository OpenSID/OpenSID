@extends('admin.layouts.index')
@include('admin.layouts.components.asset_validasi')

@section('title')
    <h1>
        Daftar C-Desa
        <small>{{ $action }} Data</small>
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ ci_route('cdesa') }}"> Daftar C-Desa</a></li>
    <li class="active">{{ $action }} Data</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')

    <div class="box box-info">
        <div class="box-header with-border">
            <a href="{{ ci_route('cdesa') }}" class="btn btn-social btn-info btn-sm btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block">
                <i class="fa fa-arrow-circle-left "></i>Kembali ke Daftar C-Desa
            </a>
        </div>
        <div class="box-body">
            {!! form_open($form_action, 'class="form-horizontal" id="validasi"') !!}
            <div class="box-body">
                <div class="form-group @error('jenis_pemilik') has-error @enderror">
                    <label class="col-sm-3 control-label">Jenis Pemilik</label>
                    <div class="col-sm-8">
                        <div class="btn-group col-xs-12 col-sm-8" style="margin-left: -16px" data-toggle="buttons">
                            <label class="btn btn-info btn-flat btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label @active($cdesa['jenis_pemilik'] != 2)">
                                <input
                                    type="radio"
                                    name="jenis_pemilik"
                                    class="form-check-input"
                                    value="1"
                                    autocomplete="off"
                                    @checked($cdesa['jenis_pemilik'] != 2)
                                    onchange="pilih_pemilik(this.value);"
                                >Warga Desa
                            </label>
                            <label class="btn btn-info btn-flat btn-sm col-xs-6 col-sm-5 col-lg-3 form-check-label @active($cdesa['jenis_pemilik'] == 2)">
                                <input
                                    type="radio"
                                    name="jenis_pemilik"
                                    class="form-check-input"
                                    value="2"
                                    autocomplete="off"
                                    @checked($cdesa['jenis_pemilik'] == 2)
                                    onchange="pilih_pemilik(this.value);"
                                >Warga Luar Desa
                            </label>
                        </div>
                    </div>
                </div>

                <div id="warga_desa">
                    <div class="form-group">
                        <label for="id_penduduk" class="col-sm-3 control-label">Cari Nama Pemilik</label>
                        <div class="col-sm-8">
                            <select
                                autofocus
                                name="id_penduduk"
                                id="id_penduduk"
                                class="form-control input-sm isi-penduduk-desa required select2-nik-ajax"
                                data-url="{{ ci_route('cdesa.apipendudukdesa') }}"
                                data-placeholder="-- Cari NIK / Tag ID Card / Nama Penduduk --"
                                onchange="loadDataPenduduk(this)"
                            >
                                @if ($cdesa->jenis_pemilik == 1)
                                    <option value="{{ $cdesa->id_pemilik }}" selected>
                                        {{ $cdesa->nik_pemilik . ' - ' . $cdesa->nama_pemilik . ' - ' . $cdesa->alamat }}
                                    </option>
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="data_penduduk_desa"></div>
                </div>

                <div id="warga_luar_desa">
                    <div class="form-group @error('nik_pemilik_luar') has-error @enderror">
                        <label for="c_desa" class="col-sm-3 control-label">NIK Pemilik</label>
                        <div class="col-sm-8">
                            <input
                                class="form-control input-sm required nik"
                                type="text"
                                placeholder="NIK Pemilik"
                                id="nik_pemilik_luar"
                                name="nik_pemilik_luar"
                                value="{{ $cdesa['nik_pemilik_luar'] }}"
                                @disabled($pemilik)
                            >
                        </div>
                    </div>
                    <div class="form-group @error('nama_pemilik_luar') has-error @enderror">
                        <label for="c_desa" class="col-sm-3 control-label">Nama Pemilik</label>
                        <div class="col-sm-8">
                            <input
                                class="form-control input-sm required"
                                type="text"
                                placeholder="Nama Pemilik Luar"
                                id="nama_pemilik_luar"
                                name="nama_pemilik_luar"
                                value="{{ $cdesa['nama_pemilik_luar'] }}"
                                @disabled($pemilik)
                            >
                        </div>
                    </div>
                    <div class="form-group @error('alamat_pemilik_luar') has-error @enderror">
                        <label for="c_desa" class="col-sm-3 control-label">Alamat Pemilik</label>
                        <div class="col-sm-8">
                            <input
                                class="form-control input-sm required"
                                type="text"
                                placeholder="Alamat Pemilik Luar"
                                id="alamat_pemilik_luar"
                                name="alamat_pemilik_luar"
                                value="{{ $cdesa['alamat_pemilik_luar'] }}"
                                @disabled($pemilik)
                            >
                        </div>
                    </div>
                </div>

                <div class="form-group @error('nomor') has-error @enderror">
                    <label class="col-sm-3 control-label">No. C-Desa</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control input-sm angka required" name="nomor" placeholder="Nomor Surat C-DESA" value="{{ old('nomor', $cdesa->nomor) }}" />
                        @error('nomor')
                            <span class="help-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="form-group @error('nomor') has-error @enderror">
                    <label class="col-sm-3 control-label">Nama Pemilik Tertulis di C-Desa</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control input-sm nama required" name="nama_kepemilikan" placeholder="Nama pemilik sebagaimana tertulis di Surat C-DESA" name="nama_kepemilikan" value="{{ old('nomor', $cdesa->nama_kepemilikan) }}" />
                        @error('nama_kepemilikan')
                            <span class="help-block">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            <div class="box-footer">
                <button type="reset" class="btn btn-social btn-danger btn-sm"><i class="fa fa-times"></i> Batal</button>
                <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i>
                    Simpan</button>
            </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/custom-select2.js') }}"></script>
    <script>
        var penduduk = "{{ $cdesa->cdesaPenduduk['id_pend'] }}";
        console.log(penduduk);

        if (penduduk) {
            document.addEventListener("DOMContentLoaded", function() {
                var selectElement = document.getElementById("id_penduduk");
                loadDataPenduduk(selectElement);
            });
        }

        $(document).ready(function() {

            $('#tipe').change(function() {
                var id = $(this).val();
                $.ajax({
                    url: "<?= site_url('data_persil/kelasid') ?>",
                    method: "POST",
                    data: {
                        id: id
                    },
                    async: true,
                    dataType: 'json',
                    success: function(data) {
                        var html = '';
                        var i;
                        for (i = 0; i < data.length; i++) {
                            html += '<option value=' + data[i].id + '>' + data[i].kode + ' ' + data[i].ndesc + '</option>';
                        }
                        $('#kelas').html(html);
                    }
                });
                return false;
            });

            pilih_pemilik(<?= $cdesa['jenis_pemilik'] ?: 1 ?>);

        });

        function loadDataPenduduk(elm) {
            let _val = $(elm).val()
            if (!$.isEmptyObject(_val)) {
                $.get('{{ ci_route('cdesa.detail_penduduk') }}', {
                    id_penduduk: _val
                }, function(data) {
                    $('.data_penduduk_desa').html(data.html)
                }, 'json')
            }
        }

        function pilih_lokasi(pilih) {
            if (pilih == 1) {
                $("#manual").hide();
                $("#pilih").show();
            } else {
                $("#manual").removeClass('hidden');
                $("#manual").show();
                $("#pilih").hide();
            }
        }

        function pilih_pemilik(pilih) {
            $('#jenis_pemilik').val(pilih);
            if (pilih == 1) {
                if ($('#id_penduduk').val() == '') {
                    $('input[name=c_desa]').attr('disabled', 'disabled');
                    $('input[name=nama_kepemilikan]').attr('disabled', 'disabled');
                }
                $('#nik_pemilik_luar').val('');
                $('#nik_pemilik_luar').removeClass('required');
                $('#nama_pemilik_luar').val('');
                $('#nama_pemilik_luar').removeClass('required');
                $('#alamat_pemilik_luar').val('');
                $('#alamat_pemilik_luar').removeClass('required');
                $("#warga_luar_desa").hide();
                $('#id_penduduk').addClass('required');
                $("#warga_desa").show();
            } else {
                $('#id_penduduk').removeClass('required');
                $("#warga_desa").hide();
                $("#warga_luar_desa").show();
                $('#nik_pemilik_luar').addClass('required');
                $('#nama_pemilik_luar').addClass('required');
                $('#alamat_pemilik_luar').addClass('required');
                $('input[name=c_desa]').removeAttr('disabled');
                $('input[name=nama_kepemilikan]').removeAttr('disabled');
                if ($('#id_penduduk').val() != '') {
                    $('#id_penduduk').val('');
                    $('#id_penduduk').change();
                }
            }
        }

        function ubah_pemilik(jenis_pemilik) {
            $('#main').submit();
        }
    </script>
@endpush
