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
 * Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

namespace App\Models;

use App\Enums\Dtks\DtksEnum;
use App\Traits\ConfigId;

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * @property int $id
 * @property int $config_id
 * @property int $is_draft
 * @property int|null $id_rtm
 * @property int|null $id_keluarga
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $versi_kuisioner
 * @property string|null $catatan
 * @property string|null $kode_provinsi
 * @property string|null $kode_kabupaten
 * @property string|null $kode_kecamatan
 * @property string|null $kode_desa
 * @property string|null $kode_sls_non_sls
 * @property string|null $kode_sub_sls
 * @property string|null $nama_sls_non_sls
 * @property string|null $no_urut_bangunan_tinggal
 * @property string|null $no_urut_keluarga_verif
 * @property string|null $status_keluarga
 * @property string|null $kode_landmark_wilkerstat
 * @property string|null $kd_kk
 * @property string|null $no_urut_ruta
 * @property \Illuminate\Support\Carbon|null $tanggal_pencacahan
 * @property string|null $nama_petugas_pencacahan
 * @property string|null $kode_petugas_pencacahan
 * @property \Illuminate\Support\Carbon|null $tanggal_pemeriksaan
 * @property string|null $nama_pemeriksa
 * @property string|null $kode_pemeriksa
 * @property string|null $nama_responden
 * @property string|null $kd_hasil_pencacahan_ruta
 * @property \Illuminate\Support\Carbon|null $tanggal_pendataan
 * @property string|null $nama_ppl
 * @property string|null $kode_ppl
 * @property string|null $nama_pml
 * @property string|null $kode_pml
 * @property string|null $kd_hasil_pendataan_keluarga
 * @property string|null $no_hp_responden
 * @property string|null $kd_stat_bangunan_tinggal
 * @property string|null $kd_stat_lahan_tinggal
 * @property string|null $kd_sertiv_lahan_milik
 * @property int|null $luas_lantai
 * @property string|null $kd_jenis_lantai_terluas
 * @property string|null $kd_jenis_dinding
 * @property string|null $kd_kondisi_dinding
 * @property string|null $kd_jenis_atap
 * @property string|null $kd_kondisi_atap
 * @property string|null $jumlah_kamar_tidur
 * @property string|null $kd_sumber_air_minum
 * @property string|null $kd_jarak_sumber_air_ke_tpl
 * @property string|null $kd_memperoleh_air_minum
 * @property string|null $kd_sumber_penerangan_utama
 * @property string|null $kd_daya_terpasang
 * @property string|null $kd_daya_terpasang2
 * @property string|null $kd_daya_terpasang3
 * @property string|null $kode_pelanggan_daya
 * @property string|null $kd_bahan_bakar_memasak
 * @property string|null $kd_fasilitas_tempat_bab
 * @property string|null $kd_jenis_kloset
 * @property string|null $kd_pembuangan_akhir_tinja
 * @property string|null $kd_tabung_gas_3_kg
 * @property string|null $kd_tabung_gas_5_5_kg
 * @property string|null $kd_tabung_gas_12_kg
 * @property string|null $kd_lemari_es
 * @property string|null $kd_ac
 * @property string|null $kd_pemanas_air
 * @property string|null $kd_telepon_rumah
 * @property string|null $kd_televisi
 * @property string|null $kd_perhiasan_10_gr_emas
 * @property string|null $kd_rek_aktif
 * @property string|null $kd_komputer_laptop
 * @property string|null $kd_sepeda_motor
 * @property string|null $kd_mobil
 * @property string|null $kd_perahu
 * @property string|null $kd_kapal_perahu_motor
 * @property string|null $kd_featured_phone
 * @property string|null $kd_smartphone
 * @property string|null $kd_sepeda
 * @property string|null $kd_lahan
 * @property int|null $luas_lahan
 * @property string|null $kd_ada_sertiv_lahan
 * @property string|null $kd_rumah_ditempat_lain
 * @property int|null $jumlah_sapi
 * @property int|null $jumlah_kerbau
 * @property int|null $jumlah_kuda
 * @property int|null $jumlah_babi
 * @property int|null $jumlah_kambing_domba
 * @property int|null $jumlah_unggas
 * @property int|null $jumlah_ikan
 * @property int|null $jumlah_lainnya
 * @property string|null $kd_ada_art_usaha_sendiri_bersama
 * @property string|null $kd_internet_sebulan
 * @property string|null $kd_pengeluaran_pulsa_dan_data
 * @property string|null $kd_ada_art_lanjut_usia
 * @property string|null $kd_bss_bnpt
 * @property string|null $bulan_bss_bnpt
 * @property string|null $tahun_bss_bnpt
 * @property string|null $kd_pkh
 * @property string|null $bulan_pkh
 * @property string|null $tahun_pkh
 * @property string|null $kd_bst_covid19
 * @property string|null $bulan_bst_covid19
 * @property string|null $tahun_bst_covid19
 * @property string|null $kd_blt_dana_desa
 * @property string|null $bulan_blt_dana_desa
 * @property string|null $tahun_blt_dana_desa
 * @property string|null $kd_subsidi_listrik
 * @property string|null $bulan_subsidi_listrik
 * @property string|null $tahun_subsidi_listrik
 * @property string|null $kd_asuransi_lain
 * @property string|null $bulan_asuransi_lain
 * @property string|null $tahun_asuransi_lain
 * @property string|null $kd_bantuan_pemprov
 * @property string|null $bulan_bantuan_pemprov
 * @property string|null $tahun_bantuan_pemprov
 * @property string|null $kd_bantuan_pemkabkot
 * @property string|null $bulan_bantuan_pemkabkot
 * @property string|null $tahun_bantuan_pemkabkot
 * @property string|null $kd_bantuan_pemdes
 * @property string|null $bulan_bantuan_pemdes
 * @property string|null $tahun_bantuan_pemdes
 * @property string|null $kd_bantuan_pemda
 * @property string|null $bulan_bantuan_pemda
 * @property string|null $tahun_bantuan_pemda
 * @property string|null $kd_bantuan_masyarakat
 * @property string|null $bulan_bantuan_masyarakat
 * @property string|null $tahun_bantuan_masyarakat
 * @property string|null $kd_subsidi_pupuk
 * @property string|null $bulan_subsidi_pupuk
 * @property string|null $tahun_subsidi_pupuk
 * @property string|null $kd_subsidi_lpg
 * @property string|null $bulan_subsidi_lpg
 * @property string|null $tahun_subsidi_lpg
 * @property string|null $kd_konsumsi_daging
 * @property string|null $kd_makan
 * @property string|null $kd_beli_pakaian_baru
 * @property string|null $kd_bayar_biaya_pengobatan
 * @property string|null $kd_bahasa_wawancara
 * @property string|null $tulis_bahasa_daerah
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DtksAnggota> $dtksAnggota
 * @property-read int|null $dtks_anggota_count
 * @property-read mixed $alamat
 * @property-read mixed $anggota_keluarga_in_r_t_m
 * @property-read mixed $jumlah_anggota_d_t_k_s
 * @property-read mixed $jumlah_keluarga
 * @property-read mixed $keluarga_in_r_t_m
 * @property-read mixed $kepala_keluarga
 * @property-read mixed $kepala_keluarga_d_t_k_s
 * @property-read mixed $kepala_rumah_tangga
 * @property-read mixed $nik_k_k
 * @property-read mixed $nik_krt
 * @property-read mixed $no_k_k
 * @property-read mixed $no_kk_art
 * @property-read string $versi_kuisioner_name
 * @property-read \App\Models\Keluarga|null $keluarga
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\DtksLampiran> $lampiran
 * @property-read int|null $lampiran_count
 * @property-read \App\Models\Rtm|null $rtm
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereBulanAsuransiLain($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereBulanBantuanMasyarakat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereBulanBantuanPemda($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereBulanBantuanPemdes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereBulanBantuanPemkabkot($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereBulanBantuanPemprov($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereBulanBltDanaDesa($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereBulanBssBnpt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereBulanBstCovid19($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereBulanPkh($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereBulanSubsidiListrik($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereBulanSubsidiLpg($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereBulanSubsidiPupuk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereCatatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereConfigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereIdKeluarga($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereIdRtm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereIsDraft($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereJumlahBabi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereJumlahIkan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereJumlahKamarTidur($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereJumlahKambingDomba($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereJumlahKerbau($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereJumlahKuda($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereJumlahLainnya($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereJumlahSapi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereJumlahUnggas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKdAc($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKdAdaArtLanjutUsia($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKdAdaArtUsahaSendiriBersama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKdAdaSertivLahan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKdAsuransiLain($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKdBahanBakarMemasak($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKdBahasaWawancara($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKdBantuanMasyarakat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKdBantuanPemda($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKdBantuanPemdes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKdBantuanPemkabkot($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKdBantuanPemprov($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKdBayarBiayaPengobatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKdBeliPakaianBaru($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKdBltDanaDesa($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKdBssBnpt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKdBstCovid19($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKdDayaTerpasang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKdDayaTerpasang2($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKdDayaTerpasang3($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKdFasilitasTempatBab($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKdFeaturedPhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKdHasilPencacahanRuta($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKdHasilPendataanKeluarga($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKdInternetSebulan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKdJarakSumberAirKeTpl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKdJenisAtap($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKdJenisDinding($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKdJenisKloset($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKdJenisLantaiTerluas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKdKapalPerahuMotor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKdKk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKdKomputerLaptop($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKdKondisiAtap($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKdKondisiDinding($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKdKonsumsiDaging($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKdLahan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKdLemariEs($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKdMakan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKdMemperolehAirMinum($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKdMobil($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKdPemanasAir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKdPembuanganAkhirTinja($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKdPengeluaranPulsaDanData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKdPerahu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKdPerhiasan10GrEmas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKdPkh($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKdRekAktif($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKdRumahDitempatLain($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKdSepeda($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKdSepedaMotor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKdSertivLahanMilik($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKdSmartphone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKdStatBangunanTinggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKdStatLahanTinggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKdSubsidiListrik($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKdSubsidiLpg($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKdSubsidiPupuk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKdSumberAirMinum($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKdSumberPeneranganUtama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKdTabungGas12Kg($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKdTabungGas3Kg($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKdTabungGas55Kg($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKdTeleponRumah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKdTelevisi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKodeDesa($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKodeKabupaten($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKodeKecamatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKodeLandmarkWilkerstat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKodePelangganDaya($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKodePemeriksa($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKodePetugasPencacahan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKodePml($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKodePpl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKodeProvinsi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKodeSlsNonSls($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereKodeSubSls($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereLuasLahan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereLuasLantai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereNamaPemeriksa($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereNamaPetugasPencacahan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereNamaPml($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereNamaPpl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereNamaResponden($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereNamaSlsNonSls($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereNoHpResponden($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereNoUrutBangunanTinggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereNoUrutKeluargaVerif($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereNoUrutRuta($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereStatusKeluarga($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereTahunAsuransiLain($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereTahunBantuanMasyarakat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereTahunBantuanPemda($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereTahunBantuanPemdes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereTahunBantuanPemkabkot($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereTahunBantuanPemprov($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereTahunBltDanaDesa($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereTahunBssBnpt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereTahunBstCovid19($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereTahunPkh($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereTahunSubsidiListrik($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereTahunSubsidiLpg($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereTahunSubsidiPupuk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereTanggalPemeriksaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereTanggalPencacahan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereTanggalPendataan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereTulisBahasaDaerah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks whereVersiKuisioner($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Dtks withoutConfigId($configId = '')
 * @mixin \Eloquent
 */
class Dtks extends BaseModel
{
    use ConfigId;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dtks';

    /**
     * The guarded with the model.
     *
     * @var array
     */
    protected $guarded = [];

    protected $appends = [
        'versi_kuisioner_name',
    ];
    protected $casts = [
        'created_at'          => 'date:Y-m-d H:i:s',
        'updated_at'          => 'date:Y-m-d H:i:s',
        'tanggal_pencacahan'  => 'date:Y-m-d',
        'tanggal_pemeriksaan' => 'date:Y-m-d',
        'tanggal_pendataan'   => 'date:Y-m-d',
    ];
    // /**
    //  * The fillable with the model.
    //  *
    //  * @var array
    //  */
    // protected $fillable = [
    //     'versi_kuisioner',
    //     'is_draft'
    // ];

    public function getVersiKuisionerNameAttribute(): string
    {
        return DtksEnum::VERSION_LIST[$this->attributes['versi_kuisioner']] ?? 'Tidak Ditemukan';
    }

    /**
     * Define a one-to-one relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function rtm()
    {
        return $this->hasOne(Rtm::class, 'id', 'id_rtm')->withoutGlobalScope(\App\Scopes\ConfigIdScope::class);
    }

    public function keluarga()
    {
        return $this->hasOne(Keluarga::class, 'id', 'id_keluarga')->withoutGlobalScope(\App\Scopes\ConfigIdScope::class);
    }

    public function getKeluargaInRTMAttribute()
    {
        $this->loadMissing([
            'rtm.anggota' => static function ($builder): void {
                // override all items within the $with property in Penduduk
                $builder->withOnly('keluarga')->withoutGlobalScope(\App\Scopes\ConfigIdScope::class);
                // hanya ambil data anggota yg masih hidup (tweb_penduduk)
                $builder->where('status_dasar', 1);
            },
        ]);

        return $this->rtm->anggota->pluck('keluarga')->unique();
    }

    public function getAnggotaKeluargaInRTMAttribute()
    {
        $this->loadMissing([
            'rtm.anggota' => static function ($builder): void {
                // override all items within the $with property in Penduduk
                $builder->without([
                    'wilayah',
                ]);
                // hanya ambil data anggota yg masih hidup (tweb_penduduk)
                $builder->where('status_dasar', 1);
            },
        ]);

        return $this->rtm->anggota->groupBy('id_kk');
    }

    public function getKepalaRumahTanggaAttribute()
    {
        $this->loadMissing([
            'rtm.kepalaKeluarga',
        ]);

        return $this->rtm->kepalaKeluarga;
    }

    public function getKepalaKeluargaAttribute()
    {
        $this->loadMissing([
            'keluarga.kepalaKeluarga',
        ]);

        return $this->keluarga->kepalaKeluarga;
    }

    public function getKepalaKeluargaDTKSAttribute()
    {
        $this->loadMissing([
            'keluarga.kepalaKeluarga',
            'dtksAnggota',
        ]);

        return $this->dtksAnggota->where('id_penduduk', $this->keluarga->kepalaKeluarga->id)->first();
    }

    public function getJumlahAnggotaDTKSAttribute()
    {
        $this->loadMissing('dtksAnggota');

        return $this->dtksAnggota->count();
    }

    public function getNikKKAttribute()
    {
        $this->loadMissing([
            'keluarga.kepalaKeluarga' => static function ($builder): void {
                // override all items within the $with property in Penduduk
                $builder->withoutRelations();
            },
        ]);

        return $this->keluarga->kepalaKeluarga->nik;
    }

    public function getNikKrtAttribute()
    {
        $this->loadMissing([
            'rtm.kepalaKeluarga' => static function ($builder): void {
                // override all items within the $with property in Penduduk
                $builder->withoutRelations();
            },
        ]);

        return $this->rtm->kepalaKeluarga->nik;
    }

    public function getAlamatAttribute()
    {
        $this->loadMissing([
            'rtm.kepalaKeluarga' => static function ($builder): void {
                // override all items within the $with property in Penduduk
                $builder->withoutRelations();
            },
        ]);

        return $this->rtm->kepalaKeluarga->alamat_wilayah;
    }

    public function getJumlahKeluargaAttribute()
    {
        return $this->getNoKKArtAttribute()->count();
    }

    public function getNoKKAttribute()
    {
        return $this->getKepalaKeluargaAttribute()->keluarga->no_kk;
    }

    public function getNoKkArtAttribute()
    {
        return $this->getKeluargaInRTMAttribute()->pluck('no_kk');
    }

    public function dtksAnggota()
    {
        return $this->hasMany(DtksAnggota::class, 'id_dtks')->withoutGlobalScope(\App\Scopes\ConfigIdScope::class);
    }

    public function lampiran()
    {
        return $this->belongsToMany(DtksLampiran::class, 'dtks_ref_lampiran', 'id_dtks', 'id_lampiran')->withoutGlobalScope(\App\Scopes\ConfigIdScope::class);
    }
}
