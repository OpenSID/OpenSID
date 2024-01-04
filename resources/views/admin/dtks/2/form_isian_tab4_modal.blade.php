<div class="modal fade" id="modal-tab4" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header btn-warning">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">IV. KETERANGAN SOSIAL EKONOMI ANGGOTA RUMAH TANGGA</h4>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <span id="title_art">Anggota Keluarga : -</span>
                </div>
                <div class="form-group">
                    <label>Kelompok Pertanyaan</label>
                    <select class="form-control input-sm" id="tab4_kelompok_pertanyaan" style="width:100%;">
                        <option disabled selected value="0">-- Pilih --</option>
                        <option value="tabel_ket_demografi">Keterangan Demografi</option>
                        <option value="tabel_pendidikan">Pendidikan (Untuk Anggota Keluarga 5 Tahun ke Atas)</option>
                        <option value="tabel_ketenagakerjaan">Ketenagakerjaan (Untuk Anggota Keluarga 5 Tahun ke Atas)
                        </option>
                        <option value="tabel_kepemilikan_usaha">Kepemilikan Usaha (Untuk Anggota Keluarga 5 Tahun ke
                            Atas)</option>
                        <option value="tabel_kesehatan">Kesehatan</option>
                        <option value="tabel_program_perlindungan_sosial">Program Perlindungan Sosial</option>
                    </select>
                    <hr>
                    <div class="table-responsive" id="tabel_ket_demografi">
                        {!! form_open(ci_route('dtks.save') . '/' . $dtks->id, 'class="form-4 form-validasi" id="form-4-demografi"') !!}
                        <input type="hidden" name='tipe_save' value='bagian4_demografi'>
                        <input type="hidden" name='id_art' value=''>
                        <table class="table table-bordered table-hover" style="margin-bottom: 0px;">
                            <thead class="bg-gray disabled color-palette">
                                <tr>
                                    <td width="50%"></td>
                                    <td>Pilihan</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr id="tr_4_404">
                                    <td>404. Keterangan keberadaan anggota keluarga</td>
                                    <td>
                                        @include('admin.layouts.components.select_pilihan_dtks', [
                                            'class' => 'select2',
                                            'attribut' => 'disabled id="pilihan_4_404" name="pilihan[4][404]"',
                                            'pilihan' => $pilihan4['404'],
                                        ])
                                    </td>
                                </tr>
                                <tr id="tr_4_405">
                                    <td>405. Jenis Kelamin</td>
                                    <td>
                                        @include('admin.layouts.components.select_pilihan_dtks', [
                                            'class' => 'select2',
                                            'attribut' => 'disabled id="pilihan_4_405" name="pilihan[4][405]"',
                                            'pilihan' => $pilihan4['405'],
                                        ])
                                    </td>
                                </tr>
                                <tr id="tr_4_406">
                                    <td>406. Tanggal Lahir</td>
                                    <td>
                                        <input class="form-control input-sm" disabled name="input[4][406]" id="input_4_406" type="date" value="" />
                                    </td>
                                </tr>
                                <tr id="tr_4_407">
                                    <td>407. Umur (Tahun)</td>
                                    <td>
                                        <input class="form-control input-sm" disabled name="input[4][407]" id="input_4_407" type="text" value="" />
                                    </td>
                                </tr>
                                <tr id="tr_4_408">
                                    <td>408. Status perkawinan</td>
                                    <td>
                                        @include('admin.layouts.components.select_pilihan_dtks', [
                                            'class' => 'select2',
                                            'attribut' => 'disabled id="pilihan_4_408" name="pilihan[4][408]"',
                                            'pilihan' => $pilihan4['408'],
                                        ])
                                    </td>
                                </tr>
                                <tr id="tr_4_409">
                                    <td>409. Status Hubungan dengan Kepala Keluarga</td>
                                    <td>
                                        @include('admin.layouts.components.select_pilihan_dtks', [
                                            'class' => 'select2',
                                            'attribut' => 'id="pilihan_4_409" name="pilihan[4][409]"',
                                            'pilihan' => $pilihan4['409'],
                                        ])
                                    </td>
                                </tr>
                                <tr id="tr_4_410">
                                    <td>410. Apakah saat ini (nama) sedang hamil (Untuk Wanita Usia 10-54 Tahun)</td>
                                    <td>
                                        @include('admin.layouts.components.select_pilihan_dtks', [
                                            'class' => 'select2',
                                            'attribut' => 'disabled id="pilihan_4_410" name="pilihan[4][410]"',
                                            'pilihan' => $pilihan4['410'],
                                        ])
                                    </td>
                                </tr>
                                <tr id="tr_4_411">
                                    <td>411. Apakah (nama) memiliki kartu Identitas (*dapat dipilih lebih dari 1)</td>
                                    <td>
                                        @include('admin.layouts.components.select_pilihan_dtks', [
                                            'class' => 'select2',
                                            'attribut' => 'disabled multiple id="pilihan_4_411" name="pilihan[4][411]"',
                                            'pilihan' => $pilihan4['411'],
                                        ])
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="col-sm-12" style="margin-top:15px">
                            <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i>
                                Simpan</button>
                        </div>
                        </form>
                    </div>
                    <div class="table-responsive" id="tabel_pendidikan">
                        {!! form_open(ci_route('dtks.save') . '/' . $dtks->id, 'class="form-4 form-validasi" id="form-4-pendidikan"') !!}
                        <input type="hidden" name='tipe_save' value='bagian4_pendidikan'>
                        <input type="hidden" name='id_art' value=''>
                        <table class="table table-bordered table-hover" style="margin-bottom: 0px;">
                            <thead class="bg-gray disabled color-palette">
                                <tr>
                                    <td width="50%"></td>
                                    <td>Pilihan</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Data Pendidikan anggota keluarga saat ini di <?= config_item('nama_aplikasi') ?>
                                    </td>
                                    <td><span class="pendidikan_saat_ini"></span></td>
                                </tr>
                                <tr>
                                    <td>Data Pendidikan KK anggota keluarga saat ini di
                                        <?= config_item('nama_aplikasi') ?></td>
                                    <td><span class="pendidikan_kk_saat_ini"></span></td>
                                </tr>
                                <tr id="tr_4_412">
                                    <td>412. Partisipasi sekolah</td>
                                    <td>
                                        @include('admin.layouts.components.select_pilihan_dtks', [
                                            'class' => 'select2',
                                            'attribut' => 'id="pilihan_4_412" name="pilihan[4][412]"',
                                            'pilihan' => $pilihan4['412'],
                                        ])
                                    </td>
                                </tr>
                                <tr id="tr_4_413">
                                    <td>413. Jenjang dan jenis pendidikan tertinggi yang pernah/sedang diduduki</td>
                                    <td>
                                        @include('admin.layouts.components.select_pilihan_dtks', [
                                            'class' => 'select2',
                                            'attribut' => 'id="pilihan_4_413" name="pilihan[4][413]"',
                                            'pilihan' => $pilihan4['413'],
                                        ])
                                    </td>
                                </tr>
                                <tr id="tr_4_414">
                                    <td>414. Kelas tertinggi yang pernah/sedang diduduki </td>
                                    <td>
                                        @include('admin.layouts.components.select_pilihan_dtks', [
                                            'class' => 'select2',
                                            'attribut' => 'id="pilihan_4_414" name="pilihan[4][414]"',
                                            'pilihan' => $pilihan4['414'],
                                        ])
                                    </td>
                                </tr>
                                <tr id="tr_4_415">
                                    <td>415. Ijazah tertinggi yang dimiliki</td>
                                    <td>
                                        @include('admin.layouts.components.select_pilihan_dtks', [
                                            'class' => 'select2',
                                            'attribut' => 'id="pilihan_4_415" name="pilihan[4][415]"',
                                            'pilihan' => $pilihan4['415'],
                                        ])
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="col-sm-12" style="margin-top:15px">
                            <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i>
                                Simpan</button>
                        </div>
                        </form>
                    </div>
                    <div class="table-responsive" id="tabel_ketenagakerjaan">
                        {!! form_open(ci_route('dtks.save') . '/' . $dtks->id, 'class="form-4 form-validasi" id="form-4-ketenagakerjaan"') !!}
                        <input type="hidden" name='tipe_save' value='bagian4_ketenagakerjaan'>
                        <input type="hidden" name='id_art' value=''>
                        <table class="table table-bordered table-hover" style="margin-bottom: 0px;">
                            <thead class="bg-gray disabled color-palette">
                                <tr>
                                    <td width="50%"></td>
                                    <td>Pilihan</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Data Pekerjaan anggota keluarga saat ini di <?= config_item('nama_aplikasi') ?>
                                    </td>
                                    <td><span class="pekerjaan_saat_ini"></span></td>
                                </tr>
                                <tr id="tr_4_416a">
                                    <td>416.a. Apakah (nama) bekerja/membantu bekerja selama seminggu yang lalu?</td>
                                    <td>
                                        @include('admin.layouts.components.select_pilihan_dtks', [
                                            'class' => 'select2',
                                            'attribut' => 'id="pilihan_4_416a" name="pilihan[4][416a]"',
                                            'pilihan' => $pilihan4['416a'],
                                        ])
                                    </td>
                                </tr>
                                <tr id="tr_4_416b">
                                    <td>416.b. Berapa Jam (nama) bekerja/membantu bekerja selama seminggu yang lalu</td>
                                    <td>
                                        <input maxlength="2" class="form-control input-sm angka" name="input[4][416b]" id="input_4_416b" type="text" value="" />
                                    </td>
                                </tr>
                                <tr id="tr_4_417">
                                    <td>417. Lapangan usaha dari pekerjaan utama (Kode diisi oleh PML)</td>
                                    <td>
                                        @include('admin.layouts.components.select_pilihan_dtks', [
                                            'class' => 'select2',
                                            'attribut' => 'id="pilihan_4_417" name="pilihan[4][417]"',
                                            'pilihan' => $pilihan4['417'],
                                        ])
                                    </td>
                                </tr>
                                <tr id="tr_4_lapangan_usaha_pekerjaan">
                                    <td>Tulis Selengkap-lengkapnya lapangan usaha dari pekerjaan utama</td>
                                    <td>
                                        <input class="form-control input-sm nama" name="input[4][lapangan_usaha_pekerjaan]" id="input_4_lapangan_usaha_pekerjaan" type="text" value="" />
                                    </td>
                                </tr>
                                <tr id="tr_4_418">
                                    <td>418. Status kedudukan (nama) dalam pekerjaan utama</td>
                                    <td>
                                        @include('admin.layouts.components.select_pilihan_dtks', [
                                            'class' => 'select2',
                                            'attribut' => 'id="pilihan_4_418" name="pilihan[4][418]"',
                                            'pilihan' => $pilihan4['418'],
                                        ])
                                    </td>
                                </tr>
                                <tr id="tr_4_419">
                                    <td>419. Apakah (nama) memiliki NPWP?</td>
                                    <td>
                                        @include('admin.layouts.components.select_pilihan_dtks', [
                                            'class' => 'select2',
                                            'attribut' => 'id="pilihan_4_419" name="pilihan[4][419]"',
                                            'pilihan' => $pilihan4['419'],
                                        ])
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                        <div class="col-sm-12" style="margin-top:15px">
                            <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i>
                                Simpan</button>
                        </div>
                        </form>
                    </div>
                    <div class="table-responsive" id="tabel_kepemilikan_usaha">
                        {!! form_open(ci_route('dtks.save') . '/' . $dtks->id, 'class="form-4 form-validasi" id="form-4-kepemilikan-usaha"') !!}
                        <input type="hidden" name='tipe_save' value='bagian4_kepemilikan_usaha'>
                        <input type="hidden" name='id_art' value=''>
                        <table class="table table-bordered table-hover" style="margin-bottom: 0px;">
                            <thead class="bg-gray disabled color-palette">
                                <tr>
                                    <td width="50%"></td>
                                    <td>Pilihan</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Data Pekerjaan anggota keluarga saat ini di <?= config_item('nama_aplikasi') ?>
                                    </td>
                                    <td><span class="pekerjaan_saat_ini"></span></td>
                                </tr>
                                <tr id="tr_4_420a">
                                    <td>420a. Apakah (nama) memiliki usaha sendiri/bersama?</td>
                                    <td>
                                        @include('admin.layouts.components.select_pilihan_dtks', [
                                            'class' => 'select2',
                                            'attribut' => 'id="pilihan_4_420a" name="pilihan[4][420a]"',
                                            'pilihan' => $pilihan4['420a'],
                                        ])
                                    </td>
                                </tr>
                                <tr id="tr_4_420b">
                                    <td>420b. Berapa jumlah usaha sendiri/bersama yang dimiliki?</td>
                                    <td>
                                        <input maxlength="2" class="form-control input-sm angka" name="input[4][420b]" id="input_4_420b" type="text" value="" />
                                    </td>
                                </tr>
                                <tr id="tr_4_421">
                                    <td>421. Apakah lapangan usaha dari usaha utama (Kode diisi oleh PML)</td>
                                    <td>
                                        @include('admin.layouts.components.select_pilihan_dtks', [
                                            'class' => 'select2',
                                            'attribut' => 'id="pilihan_4_421" name="pilihan[4][421]"',
                                            'pilihan' => $pilihan4['421'],
                                        ])
                                    </td>
                                </tr>
                                <tr id="tr_4_lapangan_usaha_dr_usaha">
                                    <td>Tulis Selengkap-lengkapnya lapangan usaha dari usaha utama</td>
                                    <td>
                                        <input class="form-control input-sm nama" name="input[4][lapangan_usaha_dr_usaha]" id="input_4_lapangan_usaha_dr_usaha" type="text" value="" />
                                    </td>
                                </tr>
                                <tr id="tr_4_422">
                                    <td>422. Jumlah pekerja yang dibayar pada usaha utama</td>
                                    <td>
                                        <input maxlength="3" class="form-control input-sm angka" name="input[4][422]" id="input_4_422" type="text" value="" />
                                    </td>
                                </tr>
                                <tr id="tr_4_423">
                                    <td>423. Jumlah pekerja yang tidak dibayar pada usaha utama</td>
                                    <td>
                                        <input maxlength="2" class="form-control input-sm angka" name="input[4][423]" id="input_4_423" type="text" value="" />
                                    </td>
                                </tr>
                                <tr id="tr_4_424">
                                    <td>424. Kepemilikan perijinan usaha utama</td>
                                    <td>
                                        @include('admin.layouts.components.select_pilihan_dtks', [
                                            'class' => 'select2',
                                            'attribut' => 'id="pilihan_4_424" name="pilihan[4][424]"',
                                            'pilihan' => $pilihan4['424'],
                                        ])
                                    </td>
                                </tr>
                                <tr id="tr_4_425">
                                    <td>425. Omset usaha utama perbulan (Rupiah)</td>
                                    <td>
                                        @include('admin.layouts.components.select_pilihan_dtks', [
                                            'class' => 'select2',
                                            'attribut' => 'id="pilihan_4_425" name="pilihan[4][425]"',
                                            'pilihan' => $pilihan4['425'],
                                        ])
                                    </td>
                                </tr>
                                <tr id="tr_4_426">
                                    <td>426. Penggunaan internet dalam kegiatan usaha utama (*dapat dipilih lebih dari
                                        1)</td>
                                    <td>
                                        @include('admin.layouts.components.select_pilihan_dtks', [
                                            'class' => 'select2',
                                            'attribut' => 'multiple id="pilihan_4_426" name="pilihan[4][426]"',
                                            'pilihan' => $pilihan4['426'],
                                        ])
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="col-sm-12" style="margin-top:15px">
                            <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i>
                                Simpan</button>
                        </div>
                        </form>
                    </div>
                    <div class="table-responsive" id="tabel_kesehatan">
                        {!! form_open(ci_route('dtks.save') . '/' . $dtks->id, 'class="form-4 form-validasi" id="form-4-kesehatan"') !!}
                        <input type="hidden" name='tipe_save' value='bagian4_kesehatan'>
                        <input type="hidden" name='id_art' value=''>
                        <b>Sekali pilih untuk 428.a. sampai 428.i.</b>
                        <div style="display: inline-flex">
                            @include('admin.layouts.components.select_pilihan_dtks', [
                                'class' => 'select2',
                                'attribut' => 'id="pilihan_4_428a_428i"',
                                'pilihan' => $pilihan4['428a'],
                            ])
                        </div>
                        <table class="table table-bordered table-hover" style="margin-bottom: 0px;">
                            <thead class="bg-gray disabled color-palette">
                                <tr>
                                    <td width="50%"></td>
                                    <td>Pilihan</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr id="tr_4_427">
                                    <td>427. Bagaimana kondisi anak dari pemeriksaan 3 bulan terakhir di
                                        posyandu/puskesmas/rumah sakit dengan mengacu pada catatan/buku kontrol?</td>
                                    <td>
                                        @include('admin.layouts.components.select_pilihan_dtks', [
                                            'class' => 'select2',
                                            'attribut' => 'id="pilihan_4_427" name="pilihan[4][427]"',
                                            'pilihan' => $pilihan4['427'],
                                        ])
                                    </td>
                                </tr>
                                <tr id="tr_4_428a">
                                    <td>428.a. Apakah (nama) mengalami Kesulitan/Gangguan Penglihatan meskipun
                                        menggunakan alat bantu melihat?</td>
                                    <td>
                                        @include('admin.layouts.components.select_pilihan_dtks', [
                                            'class' => 'select2',
                                            'attribut' => 'id="pilihan_4_428a" name="pilihan[4][428a]"',
                                            'pilihan' => $pilihan4['428a'],
                                        ])
                                    </td>
                                </tr>
                                <tr id="tr_4_428b">
                                    <td>428.b. Apakah (nama) mengalami Kesulitan/Gangguan Pendengaran meskipun
                                        menggunakan alat bantu mendengar?</td>
                                    <td>
                                        @include('admin.layouts.components.select_pilihan_dtks', [
                                            'class' => 'select2',
                                            'attribut' => 'id="pilihan_4_428b" name="pilihan[4][428b]"',
                                            'pilihan' => $pilihan4['428b'],
                                        ])
                                    </td>
                                </tr>
                                <tr id="tr_4_428c">
                                    <td>428.c. Apakah (nama) mengalami Kesulitan/Gangguan Berjalan atau Naik Tangga?
                                    </td>
                                    <td>
                                        @include('admin.layouts.components.select_pilihan_dtks', [
                                            'class' => 'select2',
                                            'attribut' => 'id="pilihan_4_428c" name="pilihan[4][428c]"',
                                            'pilihan' => $pilihan4['428c'],
                                        ])
                                    </td>
                                </tr>
                                <tr id="tr_4_428d">
                                    <td>428.d. Apakah (nama) mengalami Kesulitan/Gangguan menggerakan/menggunakan
                                        Tangan/Jari?</td>
                                    <td>
                                        @include('admin.layouts.components.select_pilihan_dtks', [
                                            'class' => 'select2',
                                            'attribut' => 'id="pilihan_4_428d" name="pilihan[4][428d]"',
                                            'pilihan' => $pilihan4['428d'],
                                        ])
                                    </td>
                                </tr>
                                <tr id="tr_4_428e">
                                    <td>428.e. Dibandingkan dengan penduduk yang sebaya, apakah (nama) mengalami
                                        Kesulitan/Gangguan Belajar atau Kemampuan Intelektual?</td>
                                    <td>
                                        @include('admin.layouts.components.select_pilihan_dtks', [
                                            'class' => 'select2',
                                            'attribut' => 'id="pilihan_4_428e" name="pilihan[4][428e]"',
                                            'pilihan' => $pilihan4['428e'],
                                        ])
                                    </td>
                                </tr>
                                <tr id="tr_4_428f">
                                    <td>428.f. Dibandingkan dengan penduduk yang sebaya, apakah (nama) mengalami
                                        Kesulitan/Gangguan megnendalikan Perilaku?</td>
                                    <td>
                                        @include('admin.layouts.components.select_pilihan_dtks', [
                                            'class' => 'select2',
                                            'attribut' => 'id="pilihan_4_428f" name="pilihan[4][428f]"',
                                            'pilihan' => $pilihan4['428f'],
                                        ])
                                    </td>
                                </tr>
                                <tr id="tr_4_428g">
                                    <td>428.g. Apakah (nama) mengalami Kesulitan/Gangguan Berbicara/Berkomunikasi?</td>
                                    <td>
                                        @include('admin.layouts.components.select_pilihan_dtks', [
                                            'class' => 'select2',
                                            'attribut' => 'id="pilihan_4_428g" name="pilihan[4][428g]"',
                                            'pilihan' => $pilihan4['428g'],
                                        ])
                                    </td>
                                </tr>
                                <tr id="tr_4_428h">
                                    <td>428.h. Apakah (nama) Mengalami Kesulitan/Gangguan untuk Mengurus Diri Sendiri?
                                        (seperti mandi, makan, berpakaian, buang air kecil, buang air besar)?</td>
                                    <td>
                                        @include('admin.layouts.components.select_pilihan_dtks', [
                                            'class' => 'select2',
                                            'attribut' => 'id="pilihan_4_428h" name="pilihan[4][428h]"',
                                            'pilihan' => $pilihan4['428h'],
                                        ])
                                    </td>
                                </tr>
                                <tr id="tr_4_428i">
                                    <td>428.i. Apakah (nama) mengalami Kesulitan/Gangguan Mengingat/Berkonsentrasi?</td>
                                    <td>
                                        @include('admin.layouts.components.select_pilihan_dtks', [
                                            'class' => 'select2',
                                            'attribut' => 'id="pilihan_4_428i" name="pilihan[4][428i]"',
                                            'pilihan' => $pilihan4['428i'],
                                        ])
                                    </td>
                                </tr>
                                <tr id="tr_4_428j">
                                    <td>428.j. Seberapa sering (nama) mengalami gangguan kesedihan depresi?</td>
                                    <td>
                                        @include('admin.layouts.components.select_pilihan_dtks', [
                                            'class' => 'select2',
                                            'attribut' => 'id="pilihan_4_428j" name="pilihan[4][428j]"',
                                            'pilihan' => $pilihan4['428j'],
                                        ])
                                    </td>
                                </tr>
                                <tr id="tr_4_429">
                                    <td>429. Apakah memiliki caregiver/pemberi rawat/pengasuh/wali?</td>
                                    <td>
                                        @include('admin.layouts.components.select_pilihan_dtks', [
                                            'class' => 'select2',
                                            'attribut' => 'id="pilihan_4_429" name="pilihan[4][429]"',
                                            'pilihan' => $pilihan4['429'],
                                        ])
                                    </td>
                                </tr>
                                <tr id="tr_4_430">
                                    <td>430. Apakah (nama) memiliki keluhan kesehatan kronis/menahun?</td>
                                    <td>
                                        @include('admin.layouts.components.select_pilihan_dtks', [
                                            'class' => 'select2',
                                            'attribut' => 'id="pilihan_4_430" name="pilihan[4][430]"',
                                            'pilihan' => $pilihan4['430'],
                                        ])
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="col-sm-12" style="margin-top:15px">
                            <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i>
                                Simpan</button>
                        </div>
                        </form>
                    </div>
                    <div class="table-responsive" id="tabel_program_perlindungan_sosial">
                        {!! form_open(ci_route('dtks.save') . '/' . $dtks->id, 'class="form-4 form-validasi" id="form-4-program-perlindungan-sosial"') !!}
                        <input type="hidden" name='tipe_save' value='bagian4_program_perlindungan_sosial'>
                        <input type="hidden" name='id_art' value=''>
                        <table class="table table-bordered table-hover" style="margin-bottom: 0px;">
                            <thead class="bg-gray disabled color-palette">
                                <tr>
                                    <td width="50%"></td>
                                    <td>Pilihan</td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr id="tr_4_431a">
                                    <td>431a. Dalam satu tahun terakhir, apakah (Nama) memiliki jaminan kesehatan?
                                        (*dapat dipilih lebih dari 1)</td>
                                    <td>
                                        @include('admin.layouts.components.select_pilihan_dtks', [
                                            'class' => 'select2',
                                            'attribut' => 'multiple id="pilihan_4_431a" name="pilihan[4][431a]"',
                                            'pilihan' => $pilihan4['431a'],
                                        ])
                                    </td>
                                </tr>
                                <tr id="tr_4_431b">
                                    <td>431b. Dalam satu tahun terakhir, apakah (Nama) ikut serta dalam Program
                                        Pra-Kerja?</td>
                                    <td>
                                        @include('admin.layouts.components.select_pilihan_dtks', [
                                            'class' => 'select2',
                                            'attribut' => 'id="pilihan_4_431b" name="pilihan[4][431b]"',
                                            'pilihan' => $pilihan4['431b'],
                                        ])
                                    </td>
                                </tr>
                                <tr id="tr_4_431c">
                                    <td>431c. Dalam satu tahun terakhir, apakah (Nama) ikut serta dalam Program Kredit
                                        Usaha Rakyat (KUR)?</td>
                                    <td>
                                        @include('admin.layouts.components.select_pilihan_dtks', [
                                            'class' => 'select2',
                                            'attribut' => 'id="pilihan_4_431c" name="pilihan[4][431c]"',
                                            'pilihan' => $pilihan4['431c'],
                                        ])
                                    </td>
                                </tr>
                                <tr id="tr_4_431d">
                                    <td>431d. Dalam satu tahun terakhir, apakah (Nama) ikut serta dalam Program
                                        Pembiayaan Ultra Mikro (UM)?</td>
                                    <td>
                                        @include('admin.layouts.components.select_pilihan_dtks', [
                                            'class' => 'select2',
                                            'attribut' => 'id="pilihan_4_431d" name="pilihan[4][431d]"',
                                            'pilihan' => $pilihan4['431d'],
                                        ])
                                    </td>
                                </tr>
                                <tr id="tr_4_431e">
                                    <td>431e. Dalam satu tahun terakhir, apakah (Nama) ikut serta dalam Program
                                        Indonesia Pintar (PIP)?</td>
                                    <td>
                                        @include('admin.layouts.components.select_pilihan_dtks', [
                                            'class' => 'select2',
                                            'attribut' => 'id="pilihan_4_431e" name="pilihan[4][431e]"',
                                            'pilihan' => $pilihan4['431e'],
                                        ])
                                    </td>
                                </tr>
                                <tr id="tr_4_431f">
                                    <td>431e. Dalam satu tahun terakhir, apakah (Nama) memiliki jaminan ketenagakerjaan?
                                        (*dapat dipilih lebih dari 1)</td>
                                    <td>
                                        @include('admin.layouts.components.select_pilihan_dtks', [
                                            'class' => 'select2',
                                            'attribut' => 'multiple id="pilihan_4_431f" name="pilihan[4][431f]"',
                                            'pilihan' => $pilihan4['431f'],
                                        ])
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="col-sm-12" style="margin-top:15px">
                            <button type="submit" class="btn btn-social btn-info btn-sm pull-right"><i class="fa fa-check"></i>
                                Simpan</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            let default_hide = ['tabel_ket_demografi', 'tabel_pendidikan', 'tabel_ketenagakerjaan',
                'tabel_kepemilikan_usaha', 'tabel_kesehatan', 'tabel_program_perlindungan_sosial'
            ];
            default_hide.concat([]);
            default_hide.forEach(id => {
                $('#' + id).hide();
            });
            // -- ket. demografi
            $('#pilihan_4_404').on('change', function(ev) {
                show_when_otherwise_hide(['1', '5'].indexOf($('#pilihan_4_404').val()) > -1, ['tr_4_405',
                    'tr_4_406', 'tr_4_407', 'tr_4_408', 'tr_4_409', 'tr_4_410', 'tr_4_411'
                ], ['tr_4_405', 'tr_4_406', 'tr_4_407', 'tr_4_408', 'tr_4_409', 'tr_4_410',
                    'tr_4_411'
                ]);
                // status kehamilan perempuan umur 10 s.d 54
                show_when_otherwise_hide(global_umur_art >= 10 && global_umur_art <= 54 && ['2', '3', '4']
                    .indexOf($('#pilihan_4_408').val()) > -1 && ['2'].indexOf($('#pilihan_4_405')
                        .val()) > -1, ['tr_4_410'], ['tr_4_410']);
            });

            // -- sekolah/pendidikan
            $('#pilihan_4_412').on('change', function(ev) {
                show_when_otherwise_hide(global_umur_art >= 5 && ['2', '3'].indexOf($('#pilihan_4_412')
                    .val()) > -1, ['tr_4_413', 'tr_4_414', 'tr_4_415'], ['tr_4_428', 'tr_4_414',
                    'tr_4_415'
                ]);
            });

            // -- ketenagakerjaan
            // kerja seminggu lalu
            $('#pilihan_4_416a').on('change', function(ev) {
                show_when_otherwise_hide(global_umur_art >= 5 && ['1'].indexOf($('#pilihan_4_416a').val()) >
                    -1, ['tr_4_416b', 'tr_4_417', 'tr_4_lapangan_usaha_pekerjaan', 'tr_4_418'], [
                        'tr_4_416b', 'tr_4_417', 'tr_4_lapangan_usaha_pekerjaan', 'tr_4_418'
                    ]);
            });
            // -- kepemilikan usaha
            $('#pilihan_4_420a').on('change', function(ev) {
                show_when_otherwise_hide(global_umur_art >= 5 && ['1'].indexOf($('#pilihan_4_420a').val()) >
                    -1, ['tr_4_420b', 'tr_4_421', 'tr_4_lapangan_usaha_dr_usaha', 'tr_4_422',
                        'tr_4_423', 'tr_4_424', 'tr_4_425', 'tr_4_426'
                    ], ['tr_4_420b', 'tr_4_421', 'tr_4_lapangan_usaha_dr_usaha', 'tr_4_422', 'tr_4_423',
                        'tr_4_424', 'tr_4_425', 'tr_4_426'
                    ]);
            });
            // -- kesehatan 428.a - 428.j
            $('#pilihan_4_428a, #pilihan_4_428b, #pilihan_4_428c, #pilihan_4_428d, #pilihan_4_428e, #pilihan_4_428f, #pilihan_4_428g, #pilihan_4_428h, #pilihan_4_428i, #pilihan_4_428j')
                .on('change', function(ev) {
                    let ada_kode_1_atau_2 = false;
                    ['#pilihan_4_428a', '#pilihan_4_428b', '#pilihan_4_428c', '#pilihan_4_428d',
                        '#pilihan_4_428e', '#pilihan_4_428f', '#pilihan_4_428g', '#pilihan_4_428h',
                        '#pilihan_4_428i', '#pilihan_4_428j'
                    ]
                    .forEach(function(item) {
                        if (!ada_kode_1_atau_2) {
                            ada_kode_1_atau_2 = ['1', '2'].indexOf($(item).val()) > -1;
                        }
                    });
                    show_when_otherwise_hide(global_umur_art >= 60 || ada_kode_1_atau_2, ['tr_4_429'], [
                        'tr_4_429'
                    ]);
                });

            $('#pilihan_4_428a_428i').on('change', function(ev) {
                $('#pilihan_4_428a, #pilihan_4_428b, #pilihan_4_428c, #pilihan_4_428d, #pilihan_4_428e, #pilihan_4_428f, #pilihan_4_428g, #pilihan_4_428h, #pilihan_4_428i')
                    .val(ev.currentTarget.value).trigger('change');
            });

            // -- program perlindungan sosial

            $('.form-4').on('submit', function(ev) {
                ev.preventDefault();
                let is_valid = is_form_valid($(this).attr('id'));
                if (!is_valid) {
                    return false;
                }

                let id = $(this).attr('id');
                let form = $('#' + id).serializeArray();
                $('#' + id + ' select').each(function(index, el) {
                    form.push({
                        'name': $(el).attr('name'),
                        'value': $(el).val()
                    });
                });
                let callback_success = function(data) {
                    let index_anggota = global_data_anggota.findIndex(function(item) {
                        return item.id == data.new_data.id;
                    });
                    // combine data
                    global_data_anggota[index_anggota] = {
                        ...global_data_anggota[index_anggota],
                        ...data.new_data
                    };
                    setJumlahTerisi(index_anggota);
                };
                ajax_save_dtks("{{ ci_route('dtks.save') . '/' . $dtks->id }}", form, callback_success);
            });
        });
    </script>
@endpush
