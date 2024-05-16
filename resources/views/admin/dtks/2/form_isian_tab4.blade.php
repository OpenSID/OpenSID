<div class="row">
    <div class="col-sm-12">
        <div class="form-group">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" style="margin-bottom: 0px;" id="tabel_art_dtks">
                    <thead class="bg-gray disabled color-palette">
                        <tr>
                            <th>Anggota Keluarga</th>
                            <th class="padat">Aksi</th>
                            <th class="padat">Keterangan <br>Demografi</th>
                            <th class="padat">Pendidikan</th>
                            <th class="padat">Ketenagakerjaan</th>
                            <th class="padat">Kepemilikan <br>Usaha</th>
                            <th class="padat">Kesehatan</th>
                            <th class="padat">Program <br>Perlindungan <br>Sosial</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dtks->dtksAnggota as $agt)
                            <tr data-id="{{ $agt->id }}">
                                <td>
                                    {{ $agt->nama }}
                                    <br>
                                    {{ $agt->nik }}
                                </td>
                                <td>
                                    <a href="#" data-remote="false" data-toggle="modal" data-target="#modal-tab4" class="modal-table btn bg-navy btn-sm visible-xs-block visible-sm-block visible-md-block visible-lg-block">
                                        Lihat
                                    </a>
                                </td>
                                <td>
                                    <a href="#" data-table="tabel_ket_demografi" data-remote="false" data-toggle="modal" data-target="#modal-tab4" class="modal-table btn btn-sm visible-xs-block visible-sm-block visible-md-block visible-lg-block">
                                        Terisi 10 / 10
                                    </a>
                                </td>
                                <td>
                                    <a href="#" data-table="tabel_pendidikan" data-remote="false" data-toggle="modal" data-target="#modal-tab4" class="modal-table btn btn-sm visible-xs-block visible-sm-block visible-md-block visible-lg-block">
                                        Terisi 4 / 4
                                    </a>
                                </td>
                                <td>
                                    <a href="#" data-table="tabel_ketenagakerjaan" data-remote="false" data-toggle="modal" data-target="#modal-tab4" class="modal-table btn btn-sm visible-xs-block visible-sm-block visible-md-block visible-lg-block">
                                        Terisi 6 / 6
                                    </a>
                                </td>
                                <td>
                                    <a href="#" data-table="tabel_kepemilikan_usaha" data-remote="false" data-toggle="modal" data-target="#modal-tab4" class="modal-table btn btn-sm visible-xs-block visible-sm-block visible-md-block visible-lg-block">
                                        Terisi 9 / 9
                                    </a>
                                </td>
                                <td>
                                    <a href="#" data-table="tabel_kesehatan" data-remote="false" data-toggle="modal" data-target="#modal-tab4" class="modal-table btn btn-sm visible-xs-block visible-sm-block visible-md-block visible-lg-block">
                                        Terisi 28 / 28
                                    </a>
                                </td>
                                <td>
                                    <a href="#" data-table="tabel_program_perlindungan_sosial" data-remote="false" data-toggle="modal" data-target="#modal-tab4" class="modal-table btn btn-sm visible-xs-block visible-sm-block visible-md-block visible-lg-block">
                                        Terisi 6 / 6
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <hr class="col-sm-12">
    <div class="col-sm-12 text-center">
        <button type="button" onclick="$(`#nav-bagian-3`).trigger('click')" class="btn btn-social btn-default btn-sm"><i class='fa fa-arrow-left'></i> Sebelumnya</button>
        <button type="button" onclick="$(`#nav-bagian-5`).trigger('click')" class="btn btn-social btn-default btn-sm">Selanjutnya <i class="fa fa-arrow-right"></i></button>
    </div>
</div>

