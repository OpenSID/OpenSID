@extends('admin.layouts.index')
@include('admin.layouts.components.asset_validasi')

@section('title')
    <h1>
        Pembangunan
        <small>{{ $action }} Data</small>
    </h1>
@endsection

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ ci_route('admin_pembangunan') }}">Daftar Pembangunan</a></li>
    <li class="active">{{ $action }} Data</li>
@endsection

@section('content')
    @include('admin.layouts.components.notifikasi')
    {!! form_open($form_action, 'class="form-horizontal" enctype="multipart/form-data" id="validasi"') !!}
    <div class="row">
        <div class="col-md-9">
            <div class="box box-info">
                <div class="box-header with-border">
                    <a href="{{ ci_route('admin_pembangunan') }}" class="btn btn-social btn-info btn-sm visible-xs-block visible-sm-inline-block visible-md-inline-block visible-lg-inline-block"><i class="fa fa-arrow-circle-left"></i> Kembali Ke Daftar Pembangunan</a>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="judul">Nama Kegiatan</label>
                        <div class="col-sm-9">
                            <input
                                id="judul"
                                name="judul"
                                class="form-control input-sm strip_tags judul required"
                                value="{{ e($main->judul) }}"
                                type="text"
                                maxlength="50"
                                minlength="5"
                                maxlength="100"
                                placeholder="Nama Kegiatan Pembangunan"
                            />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="volume">Volume</label>
                        <div class="col-sm-9">
                            <input
                                maxlength="50"
                                class="form-control input-sm strip_tags required"
                                name="volume"
                                id="volume"
                                value="{{ $main->volume }}"
                                type="text"
                                placeholder="Volume Pembangunan"
                            />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="waktu">Waktu</label>
                        <div class="col-sm-6">
                            <input
                                maxlength="50"
                                class="form-control number input-sm required"
                                name="waktu"
                                id="waktu"
                                value="{{ $main->waktu }}"
                                type="text"
                                placeholder="Lamanya pembangunan"
                            />
                        </div>
                        <div class="col-sm-3">
                            <select class="form-control input-sm select2 required" name="satuan_waktu">
                                @foreach ($satuan_waktu as $key => $value)
                                    <option value="{{ $key }}" @selected($key == $main->satuan_waktu)>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="sumber_dana">Sumber Dana</label>
                        <div class="col-sm-9">
                            <select class="form-control input-sm select2" id="sumber_dana" name="sumber_dana" style="width:100%;">
                                @foreach ($sumber_dana as $value)
                                    <option @selected($value === $main->sumber_dana) value="{{ $value }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-12 control-label">Tahun Anggaran</label>
                                <div class="col-sm-12">
                                    <select class="form-control input-sm select2" id="tahun_anggaran" name="tahun_anggaran" style="width:100%;">
                                        @foreach (tahun(1999) as $value)
                                            <option value="{{ $value }}" @selected($value == $main->tahun_anggaran)>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-12 control-label">Anggaran</label>
                                <div class="col-sm-12">
                                    <input
                                        class="form-control input-sm required bilangan"
                                        name="anggaran"
                                        id="anggaran"
                                        value="{{ $main->anggaran }}"
                                        type="text"
                                        placeholder="Anggaran"
                                        readonly
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-12 control-label">Sumber Biaya Pemerintah</label>
                                <div class="col-sm-12">
                                    <input
                                        id="sumber_biaya_pemerintah"
                                        name="sumber_biaya_pemerintah"
                                        onkeyup="cek()"
                                        class="form-control input-sm required bilangan"
                                        maxlength="12"
                                        type="text"
                                        placeholder="Sumber Biaya Pemerintah"
                                        value="{{ $main->sumber_biaya_pemerintah }}"
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-12 control-label">Sumber Biaya Provinsi</label>
                                <div class="col-sm-12">
                                    <input
                                        id="sumber_biaya_provinsi"
                                        name="sumber_biaya_provinsi"
                                        onkeyup="cek()"
                                        class="form-control input-sm required bilangan"
                                        maxlength="12"
                                        type="text"
                                        placeholder="Sumber Biaya Provinsi"
                                        value="{{ $main->sumber_biaya_provinsi }}"
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-12 control-label">Sumber Biaya Kab / Kota</label>
                                <div class="col-sm-12">
                                    <input
                                        id="sumber_biaya_kab_kota"
                                        name="sumber_biaya_kab_kota"
                                        class="form-control input-sm required bilangan"
                                        maxlength="12"
                                        onkeyup="cek()"
                                        type="text"
                                        placeholder="Sumber Biaya Kab / Kota"
                                        value="{{ $main->sumber_biaya_kab_kota }}"
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-sm-12 control-label">Sumber Biaya Swadaya</label>
                                <div class="col-sm-12">
                                    <input
                                        id="sumber_biaya_swadaya"
                                        name="sumber_biaya_swadaya"
                                        class="form-control input-sm required bilangan"
                                        maxlength="12"
                                        type="text"
                                        onkeyup="cek()"
                                        placeholder="Sumber Biaya Swadaya"
                                        value="{{ $main->sumber_biaya_swadaya }}"
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="sifat_proyek">Sifat Proyek</label>
                        <div class="col-sm-9">
                            <select class="form-control input-sm select2 required" id="sifat_proyek" name="sifat_proyek">
                                <option value="">-- Pilih Sifat Proyek --</option>
                                <option value="BARU" @selected($main->sifat_proyek == 'BARU')>BARU</option>
                                <option value="LANJUTAN" @selected($main->sifat_proyek == 'LANJUTAN')>LANJUTAN</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="pelaksana_kegiatan">Pelaksana Kegiatan</label>
                        <div class="col-sm-9">
                            <input
                                maxlength="50"
                                class="form-control input-sm strip_tags required"
                                name="pelaksana_kegiatan"
                                id="pelaksana_kegiatan"
                                value="{{ $main->pelaksana_kegiatan }}"
                                type="text"
                                placeholder="Pelaksana Kegiatan Pembangunan"
                            />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="pelaksana_kegiatan">Lokasi Pembangunan</label>
                        <div class="col-sm-9">
                            <div class="row">
                                <div class="btn-group col-sm-6" data-toggle="buttons">
                                    <label class="btn btn-info btn-sm form-check-label col-sm-6 {{ $main->lokasi ? null : 'active' }}">
                                        <input type="radio" name="jenis_lokasi" class="form-check-input" value="1" autocomplete="off" onchange="pilih_lokasi(this.value);"> Pilih Lokasi
                                    </label>
                                    <label class="btn btn-info btn-sm form-check-label col-sm-6 {{ $main->lokasi ? 'active' : null }}">
                                        <input type="radio" name="jenis_lokasi" class="form-check-input" value="2" autocomplete="off" onchange="pilih_lokasi(this.value);"> Tulis Manual
                                    </label>
                                </div>
                            </div>
                            <br>
                            <div id="pilih">
                                <select class="form-control input-sm select2 required" id="id_lokasi" name="id_lokasi">
                                    <option value="">-- Pilih Lokasi Pembangunan --</option>
                                    @foreach ($list_lokasi as $item)
                                        <option value="{{ $item['id'] }}" @selected($item['id'] == $main->id_lokasi)>{{ strtoupper($item['dusun']) }} {{ empty($item['rw']) ? '' : " - RW  {$item['rw']}" }} {{ empty($item['rt']) ? '' : " / RT  {$item['rt']}" }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div id="manual">
                                <textarea id="lokasi" class="form-control input-sm strip_tags required" type="text" placeholder="Lokasi" name="lokasi" rows="3">{{ e($main->lokasi) }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="manfaat">Manfaat</label>
                        <div class="col-sm-9">
                            <textarea id="manfaat" name="manfaat" class="form-control input-sm strip_tags required" name="manfaat" placeholder="Manfaat" rows="3">{{ e($main->manfaat) }}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label" for="manfaat">Keterangan</label>
                        <div class="col-sm-9">
                            <textarea id="keterangan" class="form-control input-sm strip_tags required" name="keterangan" placeholder="Keterangan" rows="3">{{ e($main->keterangan) }}</textarea>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="reset" class="btn btn-social btn-danger btn-sm" onclick="reset_form($(this).val());"><i class="fa fa-times"></i> Batal</button>
                    <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i> Simpan</button>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Gambar Utama</h3>
                    <div class="box-tools">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    @if (is_file(LOKASI_GALERI . $main->foto))
                        <img class="img-responsive" id="previewImage" src="{{ to_base64(LOKASI_GALERI . $main->foto) }}" alt="Gambar Utama Pembangunan">
                    @else
                        <img class="img-responsive" id="previewImage" src="{{ asset('images/404-image-not-found.jpg') }}" alt="Gambar Utama Pembangunan" />
                    @endif
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control" id="file_path">
                        <input type="file" class="hidden" id="file" name="foto" accept=".jpg,.jpeg,.png">
                        <span class="input-group-btn">
                            <button type="button" class="btn btn-info btn-flat" id="file_browser"><i class="fa fa-search"></i></button>
                        </span>
                        <span class="input-group-addon" style="background-color: red; border: 1px solid #ccc;">
                            <input type="checkbox" title="Centang Untuk Hapus Gambar" name="hapus_foto" value="hapus">
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </form>
@endsection

@push('scripts')
    <script>
        var sb_pem = document.getElementById('sumber_biaya_pemerintah');
        var sb_prov = document.getElementById('sumber_biaya_provinsi');
        var sb_kab = document.getElementById('sumber_biaya_kab_kota');
        var sb_swad = document.getElementById('sumber_biaya_swadaya');
        var aggaran = document.getElementById('anggaran');

        function getSum(total, num) {
            return total + Math.round(num);
        }

        function cek() {
            const numbers = [sb_pem.value, sb_prov.value, sb_kab.value, sb_swad.value];
            var biaya = numbers.reduce(getSum, 0);
            document.getElementById('anggaran').value = biaya;
            var total_anggaran = aggaran.value;
        };

        $(document).ready(function() {
            $("form").submit(function(e) {
                const numbers = [sb_pem.value, sb_prov.value, sb_kab.value, sb_swad.value];
                var biaya = numbers.reduce(getSum, 0);
                var total_anggaran = aggaran.value;
                if (biaya > total_anggaran) {
                    alert('Total rincian sumber biaya tidak boleh melebihi anggaran.');
                    e.preventDefault(e);
                }
            });
        });

        function pilih_lokasi(pilih) {
            if (pilih == 1) {
                $('#lokasi').val(null);
                $('#lokasi').removeClass('required');
                $("#manual").hide();
                $("#pilih").show();
                $('#id_lokasi').addClass('required');
            } else {
                $('#id_lokasi').val(null);
                $('#id_lokasi').trigger('change', true);
                $('#id_lokasi').removeClass('required');
                $("#manual").show();
                $('#lokasi').addClass('required');
                $("#pilih").hide();
            }
        }

        $(document).ready(function() {
            pilih_lokasi({{ null === $main->id_lokasi && $main ? 2 : 1 }});
        });

        document.getElementById('file').onchange = function(e) {
            var file = e.target.files[0];
            if (file) {
                var allowedExtensions = document.getElementById('file').accept.split(',');
                var fileExtension = file.name.split('.').pop().toLowerCase();
                if (allowedExtensions.includes('.' + fileExtension)) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        var output = document.getElementById('previewImage');
                        output.src = e.target.result;
                    };

                    reader.readAsDataURL(file);
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Kesalahan',
                        text: 'Mohon pilih file gambar dengan format ' + document.getElementById('file').accept,
                        timer: 3000,
                    });
                }
            }
        };
    </script>
@endpush
