<div class="nav-tabs-custom">
    <ul class="nav nav-tabs" id="nav-tab-info" role="tablist">
        <li class="active"><a href="#info-sync" data-toggle="tab" id="nav-info-sycn"><strong>Sinkronisasi Data</strong></a></li>
        <li><a href="#dtks-info-program-keluarga" data-toggle="tab" id="nav-info-program-keluarga"><strong>Pengaturan Program
                    Bantuan
                    Keluarga</strong></a></li>
        <li><a href="#dtks-info-program-anggota" data-toggle="tab" id="nav-info-program-anggota"><strong>Pengaturan Program
                    Perlindungan Sosial Anggota</strong></a></li>
    </ul>
</div>
<div class="tab-content">

    <div class="tab-pane active" id="info-sync">
        <b>Jika dalam satu rumah tangga terdapat lebih dari satu keluarga, maka akan dibuatkan 2 data untuk masing-masing keluarga</b><br><br>
        <ol style="padding: 0 0 0 25px;">
            <li>
                kode_provinsi, kode_kabupaten, kode_kecamatan dan kode_desa (sumber data kode bps TrackSID)
            </li>
            <li>
                nama sls/non sls = alamat, no kk, nik kk (sumber data : kepala keluarga)
            </li>
            <li>
                anggota keluarga <b>(agt)</b> [nama, nik, no.kk, ket.keberadaan, jenis kelamin, tgl lahir, umur,
                stat.perkawinan, stat.kehamilan, penyakit kronis/menahun]
                (sumber <a id="rtm_clear" href="{{ ci_route('rtm/clear') }}">Kependudukan/Rumah Tangga</a> hanya yang
                berstatus
                masih hidup)
            </li>
            <li>
                agt [hubungan dengan kepala keluarga] (sumber data: keluarga)
            </li>
            <li>
                agt [kartu identitas] (akta_lahir, sumber data : kia sebagai ibu/anak atau identitas_elektronik = kia,
                identitas_elektronik = ktp_el)
            </li>
            <li>
                agt [partisipasi sekolah] (untuk yang tidak/belum pernah sekolah dan D1 s.d S3, sumber data : pendidikan.sedang dan
                pendidikan.kk)
            </li>
            <li>
                agt [kelas tertinggi = tamat dan lulus] (untuk yang tamat sd sampai S3, sumber data : pendidikan.sedang dan
                pendidikan.kk)
            </li>
        </ol>
    </div>
    <div class="tab-pane" id="dtks-info-program-keluarga">
        {!! form_open(ci_route('dtks.savePengaturan') . '/2', 'class="form-program" id="form-program-keluarga"') !!}
        <input type="hidden" name='tipe_save' value='pengaturan_program'>
        <div class="table-responsive">
            <br>
            <p>Data yang digunakan: kepesertaan setahun terakhir (ya/tidak) dan periode terakhir mendapatkan program ini</p>
            <table class="table table-bordered table-hover" style="margin-bottom: 0px;">
                <thead class="bg-gray disabled color-palette">
                    <tr>
                        <td>Jenis Program</td>
                        <td>Relasi dengan Data Program Bantuan <?= config_item('nama_aplikasi') ?></td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>501.a. Program Bantuan Sosial Sembako/ BPNT</td>
                        <td>
                            <select name="501a" class="form-control input-sm select2" style="width:100%;">
                                <option disabled selected value="">----- Pilih -----</option>
                                <?php foreach($daftar_bantuan_keluarga as $item): ?>
                                <option value="<?= $item->id ?>" @selected($item->id . '' === ($name_501a . '' ?? ''))>
                                    {{ $item->nama }} | {{ $item->sdate }} s.d {{ $item->edate }} | Asal Dana :
                                    {{ $item->asaldana }}
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <button type="button" class="btn btn-sm btn-danger hapus-pengaturan-program">hapus</button>
                        </td>
                    </tr>
                    <tr>
                        <td>501.b. Program Keluarga Harapan (PKH)</td>
                        <td>
                            <select name="501b" class="form-control input-sm select2" style="width:100%;">
                                <option disabled selected value="">----- Pilih -----</option>
                                <?php foreach($daftar_bantuan_keluarga as $item): ?>
                                <option value="<?= $item->id ?>" @selected($item->id . '' === ($name_501b . '' ?? ''))>
                                    {{ $item->nama }} | {{ $item->sdate }} s.d {{ $item->edate }} | Asal Dana :
                                    {{ $item->asaldana }}
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <button type="button" class="btn btn-sm btn-danger hapus-pengaturan-program">hapus</button>
                        </td>
                    </tr>
                    <tr>
                        <td>501.c. Program Bantuan Langsung Tunai (BLT) Dana Desa</td>
                        <td>
                            <select name="501c" class="form-control input-sm select2" style="width:100%;">
                                <option disabled selected value="">----- Pilih -----</option>
                                <?php foreach($daftar_bantuan_keluarga as $item): ?>
                                <option value="<?= $item->id ?>" @selected($item->id . '' === ($name_501c . '' ?? ''))>
                                    {{ $item->nama }} | {{ $item->sdate }} s.d {{ $item->edate }} | Asal Dana :
                                    {{ $item->asaldana }}
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <button type="button" class="btn btn-sm btn-danger hapus-pengaturan-program">hapus</button>
                        </td>
                    </tr>
                    <tr>
                        <td>501.d. Program Subsidi Listrik (gratis/pemotongan biaya)</td>
                        <td>
                            <select name="501d" class="form-control input-sm select2" style="width:100%;">
                                <option disabled selected value="">----- Pilih -----</option>
                                <?php foreach($daftar_bantuan_keluarga as $item): ?>
                                <option value="<?= $item->id ?>" @selected($item->id . '' === ($name_501d . '' ?? ''))>
                                    {{ $item->nama }} | {{ $item->sdate }} s.d {{ $item->edate }} | Asal Dana :
                                    {{ $item->asaldana }}
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <button type="button" class="btn btn-sm btn-danger hapus-pengaturan-program">hapus</button>
                        </td>
                    </tr>
                    <tr>
                        <td>501.e. Program Bantuan Pemerintah Daerah</td>
                        <td>
                            <select name="501e" class="form-control input-sm select2" style="width:100%;">
                                <option disabled selected value="">----- Pilih -----</option>
                                <?php foreach($daftar_bantuan_keluarga as $item): ?>
                                <option value="<?= $item->id ?>" @selected($item->id . '' === ($name_501e . '' ?? ''))>
                                    {{ $item->nama }} | {{ $item->sdate }} s.d {{ $item->edate }} | Asal Dana :
                                    {{ $item->asaldana }}
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <button type="button" class="btn btn-sm btn-danger hapus-pengaturan-program">hapus</button>
                        </td>
                    </tr>
                    <tr>
                        <td>501.f. Program Bantuan Subsidi Pupuk</td>
                        <td>
                            <select name="501f" class="form-control input-sm select2" style="width:100%;">
                                <option disabled selected value="">----- Pilih -----</option>
                                <?php foreach($daftar_bantuan_keluarga as $item): ?>
                                <option value="<?= $item->id ?>" @selected($item->id . '' === ($name_501f . '' ?? ''))>
                                    {{ $item->nama }} | {{ $item->sdate }} s.d {{ $item->edate }} | Asal Dana :
                                    {{ $item->asaldana }}
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <button type="button" class="btn btn-sm btn-danger hapus-pengaturan-program">hapus</button>
                        </td>
                    </tr>
                    <tr>
                        <td>501.g. Program Subsidi LPG</td>
                        <td>
                            <select name="501g" class="form-control input-sm select2" style="width:100%;">
                                <option disabled selected value="">----- Pilih -----</option>
                                <?php foreach($daftar_bantuan_keluarga as $item): ?>
                                <option value="<?= $item->id ?>" @selected($item->id . '' === ($name_501g . '' ?? ''))>
                                    {{ $item->nama }} | {{ $item->sdate }} s.d {{ $item->edate }} | Asal Dana :
                                    {{ $item->asaldana }}
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <button type="button" class="btn btn-sm btn-danger hapus-pengaturan-program">hapus</button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <button type="submit" class="btn btn-social btn-info btn-sm" style="position:absolute;z-index:1;right:0;bottom:-44px;">
                <i class="fa fa-check"></i> Simpan
            </button>
        </div>
        </form>
    </div>
    <div class="tab-pane" id="dtks-info-program-anggota">
        {!! form_open(ci_route('dtks.savePengaturan') . '/2', 'class="form-program" id="form-program-anggota"') !!}
        <input type="hidden" name='tipe_save' value='pengaturan_program'>
        <br>
        <p>Data yang digunakan: penerima/pemilik jaminan atau ikut serta pada program dalam satu tahun terakhir</p>
        <div class="table-responsive">
            <table class="table table-bordered table-hover" style="margin-bottom: 0px;">
                <thead class="bg-gray disabled color-palette">
                    <tr>
                        <td>Jenis Program</td>
                        <td>Relasi dengan Data Jaminan/Program Bantuan <?= config_item('nama_aplikasi') ?></td>
                        <td>Nilai bawaan jika anggota <b>tidak</b> ikut serta/<b>tidak</b> memilki <b>satupun</b> program/jaminan tersebut</td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            431.a1. Jaminan Kesehatan <br><b>(PBI JKN)</b>
                        </td>
                        <td>
                            <select name="431a1" class="form-control input-sm select2" style="width:100%;">
                                <option disabled selected value="">----- Pilih -----</option>
                                <?php foreach($daftar_bantuan_anggota as $item): ?>
                                <option value="<?= $item->id ?>" @selected($item->id . '' === ($name_431a1 . '' ?? ''))>
                                    {{ $item->nama }} | {{ $item->sdate }} s.d {{ $item->edate }} | Asal Dana :
                                    {{ $item->asaldana }}
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <button type="button" class="btn btn-sm btn-danger hapus-pengaturan-program">hapus</button>
                        </td>
                        <td rowspan="4">
                            <b>Untuk 431.a1. s.d 431.a4. Jika ada program.</b>
                            <br>
                            <br>
                            <select name="431a1_431a4_default" class="form-control input-sm select2" style="width:100%;">
                                <option value="">Sesuaikan manual</option>
                                <option value="0" @selected(($name_431a1_431a4_default . '' ?? '') == '0')>0. Tidak Memiliki</option>
                                <option value="99" @selected(($name_431a1_431a4_default . '' ?? '') == '99')>99. Tidak Tahu</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            431.a2. Jaminan Kesehatan <br><b>(JKN Mandiri)</b>
                        </td>
                        <td>
                            <select name="431a2" class="form-control input-sm select2" style="width:100%;">
                                <option disabled selected value="">----- Pilih -----</option>
                                <?php foreach($daftar_bantuan_anggota as $item): ?>
                                <option value="<?= $item->id ?>" @selected($item->id . '' === ($name_431a2 . '' ?? ''))>
                                    {{ $item->nama }} | {{ $item->sdate }} s.d {{ $item->edate }} | Asal Dana :
                                    {{ $item->asaldana }}
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <button type="button" class="btn btn-sm btn-danger hapus-pengaturan-program">hapus</button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            431.a3. Jaminan Kesehatan <br><b>(JKN Pemberi Kerja)</b>
                        </td>
                        <td>
                            <select name="431a3" class="form-control input-sm select2" style="width:100%;">
                                <option disabled selected value="">----- Pilih -----</option>
                                <?php foreach($daftar_bantuan_anggota as $item): ?>
                                <option value="<?= $item->id ?>" @selected($item->id . '' === ($name_431a3 . '' ?? ''))>
                                    {{ $item->nama }} | {{ $item->sdate }} s.d {{ $item->edate }} | Asal Dana :
                                    {{ $item->asaldana }}
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <button type="button" class="btn btn-sm btn-danger hapus-pengaturan-program">hapus</button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            431.a4. Jaminan Kesehatan <br><b>(Lainnya)</b>
                        </td>
                        <td>
                            <select name="431a4" class="form-control input-sm select2" style="width:100%;">
                                <option disabled selected value="">----- Pilih -----</option>
                                <?php foreach($daftar_bantuan_anggota as $item): ?>
                                <option value="<?= $item->id ?>" @selected($item->id . '' === ($name_431a4 . '' ?? ''))>
                                    {{ $item->nama }} | {{ $item->sdate }} s.d {{ $item->edate }} | Asal Dana :
                                    {{ $item->asaldana }}
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <button type="button" class="btn btn-sm btn-danger hapus-pengaturan-program">hapus</button>
                        </td>
                    </tr>
                </tbody>
                <tbody>
                    <tr>
                        <td>
                            431.b. Program Pra-Kerja
                        </td>
                        <td>
                            <select name="431b" class="form-control input-sm select2" style="width:100%;">
                                <option disabled selected value="">----- Pilih -----</option>
                                <?php foreach($daftar_bantuan_anggota as $item): ?>
                                <option value="<?= $item->id ?>" @selected($item->id . '' === ($name_431b . '' ?? ''))>
                                    {{ $item->nama }} | {{ $item->sdate }} s.d {{ $item->edate }} | Asal Dana :
                                    {{ $item->asaldana }}
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <button type="button" class="btn btn-sm btn-danger hapus-pengaturan-program">hapus</button>
                        </td>
                        <td>
                            <select name="431b_default" class="form-control input-sm select2" style="width:100%;">
                                <option selected value="">Sesuaikan manual</option>
                                <option value="2" @selected(($name_431b_default . '' ?? '') == '2')>2. Tidak</option>
                                <option value="8" @selected(($name_431b_default . '' ?? '') == '8')>8. Tidak Tahu</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            431.c. Program Kredit Usaha Rakyat (KUR)
                        </td>
                        <td>
                            <select name="431c" class="form-control input-sm select2" style="width:100%;">
                                <option disabled selected value="">----- Pilih -----</option>
                                <?php foreach($daftar_bantuan_anggota as $item): ?>
                                <option value="<?= $item->id ?>" @selected($item->id . '' === ($name_431c . '' ?? ''))>
                                    {{ $item->nama }} | {{ $item->sdate }} s.d {{ $item->edate }} | Asal Dana :
                                    {{ $item->asaldana }}
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <button type="button" class="btn btn-sm btn-danger hapus-pengaturan-program">hapus</button>
                        </td>
                        <td>
                            <select name="431c_default" class="form-control input-sm select2" style="width:100%;">
                                <option value="">Sesuaikan manual</option>
                                <option value="2" @selected(($name_431c_default . '' ?? '') == '2')>2. Tidak</option>
                                <option value="8" @selected(($name_431c_default . '' ?? '') == '8')>8. Tidak Tahu</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            431.d. Program Pembiayaan Ultra Mikro (UMI)
                        </td>
                        <td>
                            <select name="431d" class="form-control input-sm select2" style="width:100%;">
                                <option disabled selected value="">----- Pilih -----</option>
                                <?php foreach($daftar_bantuan_anggota as $item): ?>
                                <option value="<?= $item->id ?>" @selected($item->id . '' === ($name_431d . '' ?? ''))>
                                    {{ $item->nama }} | {{ $item->sdate }} s.d {{ $item->edate }} | Asal Dana :
                                    {{ $item->asaldana }}
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <button type="button" class="btn btn-sm btn-danger hapus-pengaturan-program">hapus</button>
                        </td>
                        <td>
                            <select name="431d_default" class="form-control input-sm select2" style="width:100%;">
                                <option value="">Sesuaikan manual</option>
                                <option value="2" @selected(($name_431d_default . '' ?? '') == '2')>2. Tidak</option>
                                <option value="8" @selected(($name_431d_default . '' ?? '') == '8')>8. Tidak Tahu</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            431.e. Program Indonesia Pintar (PIP)
                        </td>
                        <td>
                            <select name="431e" class="form-control input-sm select2" style="width:100%;">
                                <option disabled selected value="">----- Pilih -----</option>
                                <?php foreach($daftar_bantuan_anggota as $item): ?>
                                <option value="<?= $item->id ?>" @selected($item->id . '' === ($name_431e . '' ?? ''))>
                                    {{ $item->nama }} | {{ $item->sdate }} s.d {{ $item->edate }} | Asal Dana :
                                    {{ $item->asaldana }}
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <button type="button" class="btn btn-sm btn-danger hapus-pengaturan-program">hapus</button>
                        </td>
                        <td>
                            <select name="431e_default" class="form-control input-sm select2" style="width:100%;">
                                <option value="">Sesuaikan manual</option>
                                <option value="2" @selected(($name_431e_default . '' ?? '') == '2')>2. Tidak</option>
                                <option value="8" @selected(($name_431e_default . '' ?? '') == '8')>8. Tidak Tahu</option>
                            </select>
                        </td>
                    </tr>
                </tbody>
                <tbody>
                    <tr>
                        <td>
                            431.f1. Jaminan Ketenagakerjaan <br><b>(BPJS Jaminan Kecelakaan Kerja)</b>
                        </td>
                        <td>
                            <select name="431f1" class="form-control input-sm select2" style="width:100%;">
                                <option disabled selected value="">----- Pilih -----</option>
                                <?php foreach($daftar_bantuan_anggota as $item): ?>
                                <option value="<?= $item->id ?>" @selected($item->id . '' === ($name_431f1 . '' ?? ''))>
                                    {{ $item->nama }} | {{ $item->sdate }} s.d {{ $item->edate }} | Asal Dana :
                                    {{ $item->asaldana }}
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <button type="button" class="btn btn-sm btn-danger hapus-pengaturan-program">hapus</button>
                        </td>
                        <td rowspan="5">
                            <b>Untuk 431.f1. s.d 431.f5. Jika ada program.</b>
                            <br>
                            <br>
                            <select name="431f1_431f5_default" class="form-control input-sm select2" style="width:100%;">
                                <option value="">Sesuaikan manual</option>
                                <option value="0" @selected(($name_431f1_431f5_default . '' ?? '') == '0')>0. Tidak Memiliki</option>
                                <option value="99" @selected(($name_431f1_431f5_default . '' ?? '') == '99')>99. Tidak Tahu</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            431.f2. Jaminan Ketenagakerjaan <br><b>(BPJS Jaminan Kematian)</b>
                        </td>
                        <td>
                            <select name="431f2" class="form-control input-sm select2" style="width:100%;">
                                <option disabled selected value="">----- Pilih -----</option>
                                <?php foreach($daftar_bantuan_anggota as $item): ?>
                                <option value="<?= $item->id ?>" @selected($item->id . '' === ($name_431f2 . '' ?? ''))>
                                    {{ $item->nama }} | {{ $item->sdate }} s.d {{ $item->edate }} | Asal Dana :
                                    {{ $item->asaldana }}
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <button type="button" class="btn btn-sm btn-danger hapus-pengaturan-program">hapus</button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            431.f3. Jaminan Ketenagakerjaan <br><b>(BPJS Jaminan Hari Tua)</b>
                        </td>
                        <td>
                            <select name="431f3" class="form-control input-sm select2" style="width:100%;">
                                <option disabled selected value="">----- Pilih -----</option>
                                <?php foreach($daftar_bantuan_anggota as $item): ?>
                                <option value="<?= $item->id ?>" @selected($item->id . '' === ($name_431f3 . '' ?? ''))>
                                    {{ $item->nama }} | {{ $item->sdate }} s.d {{ $item->edate }} | Asal Dana :
                                    {{ $item->asaldana }}
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <button type="button" class="btn btn-sm btn-danger hapus-pengaturan-program">hapus</button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            431.f4. Jaminan Ketenagakerjaan <br><b>(BPJS Jaminan Pensiun)</b>
                        </td>
                        <td>
                            <select name="431f4" class="form-control input-sm select2" style="width:100%;">
                                <option disabled selected value="">----- Pilih -----</option>
                                <?php foreach($daftar_bantuan_anggota as $item): ?>
                                <option value="<?= $item->id ?>" @selected($item->id . '' === ($name_431f4 . '' ?? ''))>
                                    {{ $item->nama }} | {{ $item->sdate }} s.d {{ $item->edate }} | Asal Dana :
                                    {{ $item->asaldana }}
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <button type="button" class="btn btn-sm btn-danger hapus-pengaturan-program">hapus</button>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            431.f5. Jaminan Ketenagakerjaan <br><b>(Pensiun/Jaminan hari tua lainnya [Taspen/Program Pensiun
                                Swasta])</b>
                        </td>
                        <td>
                            <select name="431f5" class="form-control input-sm select2" style="width:100%;">
                                <option disabled selected value="">----- Pilih -----</option>
                                <?php foreach($daftar_bantuan_anggota as $item): ?>
                                <option value="<?= $item->id ?>" @selected($item->id . '' === ($name_431f5 . '' ?? ''))>
                                    {{ $item->nama }} | {{ $item->sdate }} s.d {{ $item->edate }} | Asal Dana :
                                    {{ $item->asaldana }}
                                </option>
                                <?php endforeach; ?>
                            </select>
                            <button type="button" class="btn btn-sm btn-danger hapus-pengaturan-program">hapus</button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <button type="submit" class="btn btn-social btn-info btn-sm pull-right" style="position:absolute;z-index:1;right:0;bottom:-44px;">
                <i class="fa fa-check"></i> Simpan
            </button>
        </div>
        </form>
    </div>
