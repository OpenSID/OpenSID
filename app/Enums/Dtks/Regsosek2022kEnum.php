<?php

/*
 *
 * File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package   OpenSID
 * @author    Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace App\Enums\Dtks;

/*
 *
 * File ini bagian dari:
 *
 * OpenSID
 *
 * Sistem informasi desa sumber terbuka untuk memajukan desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * Hak Cipta 2016 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package   OpenSID
 * @author    Tim Pengembang OpenDesa
 * @copyright Hak Cipta 2009 - 2015 Combine Resource Institution (http://lumbungkomunitas.net/)
 * @copyright Hak Cipta 2016 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

defined('BASEPATH') || exit('No direct script access allowed');

class Regsosek2022kEnum
{
    public const YA_TIDAK = [
        '1' => 'Ya',
        '2' => 'Tidak',
    ];

    /**
     * return ['dtks' => [...], 'dtks_anggota' => [....]]
     */
    final public static function getUsedFields(): array
    {
        return [
            'dtks' => [
                'id',
                'is_draft',
                'id_rtm',
                'id_keluarga',
                'created_at',
                'updated_at',
                'versi_kuisioner',

                'kode_provinsi',
                'kode_kabupaten',
                'kode_kecamatan',
                'kode_desa',
                'kode_sls_non_sls',
                'kode_sub_sls',
                'nama_sls_non_sls',
                'no_urut_bangunan_tinggal',
                'no_urut_keluarga_verif',
                'status_keluarga',
                'kode_landmark_wilkerstat',
                'kd_kk',

                'tanggal_pendataan', // index ke 18, jika urutannya belum berubah
                'nama_ppl',
                'kode_ppl',
                'tanggal_pemeriksaan',
                'nama_pml',
                'kode_pml',
                'nama_responden',
                'kd_hasil_pendataan_keluarga',
                'no_hp_responden',

                'kd_stat_bangunan_tinggal',
                'kd_sertiv_lahan_milik',
                'luas_lantai',
                'kd_jenis_lantai_terluas',
                'kd_jenis_dinding',
                'kd_jenis_atap',
                'kd_sumber_air_minum',
                'kd_jarak_sumber_air_ke_tpl',
                'kd_sumber_penerangan_utama',
                'kd_daya_terpasang',
                'kd_daya_terpasang2',
                'kd_daya_terpasang3',
                'kd_bahan_bakar_memasak',
                'kd_fasilitas_tempat_bab',
                'kd_jenis_kloset',
                'kd_pembuangan_akhir_tinja',

                'kd_bss_bnpt',
                'bulan_bss_bnpt',
                'tahun_bss_bnpt',
                'kd_pkh',
                'bulan_pkh',
                'tahun_pkh',
                'kd_blt_dana_desa',
                'bulan_blt_dana_desa',
                'tahun_blt_dana_desa',
                'kd_subsidi_listrik',
                'bulan_subsidi_listrik',
                'tahun_subsidi_listrik',
                'kd_bantuan_pemda',
                'bulan_bantuan_pemda',
                'tahun_bantuan_pemda',
                'kd_subsidi_pupuk',
                'bulan_subsidi_pupuk',
                'tahun_subsidi_pupuk',
                'kd_subsidi_lpg',
                'bulan_subsidi_lpg',
                'tahun_subsidi_lpg',

                'kd_tabung_gas_5_5_kg',
                'kd_lemari_es',
                'kd_ac',
                'kd_pemanas_air',
                'kd_telepon_rumah',
                'kd_televisi',
                'kd_perhiasan_10_gr_emas',
                'kd_komputer_laptop',
                'kd_sepeda_motor',
                'kd_sepeda',
                'kd_mobil',
                'kd_perahu',
                'kd_kapal_perahu_motor',
                'kd_smartphone',

                'jumlah_sapi',
                'jumlah_kerbau',
                'jumlah_kuda',
                'jumlah_babi',
                'jumlah_kambing_domba',

                'kd_lahan',
                'kd_rumah_ditempat_lain',
                'kd_internet_sebulan',
                'kd_rek_aktif',

                'catatan',
            ],
            // -------------------------------------------------------- pemisah & penanda
            'dtks_anggota' => [
                'id',
                'id_dtks',
                'id_penduduk',
                'id_keluarga',
                'created_at',
                'updated_at',

                'kd_ket_keberadaan_art',
                'kd_hubungan_dg_kk',
                'kd_punya_kartuid',

                'kd_partisipasi_sekolah',
                'kd_pendidikan_tertinggi',
                'kd_kelas_tertinggi',
                'kd_ijazah_tertinggi',

                'kd_bekerja_seminggu_lalu',
                'jumlah_jam_kerja_seminggu_lalu',
                'kd_lapangan_usaha_pekerjaan',
                'tulis_lapangan_usaha_pekerjaan',
                'kd_kedudukan_di_pekerjaan',
                'kd_punya_npwp',

                'kd_punya_usaha_sendiri_bersama',
                'jumlah_usaha_sendiri_bersama',
                'kd_lapangan_usaha_dr_usaha',
                'tulis_lapangan_usaha_dr_usaha',
                'jumlah_pekerja_dibayar',
                'jumlah_pekerja_tidak_dibayar',
                'kd_kepemilikan_ijin_usaha',
                'kd_omset_usaha_perbulan',
                'kd_guna_internet_usaha',

                'kd_gizi_seimbang',
                'kd_sulit_penglihatan',
                'kd_sulit_pendengaran',
                'kd_sulit_jalan_naiktangga',
                'kd_sulit_gerak_tangan_jari',
                'kd_sulit_belajar_intelektual',
                'kd_sulit_perilaku_emosi',
                'kd_sulit_paham_bicara_kom',
                'kd_sulit_mandiri',
                'kd_sulit_ingat_konsentrasi',
                'kd_sering_sedih_depresi',
                'kd_memiliki_perawat',
                'kd_penyakit_kronis_menahun',

                'kd_jamkes_setahun',
                'kd_ikut_prakerja',
                'kd_ikut_kur',
                'kd_ikut_umi',
                'kd_ikut_pip',
                'jumlah_jamket_kerja',
            ],
        ];
    }

    final public static function pilihanBagian1(): array
    {
        return ['115' => [
            '0' => '0. KK Sesuai',
            '1' => '1. Keluarga Induk',
            '2' => '2. Keluarga pecahan',
        ]];
    }

    final public static function pilihanBagian2(): array
    {
        return [
            '205' => [
                '1' => '1. Terisi lengkap',
                '2' => '2. Terisi tidak lengkap',
                '3' => '3. Tidak ada responden yang dapat memberi jawaban sampai akhir masa pendataan',
                '4' => '4. Responden menolak',
                '5' => '5. Keluarga pindah/bangunan sensus sudah tidak ada',
            ],
        ];
    }

    final public static function pilihanBagian3()
    {
        $pilihan3 = [
            '301a' => [
                '1' => '1. Milik sendiri',
                '2' => '2. Kontrak/sewa',
                '3' => '3. Bebas sewa',
                '4' => '4. Dinas',
                '5' => '5. Lainnya',
            ],
            '301b' => [
                '1' => '1. SHM atas Nama Anggota Keluarga',
                '2' => '2. SHM bukan a.n Anggota Keluarga dengan perjanjian pemanfaatan tertulis',
                '3' => '3. SHM bukan a.n Anggota Keluarga tanpa perjanjian pemanfaatan tertulis',
                '4' => '4. Sertfikat selain SHM (SHGB, SHSRS)',
                '5' => '5. Surat bukti lainnya (Girik, Letter C, dll)',
                '6' => '6. Tidak Punya',
            ],
            '303' => [
                '1' => '1. Marmer/granit',
                '2' => '2. Keramik',
                '3' => '3. Parket/vinil/karpet',
                '4' => '4. Ubin/tegel/teraso',
                '5' => '5. Kayu/papan',
                '6' => '6. Semen/bata merah',
                '7' => '7. Bambu',
                '8' => '8. Tanah',
                '9' => '9. Lainnya',
            ],
            '304' => [
                '1' => '1. Tembok',
                '2' => '2. Plesteran anyaman bambu/kawat',
                '3' => '3. Kayu/papan/Gypsum/GRC/Calciboard',
                '4' => '4. Anyaman bambu',
                '5' => '5. Batang kayu',
                '6' => '6. Bambu',
                '7' => '7. Lainnya',
            ],
            '305' => [
                '1' => '1. Beton',
                '2' => '2. Genteng',
                '3' => '3. Seng',
                '4' => '4. Asbes',
                '5' => '5. Kayu/sirap',
                '6' => '6. Bambu',
                '7' => '7. Jerami/ijuk/daun-daunan/rumbia',
                '8' => '8. Lainnya',
            ],
            '306a' => [
                '1'  => '1. Air kemasan bermerk',
                '2'  => '2. Air isi ulang',
                '3'  => '3. Leding',
                '4'  => '4. Sumur bor/pompa',
                '5'  => '5. Sumur terlindung',
                '6'  => '6. Sumur tak terlindung',
                '7'  => '7. Mata air terlindung',
                '8'  => '8. Mata air tak terlindung',
                '9'  => '9. Air permukaan (sungai/danau/waduk/kolam/irigasi)',
                '10' => '10. Air hujan',
                '11' => '11. Lainnya',
            ],
            '306b' => [
                '1' => '1. < 10 meter',
                '2' => '2. &ge; 10 meter',
                '3' => '3. Tidak tahu',
            ],
            '307a' => [
                '1' => '1. Listrik PLN dengan meteran',
                '2' => '2. Listrik PLN tanpa meteran',
                '3' => '3. Listrik Non-PLN',
                '4' => '4. Bukan listrik',
            ],
            '307b1' => [
                '1' => '1. 450 watt',
                '2' => '2. 900 watt',
                '3' => '3. 1.300 watt',
                '4' => '4. 2.200 watt',
                '5' => '5. > 2.200 watt',
            ],
        ];

        return $pilihan3 + [
            '307b2' => $pilihan3['307b1'],
            '307b3' => $pilihan3['307b1'],
            '308'   => [
                '1'  => '1. Listrik',
                '2'  => '2. Gas elpiji 5,5kg/Blue gaz',
                '3'  => '3. Gas elpiji 12 kg',
                '4'  => '4. Gas elpiji 3 kg',
                '5'  => '5. Gas kota/meteran PGN',
                '6'  => '6. Biogas',
                '7'  => '7. Minyak tanah',
                '8'  => '8. Briket',
                '9'  => '9. Arang',
                '10' => '10. Kayu bakar',
                '11' => '11. Lainnya',
                '12' => '12. Tidak memasak di rumah',
            ],
            '309a' => [
                '1' => '1. Ada, digunakan hanya Anggota Keluarga sendiri ',
                '2' => '2. Ada, digunakan bersama Anggota Keluarga dari rumah tangga tertentu',
                '3' => '3. Ada, di MCK komunal',
                '4' => '4. Ada, di MCK umum/siapapun menggunakan',
                '5' => '5. Ada, Anggota Keluarga tidak menggunakan',
                '6' => '6. Tidak ada fasilitas',
            ],
            '309b' => [
                '1' => '1. Leher angsa',
                '2' => '2. Plengsengan dengan tutup',
                '3' => '3. Plengsengan tanpa tutup',
                '4' => '4. Cemplung/cubluk',
            ],
            '310' => [
                '1' => '1. Tangki septik',
                '2' => '2. IPAL',
                '3' => '3. Kolam/sawah/sungai/danau/laut',
                '4' => '4. Lubang tanah',
                '5' => '5. Pantai/tanah lapang/kebun',
                '6' => '6. Lainnya',
            ],
        ];
    }

    final public static function pilihanBagian4()
    {
        $pilihan4 = [
            '404' => [
                '1' => '1. Tinggal bersama keluarga',
                '2' => '2. Meninggal',
                '3' => '3. Tidak tinggal bersama keluarga/pindah ke wilayah (daerah) lain di Indonesia',
                '4' => '4. Tidak tinggal bersama keluarga/pindah ke luar negeri',
                '5' => '5. Anggota Keluarga baru',
                '6' => '6. Tidak ditemukan',
            ],
            '405' => [
                '1' => '1. Laki-laki',
                '2' => '2. Perempuan',
            ],
            '408' => [
                '1' => '1. Belum kawin',
                '2' => '2. Kawin/nikah',
                '3' => '3. Cerai hidup',
                '4' => '4. Cerai mati',
            ],
            '409' => [
                '1' => '1. Kepala keluarga',
                '2' => '2. Istri/suami',
                '3' => '3. Anak',
                '4' => '4. Menantu',
                '5' => '5. Cucu',
                '6' => '6. Orang tua/mertua',
                '7' => '7. Pembantu/sopir',
                '8' => '8. Lainnya',
            ],
            '410' => self::YA_TIDAK,
            '411' => [
                '0' => '0. Tidak memiliki',
                '1' => '1. Akta Kelahiran',
                '2' => '2. KIA',
                '4' => '4. KTP',
            ],
            '412' => [
                '1' => '1. Tidak/belum pernah sekolah',
                '2' => '2. Masih sekolah',
                '3' => '3. Tidak bersekolah lagi',
            ],
            '413' => [
                '1'  => '01. Paket A',
                '2'  => '02. SDLB',
                '3'  => '03. SD',
                '4'  => '04. MI',
                '5'  => '05. SPM/PDF Ula',
                '6'  => '06. Paket B',
                '7'  => '07. SMP LB',
                '8'  => '08. SMP',
                '9'  => '09. MTs',
                '10' => '10. SPM/PDF Wustha',
                '11' => '11. Paket C',
                '12' => '12. SMLB',
                '13' => '13. SMA',
                '14' => '14. MA',
                '15' => '15. SMK',
                '16' => '16. MAK',
                '17' => '17. SPM/PDF Ulya',
                '18' => '18. D1/D2/D3',
                '19' => '19. D4/S1',
                '20' => '20. Profesi',
                '21' => '21. S2',
                '22' => '22. S3',
            ],
            '414' => [
                '1' => '1 ',
                '2' => '2 ',
                '3' => '3',
                '4' => '4',
                '5' => '5',
                '6' => '6',
                '7' => '7',
                '8' => '8 (Tamat & Lulus)',
            ],
        ];
        $pilihan4 += [
            '415' => $pilihan4['413'] + [
                '23' => '23. Tidak Punya Ijazah SD',
            ],
            '416a' => [
                '1' => '1. Ya',
                '2' => '2. Tidak',
            ],
            // '416b' => 'int(2)',
            '417' => [
                '1'  => '01. Pertanian tanaman padi & palawija',
                '2'  => '02. Hortikultura',
                '3'  => '03. Perkebunan',
                '4'  => '04. Perikanan ',
                '5'  => '05. Peternakan',
                '6'  => '06. Kehutanan & pertanian lainnya',
                '7'  => '07. Pertambangan/penggalian',
                '8'  => '08. Industri pengolahan',
                '9'  => '09. Pengadaan listrik, gas, uap/air panas, & udara dingin',
                '10' => '10. Pengelolaan air, pengelolaan air limbah, pengelolaan dan daur ulang sampah, dan aktivitas remediasi',
                '11' => '11. Konstruksi',
                '12' => '12. Perdagangan besar dan eceran, reparasi dan perawatan mobil dan sepeda motor',
                '13' => '13. Pengangkutan dan pergudangan',
                '14' => '14. Penyediaan akomodasi & makan minum',
                '15' => '15. Informasi & komunikasi',
                '16' => '16. Keuangan & asuransi',
                '17' => '17. Real estate',
                '18' => '18. Aktivitas profesional, ilmiah, dan teknis',
                '19' => '19. Aktivitas penyewaan dan sewa guna tanpa hak opsi, ketenagakerjaan, agen perjalanan, dan penunjang usaha lainnya',
                '20' => '20. Administrasi pemerintahan, pertahanan, dan jaminan sosial wajib',
                '21' => '21. Pendidikan',
                '22' => '22. Aktivitas kesehatan manusia dan aktivitas sosial',
                '23' => '23. Kesenian, hiburan, dan rekreasi',
                '24' => '24. Aktivitas jasa lainnya',
                '25' => '25. Aktivitas keluarga sebagai pemberi kerja',
                '26' => '26. Aktivitas badan internasional dan badan ekstra internasional lainnya',
            ],
            '418' => [
                '1' => '1. Berusaha sendiri',
                '2' => '2. Berusaha dibantu buruh tidak tetap/tidak dibayar',
                '3' => '3. Berusaha dibantu buruh tetap/dibayar',
                '4' => '4. Buruh/karyawan/pegawai swasta',
                '5' => '5. PNS/TNI/ Polri/BUMN/BUMD/pejabat negara  ',
                '6' => '6. Pekerja bebas pertanian',
                '7' => '7. Pekerja bebas non-pertanian',
                '8' => '8. Pekerja keluarga/tidak dibayar',
            ],
            '419' => [
                '1' => '1. Ada, Dapat menunjukkan',
                '2' => '2. Ada, Tidak dapat  menunjukkan ',
                '3' => '3. Tidak ada',
            ],
            '420a' => self::YA_TIDAK,
            // '420b' => 'int(2)',
        ];
        $pilihan4 += [
            '421' => $pilihan4['417'],
            // '422' => 'int(3)',
            // '423' => 'int(2)',
            '424' => [
                '1'  => '01. Surat Izin Tempat Usaha (SITU)',
                '2'  => '02. Surat Izin Usaha Perdagangan (SIUP)',
                '3'  => '03. Nomor Registrasi Perusahaan (NRP)',
                '4'  => '04. Nomor Induk Berusaha (NIB)',
                '5'  => '05. Surat Keterangan Domisili Perusahaan (SKDP)',
                '6'  => '06. Analisis Mengenai Dampak Lingkungan (Amdal)',
                '7'  => '07. Surat Izin Mendirikan Bangunan (SIMB)',
                '8'  => '08. Surat Keputusan Badan Hukum (SKBH)',
                '9'  => '09. Akta Pendirian Perseroan Terbatas (APPT)',
                '10' => '10. Surat izin lainnya',
                '11' => '11. Belum memiliki izin usaha',
                '12' => '12. Surat Izin Gangguan',
            ],
            '425' => [
                '1' => '1. < 5 Juta (ultra mikro)',
                '2' => '2. 5 -< 15 Juta (ultra mikro)',
                '3' => '3. 15 -< 25 Juta (ultra mikro)',
                '4' => '4. 25 -< 167 Juta (mikro)',
                '5' => '5. 167 -< 1.250 Juta (kecil)',
                '6' => '6. 1.250 -< 4.167 Juta (menengah)',
                '7' => '7. &ge; 4.167 Juta (besar)',
            ],
            '426' => [
                '0'  => '00. Tidak menggunakan internet',
                '1'  => '01. Sebagai sarana komunikasi',
                '2'  => '02. Untuk mencari informasi',
                '4'  => '04. Sebagai Pemasaran/Iklan',
                '8'  => '08. Sebagai Sarana Penjualan Produk/Output',
                '16' => '16. Sebagai Pembelian dan/atau Produksi',
                '32' => '32. Lainnya',
            ],
            '427' => [
                '1' => '1. Kurang Gizi (Wasting)',
                '2' => '2. Kerdil (Stunting)',
                '3' => '3. Tidak ada catatan',
                '8' => '8. Tidak tahu',
            ],
            '428a' => [
                '1' => '1. Ya, sama sekali tidak bisa',
                '2' => '2. Ya, banyak kesulitan dan membutuhkan bantuan',
                '3' => '3. Ya, sedikit kesulitan, tapi tidak membutuhkan bantuan',
                '4' => '4. Tidak mengalami kesulitan',
            ],
        ];
        $pilihan4 += [
            '428b' => $pilihan4['428a'],
            '428c' => $pilihan4['428a'],
            '428d' => $pilihan4['428a'],
            '428e' => $pilihan4['428a'],
            '428f' => $pilihan4['428a'],
            '428g' => $pilihan4['428a'],
            '428h' => $pilihan4['428a'],
            '428i' => $pilihan4['428a'],
            '428j' => [
                '1' => '1. Sangat sering',
                '2' => '2. Sering',
                '3' => '3. Jarang',
                '4' => '4. Tidak pernah',
            ],
            '429' => [
                '1' => '1. Ya, Anggota Keluarga',
                '2' => '2. Ya, Bukan Anggota Keluarga',
                '3' => '3. Ya, Tinggal Sendiri',
            ],
            '430' => [
                '1'  => '01. Tidak Ada',
                '2'  => '02. Hipertensi (darah tinggi)',
                '3'  => '03. Rematik',
                '4'  => '04. Asma',
                '5'  => '05. Masalah jantung',
                '6'  => '06. Diabetes (kencing manis)',
                '7'  => '07. Tuberculosis (TBC)',
                '8'  => '08. Stroke',
                '9'  => '09. Kanker atau tumor ganas',
                '10' => '10. Gagal ginjal',
                '11' => '11. Haemophilia',
                '12' => '12. HIV/AIDS',
                '13' => '13. Kolesterol',
                '14' => '14. Sirosis Hati',
                '15' => '15. Thalasemia',
                '16' => '16. Leukimia',
                '17' => '17. Alzheimer',
                '18' => '18. Lainnya',
            ],
            '431a' => [
                '0'  => '0. Tidak memiliki',
                '1'  => '1. PBI/JKN',
                '2'  => '2. JKN Mandiri',
                '4'  => '4. JKN Pemberi Kerja',
                '8'  => '8. Jamkes lainnya',
                '99' => '99. Lainnya',
            ],
            '431b' => [
                '1' => '1. Ya',
                '2' => '2. Tidak',
                '8' => '8. Tidak tahu',
            ],
        ];

        return $pilihan4 + [
            '431c' => $pilihan4['431b'],
            '431d' => $pilihan4['431b'],
            '431e' => $pilihan4['431b'],
            '431f' => [
                '0'  => '00. Tidak memiliki',
                '1'  => '01. BPJS Jaminan Kecelakaan Kerja',
                '2'  => '02. BPJS Jaminan Kematian',
                '4'  => '04. BPJS Jaminan Hari Tua',
                '8'  => '08. BPJS Jaminan Pensiun',
                '16' => '16. Pensiunan/Jaminan hari tua lainnya (Taspen/Program Pensiun Swasta)',
                '99' => '99. Tidak tahu',
            ],
        ];
    }

    final public static function pilihanBagian5(): array
    {
        return [
            'ya_tidak' => self::YA_TIDAK,

            '505' => [
                '0' => '0. Tidak menggunakan internet',
                '1' => '1. Internet dan TV digital berlangganan',
                '2' => '2. Wifi',
                '3' => '3. Internet <i>Handphone</i>',
            ],
            '506' => [
                '1' => '1. Ya, untuk usaha',
                '2' => '2. Ya, untuk pribadi',
                '3' => '3. Ya, untuk usaha dan pribadi',
                '4' => '4. Tidak',
            ],
        ];
    }
}