@push('scripts')
    {{-- letakkan modal di paling bawah/tag nya tidak terlalu dalam, biar scroll berjalan normal --}}
    @include('admin.dtks.2.form_isian_tab4_modal')
    <script>
        var global_is_draft = '{{ $dtks->is_draft }}';
        var global_umur_art = '';
        var global_table_art_dtsk = [{
                id: 'tabel_ket_demografi',
                title_select: 'Keterangan Demografi'
            },
            {
                id: 'tabel_pendidikan',
                title_select: 'Pendidikan (Untuk Anggota Keluarga 5 Tahun ke Atas)'
            },
            {
                id: 'tabel_ketenagakerjaan',
                title_select: 'Ketenagakerjaan (Untuk Anggota Keluarga 5 Tahun ke Atas)'
            },
            {
                id: 'tabel_kepemilikan_usaha',
                title_select: 'Kepemilikan Usaha (Untuk Anggota Keluarga 5 Tahun ke Atas)'
            },
            {
                id: 'tabel_kesehatan',
                title_select: 'Kesehatan'
            },
            {
                id: 'tabel_program_perlindungan_sosial',
                title_select: 'Program Perlindungan Sosial'
            },
        ];;
        var global_art_jumlah_terisi = null;
        var global_data_anggota = {!! json_encode($dtks->dtksAnggota) !!};

        function refresh_jumlah_dan_terisi(id_anggota) {
            $('#tabel_art_dtks tr[data-id="' + id_anggota + '"] a.modal-table').each(function(index, el) {
                let indexTR = global_table_art_dtsk.findIndex(function(item) {
                    return item.id == $(el).data('table');
                });
                if (indexTR != -1) {
                    $(el).text(`Terisi ${global_art_jumlah_terisi[indexTR].terisi} / ${global_art_jumlah_terisi[indexTR].jumlah}`);
                    if (global_art_jumlah_terisi[indexTR].jumlah - global_art_jumlah_terisi[indexTR].terisi > 0) {
                        $(el).addClass('bg-orange');
                    } else {
                        $(el).removeClass('bg-orange');
                    }
                }
            });
        }

        function getAge(dateString) {
            var today = new Date();
            var birthDate = new Date(dateString);
            var age = today.getFullYear() - birthDate.getFullYear();
            var m = today.getMonth() - birthDate.getMonth();
            if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            return age;
        }

        function tentukanJumlahTerpilih(list_kode, value) {
            let index = 0;
            let kode_terpilih = [];

            if (value == 0) {
                return 0;
            }

            while (value > 0 || index >= list_kode.length) {
                if (value - list_kode[index] >= 0) {
                    value -= list_kode[index];
                    kode_terpilih.push(list_kode[index]);
                }
                index++;
            }
            return kode_terpilih;
        }

        function setDefaultValue(index_anggota) {
            let tmp_jumlah, list_kode, index, kode_terpilih;
            global_umur_art = global_data_anggota[index_anggota].umur;
            // ket demografi
            $('#title_art').text('Nama : ' + global_data_anggota[index_anggota].nama + ' | ' + global_data_anggota[index_anggota].nik + ' | ' + global_umur_art + ' Tahun');
            $('input[type=hidden][name=id_art]').val(global_data_anggota[index_anggota].id);

            $('#pilihan_4_404').val(global_data_anggota[index_anggota].kd_ket_keberadaan_art).trigger('change');
            $('#pilihan_4_405').val(global_data_anggota[index_anggota].kd_jenis_kelamin).trigger('change');
            $('#input_4_406').val(global_data_anggota[index_anggota].tgl_lahir);
            $('#input_4_407').val(global_data_anggota[index_anggota].umur);
            $('#pilihan_4_408').val(global_data_anggota[index_anggota].kd_stat_perkawinan).trigger('change');
            $('#pilihan_4_409').val(global_data_anggota[index_anggota].kd_hubungan_dg_kk).trigger('change');
            $('#pilihan_4_410').val(global_data_anggota[index_anggota].kd_status_kehamilan).trigger('change');
            $('#pilihan_4_411').val(tentukanJumlahTerpilih([4, 2, 1, 0], global_data_anggota[index_anggota].kd_punya_kartuid)).trigger('change');

            // pendidikan
            $('#pilihan_4_412').val(global_data_anggota[index_anggota].kd_partisipasi_sekolah).trigger('change');
            $('#pilihan_4_413').val(global_data_anggota[index_anggota].kd_pendidikan_tertinggi).trigger('change');
            $('#pilihan_4_414').val(global_data_anggota[index_anggota].kd_kelas_tertinggi).trigger('change');
            $('#pilihan_4_415').val(global_data_anggota[index_anggota].kd_ijazah_tertinggi).trigger('change');
            $('.pendidikan_saat_ini').text(global_data_anggota[index_anggota].pendidikan_saat_ini ?? '');
            $('.pendidikan_kk_saat_ini').text(global_data_anggota[index_anggota].pendidikan_kk_saat_ini ?? '');

            // ketenagakerjaan
            $('#pilihan_4_416a').val(global_data_anggota[index_anggota].kd_bekerja_seminggu_lalu).trigger('change');
            $('#input_4_416b').val(global_data_anggota[index_anggota].jumlah_jam_kerja_seminggu_lalu);
            $('#pilihan_4_417').val(global_data_anggota[index_anggota].kd_lapangan_usaha_pekerjaan).trigger('change');
            $('#input_4_lapangan_usaha_pekerjaan').val(global_data_anggota[index_anggota].tulis_lapangan_usaha_pekerjaan);
            $('#pilihan_4_418').val(global_data_anggota[index_anggota].kd_kedudukan_di_pekerjaan).trigger('change');
            $('#pilihan_4_419').val(global_data_anggota[index_anggota].kd_punya_npwp).trigger('change');
            $('.pekerjaan_saat_ini').text(global_data_anggota[index_anggota].pekerjaan_saat_ini ?? '');

            // kepemilikan usaha
            $('#pilihan_4_420a').val(global_data_anggota[index_anggota].kd_punya_usaha_sendiri_bersama).trigger('change');
            $('#input_4_420b').val(global_data_anggota[index_anggota].jumlah_usaha_sendiri_bersama);
            $('#pilihan_4_421').val(global_data_anggota[index_anggota].kd_lapangan_usaha_dr_usaha).trigger('change');
            $('#input_4_lapangan_usaha_dr_usaha').val(global_data_anggota[index_anggota].tulis_lapangan_usaha_dr_usaha).trigger('change');
            $('#input_4_422').val(global_data_anggota[index_anggota].jumlah_pekerja_dibayar).trigger('change');
            $('#input_4_423').val(global_data_anggota[index_anggota].jumlah_pekerja_tidak_dibayar).trigger('change');
            $('#pilihan_4_424').val(global_data_anggota[index_anggota].kd_kepemilikan_ijin_usaha).trigger('change');
            $('#pilihan_4_425').val(global_data_anggota[index_anggota].kd_omset_usaha_perbulan).trigger('change');
            $('#pilihan_4_426').val(tentukanJumlahTerpilih([32, 16, 8, 4, 2, 1, 0], global_data_anggota[index_anggota].kd_guna_internet_usaha)).trigger('change');

            // kesehatan
            $('#pilihan_4_427').val(global_data_anggota[index_anggota].kd_gizi_seimbang).trigger('change');
            $('#pilihan_4_428a').val(global_data_anggota[index_anggota].kd_sulit_penglihatan).trigger('change');
            $('#pilihan_4_428b').val(global_data_anggota[index_anggota].kd_sulit_pendengaran).trigger('change');
            $('#pilihan_4_428c').val(global_data_anggota[index_anggota].kd_sulit_jalan_naiktangga).trigger('change');
            $('#pilihan_4_428d').val(global_data_anggota[index_anggota].kd_sulit_gerak_tangan_jari).trigger('change');
            $('#pilihan_4_428e').val(global_data_anggota[index_anggota].kd_sulit_belajar_intelektual).trigger('change');
            $('#pilihan_4_428f').val(global_data_anggota[index_anggota].kd_sulit_perilaku_emosi).trigger('change');
            $('#pilihan_4_428g').val(global_data_anggota[index_anggota].kd_sulit_paham_bicara_kom).trigger('change');
            $('#pilihan_4_428h').val(global_data_anggota[index_anggota].kd_sulit_mandiri).trigger('change');
            $('#pilihan_4_428i').val(global_data_anggota[index_anggota].kd_sulit_ingat_konsentrasi).trigger('change');
            $('#pilihan_4_428j').val(global_data_anggota[index_anggota].kd_sering_sedih_depresi).trigger('change');
            $('#pilihan_4_429').val(global_data_anggota[index_anggota].kd_memiliki_perawat).trigger('change');
            $('#pilihan_4_430').val(global_data_anggota[index_anggota].kd_penyakit_kronis_menahun).trigger('change');

            // keikutsertaan dalam program
            $('#pilihan_4_431a').val(tentukanJumlahTerpilih([99, 8, 4, 2, 1, 0], global_data_anggota[index_anggota].kd_jamkes_setahun)).trigger('change');
            $('#pilihan_4_431b').val(global_data_anggota[index_anggota].kd_ikut_prakerja).trigger('change');
            $('#pilihan_4_431c').val(global_data_anggota[index_anggota].kd_ikut_kur).trigger('change');
            $('#pilihan_4_431d').val(global_data_anggota[index_anggota].kd_ikut_umi).trigger('change');
            $('#pilihan_4_431e').val(global_data_anggota[index_anggota].kd_ikut_pip).trigger('change');
            $('#pilihan_4_431f').val(tentukanJumlahTerpilih([99, 16, 8, 4, 2, 1, 0], global_data_anggota[index_anggota].jumlah_jamket_kerja)).trigger('change');
        }

        function setJumlahTerisi(index_agt = null) {
            let pengaturan = function(element, index_anggota) {
                // index urutan harus sama dengan global_table_art_dtsk
                let temp_jumlah_terisi = [{
                        jumlah: 10,
                        terisi: 0
                    }, // id: 'tabel_ket_demografi'
                    {
                        jumlah: 4,
                        terisi: 0
                    }, // id: 'tabel_pendidikan'
                    {
                        jumlah: 6,
                        terisi: 0
                    }, // id: 'tabel_ketenagakerjaan'
                    {
                        jumlah: 9,
                        terisi: 0
                    }, // id: 'tabel_kepemilikan_usaha'
                    {
                        jumlah: 28,
                        terisi: 0
                    }, // id: 'tabel_kesehatan'
                    {
                        jumlah: 6,
                        terisi: 0
                    }, // id: 'tabel_program_perlindungan_sosial'
                ];
                let indexTR, terisi, tmp_umur, untuk_dicek_terisi;
                let function_cek_sudah_diisi = function(item) {
                    if (global_data_anggota[index_anggota][item] !== null && global_data_anggota[index_anggota][item] !== '') {
                        temp_jumlah_terisi[indexTR].terisi = ++terisi;
                    }
                };

                // -- ket demografi
                indexTR = 0;
                terisi = 0;
                temp_jumlah_terisi[indexTR].jumlah = 8; // default value
                if ([2, 3, 4, 6].indexOf(global_data_anggota[index_anggota].kd_ket_keberadaan_art) > -1) {
                    temp_jumlah_terisi[indexTR].jumlah = 1;
                    temp_jumlah_terisi[indexTR].terisi = 1;
                } else {
                    temp_jumlah_terisi[indexTR].jumlah = 7; // minus kehamilan
                    ['kd_ket_keberadaan_art', 'kd_jenis_kelamin', 'tgl_lahir', 'umur', 'kd_stat_perkawinan', 'kd_hubungan_dg_kk', 'kd_punya_kartuid', ].forEach(function_cek_sudah_diisi);
                }
                tmp_umur = global_data_anggota[index_anggota].umur;
                // status kehamilan untuk perempuan umur 10 s.d 54
                if (tmp_umur >= 10 && tmp_umur <= 54 && ['2', '3', '4'].indexOf(global_data_anggota[index_anggota].kd_stat_perkawinan) > -1 && ['2'].indexOf(global_data_anggota[index_anggota].kd_jenis_kelamin) > -1) {
                    temp_jumlah_terisi[indexTR].jumlah++;
                    ['kd_status_kehamilan'].forEach(function_cek_sudah_diisi);
                }

                // -- pendidikan
                indexTR = 1;
                terisi = 0;
                temp_jumlah_terisi[indexTR].jumlah = 4; // default value
                if (tmp_umur >= 5) {
                    // tidak/belum pernah sekolah
                    if (['1'].indexOf(global_data_anggota[index_anggota].kd_partisipasi_sekolah) > -1) {
                        temp_jumlah_terisi[indexTR].jumlah = 1;
                        temp_jumlah_terisi[indexTR].terisi = 1;
                    }
                    // masih sekolah/tidak sekolah lagi
                    ['kd_partisipasi_sekolah', 'kd_pendidikan_tertinggi', 'kd_kelas_tertinggi', 'kd_ijazah_tertinggi'].forEach(function_cek_sudah_diisi);
                    // kalau belum isi apa2
                    if (temp_jumlah_terisi[indexTR].terisi == 0 && temp_jumlah_terisi[indexTR].jumlah == 4) {
                        temp_jumlah_terisi[indexTR].jumlah = 1;
                    }
                } else {
                    temp_jumlah_terisi[indexTR].jumlah = 0;
                }

                // -- ketenagakerjaan
                indexTR = 2;
                terisi = 0;
                temp_jumlah_terisi[indexTR].jumlah = 6; // default value
                if (tmp_umur >= 5) {
                    // bekerja seminggu lalu
                    if (['1'].indexOf(global_data_anggota[index_anggota].kd_bekerja_seminggu_lalu) > -1) {
                        ['kd_bekerja_seminggu_lalu', 'jumlah_jam_kerja_seminggu_lalu', 'kd_lapangan_usaha_pekerjaan', 'tulis_lapangan_usaha_pekerjaan', 'kd_kedudukan_di_pekerjaan', 'kd_punya_npwp', ].forEach(function_cek_sudah_diisi);
                    } else {
                        temp_jumlah_terisi[indexTR].jumlah = 2;
                        ['kd_bekerja_seminggu_lalu', 'kd_punya_npwp', ].forEach(function_cek_sudah_diisi);
                    }
                } else {
                    temp_jumlah_terisi[indexTR].jumlah = 0;
                }

                // -- kepemilikan usaha
                indexTR = 3;
                terisi = 0;
                temp_jumlah_terisi[indexTR].jumlah = 9; // default value
                if (tmp_umur >= 5) {
                    if (['1'].indexOf(global_data_anggota[index_anggota].kd_punya_usaha_sendiri_bersama) > -1) {
                        ['kd_punya_usaha_sendiri_bersama', 'jumlah_usaha_sendiri_bersama', 'kd_lapangan_usaha_dr_usaha', 'tulis_lapangan_usaha_dr_usaha', 'jumlah_pekerja_dibayar', 'jumlah_pekerja_tidak_dibayar', 'kd_kepemilikan_ijin_usaha', 'kd_omset_usaha_perbulan', 'kd_guna_internet_usaha']
                        .forEach(function_cek_sudah_diisi);
                    } else {
                        temp_jumlah_terisi[indexTR].jumlah = 1;
                        ['kd_punya_usaha_sendiri_bersama'].forEach(function_cek_sudah_diisi);
                    }
                } else {
                    temp_jumlah_terisi[indexTR].jumlah = 0;
                }

                // -- kesehatan
                indexTR = 4;
                terisi = 0;
                temp_jumlah_terisi[indexTR].jumlah = 28; // default value
                untuk_dicek_terisi = ['kd_penyakit_kronis_menahun'];
                if (tmp_umur >= 0 && tmp_umur <= 4) {
                    untuk_dicek_terisi.push('kd_gizi_seimbang');
                }
                if (tmp_umur >= 2) {
                    untuk_dicek_terisi.push('kd_sulit_penglihatan', 'kd_sulit_pendengaran', 'kd_sulit_jalan_naiktangga', 'kd_sulit_gerak_tangan_jari', 'kd_sulit_belajar_intelektual', 'kd_sulit_perilaku_emosi');
                }
                if (tmp_umur >= 5) {
                    untuk_dicek_terisi.push('kd_sulit_paham_bicara_kom', 'kd_sulit_mandiri', 'kd_sulit_ingat_konsentrasi', 'kd_sering_sedih_depresi');
                }
                if (tmp_umur >= 60 || ['1', '2'].indexOf(global_data_anggota[index_anggota].kd_sering_sedih_depresi) || ['1', '2'].indexOf(global_data_anggota[index_anggota].kd_sulit_penglihatan) || ['1', '2'].indexOf(global_data_anggota[index_anggota].kd_sulit_pendengaran) || ['1', '2'].indexOf(
                        global_data_anggota[index_anggota].kd_sulit_jalan_naiktangga) || ['1', '2'].indexOf(global_data_anggota[index_anggota].kd_sulit_gerak_tangan_jari) || ['1', '2'].indexOf(global_data_anggota[index_anggota].kd_sulit_belajar_intelektual) || ['1', '2'].indexOf(global_data_anggota[
                        index_anggota].kd_sulit_perilaku_emosi) || ['1', '2'].indexOf(global_data_anggota[index_anggota].kd_sulit_paham_bicara_kom) || ['1', '2'].indexOf(global_data_anggota[index_anggota].kd_sulit_mandiri) || ['1', '2'].indexOf(global_data_anggota[index_anggota]
                        .kd_sulit_ingat_konsentrasi) || ['1', '2'].indexOf(global_data_anggota[index_anggota].kd_sering_sedih_depresi)) {
                    untuk_dicek_terisi.push('kd_memiliki_perawat');
                }
                temp_jumlah_terisi[indexTR].jumlah = untuk_dicek_terisi.length;
                untuk_dicek_terisi.forEach(function_cek_sudah_diisi);

                // -- program perlindungan sosial
                indexTR = 5;
                terisi = 0;
                temp_jumlah_terisi[indexTR].jumlah = 6; // default value
                untuk_dicek_terisi = ['kd_jamkes_setahun'];
                if (tmp_umur >= 5) {
                    untuk_dicek_terisi.push('kd_ikut_prakerja', 'kd_ikut_kur', 'kd_ikut_umi');
                }
                if (tmp_umur >= 5 && tmp_umur <= 30) {
                    untuk_dicek_terisi.push('kd_ikut_pip');
                }
                if (tmp_umur >= 15) {
                    untuk_dicek_terisi.push('jumlah_jamket_kerja');
                }
                temp_jumlah_terisi[indexTR].jumlah = untuk_dicek_terisi.length;
                untuk_dicek_terisi.forEach(function_cek_sudah_diisi);

                global_data_anggota[index_anggota].jumlah_terisi = temp_jumlah_terisi
            };

            if (index_agt == null) {
                global_data_anggota.forEach(pengaturan);
                global_data_anggota.forEach(function(element, index_anggota) {
                    global_art_jumlah_terisi = global_data_anggota[index_anggota].jumlah_terisi;
                    refresh_jumlah_dan_terisi(element.id);
                });
            } else {
                pengaturan(global_data_anggota[index_agt], index_agt);
                global_art_jumlah_terisi = global_data_anggota[index_agt].jumlah_terisi;
                refresh_jumlah_dan_terisi(global_data_anggota[index_agt].id);
            }
        }

        $(document).ready(function() {
            setJumlahTerisi();
            $('.modal-table').on('click', function(ev) {
                let id_anggota = $(ev.currentTarget).parentsUntil('tr').parent().data('id');
                let table = $(ev.currentTarget).data('table') ?? 'tabel_ket_demografi';
                // cek event change di file modal
                $('#tab4_kelompok_pertanyaan').val(table).trigger('change');
                let index_anggota = global_data_anggota.findIndex(function(item) {
                    return item.id == id_anggota;
                });
                // set global jumlah dan terisi
                global_art_jumlah_terisi = global_data_anggota[index_anggota].jumlah_terisi;
                // refresh select title
                $('#tab4_kelompok_pertanyaan option').each(function(index, el) {
                    let indexTR = global_table_art_dtsk.findIndex(function(item) {
                        return item.id == $(el).val();
                    });
                    if (indexTR != -1) {
                        $(el).text(`${global_table_art_dtsk[indexTR].title_select} ( ${global_art_jumlah_terisi[indexTR].terisi} / ${global_art_jumlah_terisi[indexTR].jumlah} )`);
                    }
                });
                // tampilkan semuanya terlebih dahulu
                $(table).find('tr').each(function() {
                    $(this).show();
                });
                // sembunyikan sesuai kondisi
                global_umur_art = global_data_anggota[index_anggota].umur;
                setDefaultValue(index_anggota);
                if (table == 'tabel_ket_demografi') {
                    $('#pilihan_4_404').trigger('change');
                    // status kehamilan perempuan umur 10 s.d 54
                    show_when_otherwise_hide(global_umur_art >= 10 && global_umur_art <= 54 && ['2', '3', '4'].indexOf($('#pilihan_4_408').val()) > -1 && ['2'].indexOf($('#pilihan_4_405').val()) > -1, ['tr_4_410'], ['tr_4_410']);
                } else if (table == 'tabel_pendidikan') {
                    // tampilkan semua
                    ['tr_4_412', 'tr_4_413', 'tr_4_414', 'tr_4_415'].forEach(function(el) {
                        $('#' + el).show();
                    });
                    // cek
                    if (global_umur_art < 5) {
                        ['tr_4_412', 'tr_4_413', 'tr_4_414', 'tr_4_415'].forEach(function(el) {
                            $('#' + el).hide();
                        });
                    } else {
                        show_when_otherwise_hide(global_umur_art >= 5 && ['2', '3'].indexOf($('#pilihan_4_412').val()) > -1, ['tr_4_413', 'tr_4_414', 'tr_4_415'], ['tr_4_413', 'tr_4_414', 'tr_4_415']);
                    }
                } else if (table == 'tabel_ketenagakerjaan') {
                    // tampilkan semua
                    ['tr_4_416a', 'tr_4_416b', 'tr_4_417', 'tr_4_lapangan_usaha_pekerjaan', 'tr_4_418', 'tr_4_419'].forEach(function(el) {
                        $('#' + el).show();
                    });
                    // cek
                    if (global_umur_art < 5) {
                        ['tr_4_416a', 'tr_4_416b', 'tr_4_417', 'tr_4_lapangan_usaha_pekerjaan', 'tr_4_418', 'tr_4_419'].forEach(function(el) {
                            $('#' + el).hide();
                        });
                    } else {
                        show_when_otherwise_hide(global_umur_art >= 5 && ['1'].indexOf($('#pilihan_4_416a').val()) > -1, ['tr_4_416b', 'tr_4_417', 'tr_4_lapangan_usaha_pekerjaan', 'tr_4_418'], ['tr_4_416b', 'tr_4_417', 'tr_4_lapangan_usaha_pekerjaan', 'tr_4_418']);
                    }
                } else if (table == 'tabel_kepemilikan_usaha') {
                    // tampilkan semua
                    ['tr_4_420a', 'tr_4_420b', 'tr_4_421', 'tr_4_lapangan_usaha_dr_usaha', 'tr_4_422', 'tr_4_423', 'tr_4_424', 'tr_4_425', 'tr_4_426'].forEach(function(el) {
                        $('#' + el).show();
                    });
                    // cek
                    if (global_umur_art < 5) {
                        ['tr_4_420a', 'tr_4_420b', 'tr_4_421', 'tr_4_lapangan_usaha_dr_usaha', 'tr_4_422', 'tr_4_423', 'tr_4_424', 'tr_4_425', 'tr_4_426'].forEach(function(el) {
                            $('#' + el).hide();
                        });
                    } else {
                        show_when_otherwise_hide(global_umur_art >= 5 && ['1'].indexOf($('#pilihan_4_420a').val()) > -1, ['tr_4_420b', 'tr_4_421', 'tr_4_lapangan_usaha_dr_usaha', 'tr_4_422', 'tr_4_423', 'tr_4_424', 'tr_4_425', 'tr_4_426'], ['tr_4_420b', 'tr_4_421',
                            'tr_4_lapangan_usaha_dr_usaha', 'tr_4_422', 'tr_4_423', 'tr_4_424', 'tr_4_425', 'tr_4_426'
                        ]);
                    }
                } else if (table == 'tabel_kesehatan') {
                    show_when_otherwise_hide(global_umur_art <= 4, ['tr_4_427'], ['tr_4_427']);
                    show_when_otherwise_hide(global_umur_art >= 2, ['tr_4_428a', 'tr_4_428b', 'tr_4_428c', 'tr_4_428d', 'tr_4_428e', 'tr_4_428f'], ['tr_4_428a', 'tr_4_428b', 'tr_4_428c', 'tr_4_428d', 'tr_4_428e', 'tr_4_428f']);
                    show_when_otherwise_hide(global_umur_art >= 5, ['tr_4_428g', 'tr_4_428h', 'tr_4_428i', 'tr_4_428j'], ['tr_4_428g', 'tr_4_428h', 'tr_4_428i', 'tr_4_428j']);
                    let ada_kode_1_atau_2 = false;
                    ['#pilihan_4_428a', '#pilihan_4_428b', '#pilihan_4_428c', '#pilihan_4_428d', '#pilihan_4_428e', '#pilihan_4_428f', '#pilihan_4_428g', '#pilihan_4_428h', '#pilihan_4_428i', '#pilihan_4_428j']
                    .forEach(function(item) {
                        if (!ada_kode_1_atau_2) {
                            ada_kode_1_atau_2 = ['1', '2'].indexOf($(item).val()) > -1;
                        }
                    });
                    show_when_otherwise_hide(global_umur_art >= 60 || ada_kode_1_atau_2, ['1', '2'].indexOf($('#pilihan_4_428j').val()) > -1, ['tr_4_429'], ['tr_4_429']);
                } else if (table == 'tabel_program_perlindungan_sosial') {
                    show_when_otherwise_hide(global_umur_art >= 5, ['tr_4_431b', 'tr_4_431c', 'tr_4_431d', ], ['tr_4_431b', 'tr_4_431c', 'tr_4_431d', ]);
                    show_when_otherwise_hide(global_umur_art >= 5 && global_umur_art <= 30, ['tr_4_431e', ], ['tr_4_431e', ]);
                    show_when_otherwise_hide(global_umur_art >= 15, ['tr_4_431f', ], ['tr_4_431f', ]);
                }

            });
            $('#tab4_kelompok_pertanyaan').on('change', function(ev) {
                let selected_value = ev.currentTarget.value;
                $('#tab4_kelompok_pertanyaan option').each(function(index, el) {
                    $('#' + el.value).hide();
                });
                $('#' + selected_value).show();
            });
        });
    </script>
@endpush