</div>

@include('admin.layouts.components.ajax_dtks')
<link rel="stylesheet" href="{{ asset('js/sweetalert2/sweetalert2.min.css') }}">
<script src="{{ asset('js/sweetalert2/sweetalert2.all.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2();
        $(".hapus-pengaturan-program").each(function(index, el) {
            let select = $(el).parent().find('select');
            $(el).on('click', function() {
                select.val(null).trigger('change');
            });
            $(select).on('change', function() {
                if (select.val() == null) {
                    $(el).hide();
                } else {
                    $(el).show();
                }
            });
            if (select.val() == null) {
                $(el).hide();
            } else {
                $(el).show();
            }
        });
        $.each($("#info_versi_dtks .tab-pane"), function(index, val) {
            var id = $(val).attr('id');
            if (index == 0) {
                $(`#nav-${id}`).trigger("click");
            }
        });

        $('.form-program').on('submit', function(ev) {
            ev.preventDefault();

            let id = $(this).attr('id');
            let form = $('#' + id).serializeArray();
            // $('#' + id + ' select').each(function(index, el) {
            //     form.push({
            //         'name': $(el).attr('name'),
            //         'value': $(el).val()
            //     });
            // });
            ajax_save_dtks("{{ ci_route('dtks.savePengaturan') . '/' . 2 }}", form);
        });
    });
</script>
