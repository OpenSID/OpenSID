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

use App\Traits\ConfigId;

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * @property int $id
 * @property int $config_id
 * @property int|null $id_dtks
 * @property int|null $id_penduduk
 * @property int|null $id_keluarga
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $kd_ket_keberadaan_art
 * @property string|null $bulan_meninggal
 * @property string|null $tahun_meninggal
 * @property string|null $kd_punya_akta_meniggal
 * @property string|null $bulan_pindah_tempat
 * @property string|null $tahun_pindah_tempat
 * @property string|null $kd_tempat_tinggal_saat_ini
 * @property string|null $bulan_masuk_ruta
 * @property string|null $tahun_masuk_ruta
 * @property string|null $kd_alasan_masuk_ruta
 * @property string|null $kd_hubungan_dg_krt
 * @property string|null $kd_hubungan_dg_kk
 * @property string|null $kd_jenis_kelamin
 * @property string|null $kd_punya_aktanikah_cerai
 * @property string|null $kd_punya_kartuid
 * @property string|null $kd_sulit_penglihatan
 * @property string|null $kd_sulit_pendengaran
 * @property string|null $kd_sulit_jalan_naiktangga
 * @property string|null $kd_sulit_gerak_tangan_jari
 * @property string|null $kd_sulit_belajar_intelektual
 * @property string|null $kd_sulit_ingat_konsentrasi
 * @property string|null $kd_sulit_perilaku_emosi
 * @property string|null $kd_sulit_paham_bicara_kom
 * @property string|null $kd_sulit_mandiri
 * @property string|null $kd_sering_sedih_depresi
 * @property string|null $kd_memiliki_perawat
 * @property string|null $kd_merokok_sebulan_akhir
 * @property string|null $kd_penyakit_kronis_menahun
 * @property string|null $kd_partisipasi_sekolah
 * @property string|null $kd_pendidikan_tertinggi
 * @property string|null $kd_kelas_tertinggi
 * @property string|null $kd_ijazah_tertinggi
 * @property string|null $kd_bekerja_seminggu_lalu
 * @property string|null $jumlah_jam_kerja_seminggu_lalu
 * @property int|null $pendapatan_sebulan_terakhir
 * @property string|null $kd_punya_npwp
 * @property string|null $npwp
 * @property string|null $kd_lapangan_usaha_pekerjaan
 * @property string|null $kd_kedudukan_di_pekerjaan
 * @property string|null $kd_gizi_seimbang
 * @property string|null $kd_imunasasi_lengkap
 * @property string|null $kd_bantuan_pempus
 * @property string|null $kd_bantuan_pemkot
 * @property string|null $kd_bantuan_pemdes
 * @property string|null $kd_jamkes_setahun
 * @property string|null $kd_ikut_pbijkn_bpjssehat
 * @property string|null $kd_ikut_bpjssehat_nonpbi
 * @property string|null $kd_ikut_jamsostek_bpjsk
 * @property string|null $kd_ikut_pip
 * @property string|null $kd_ikut_prakerja
 * @property string|null $kd_ikut_kur
 * @property string|null $kd_ikut_umi
 * @property string|null $jumlah_jamket_kerja
 * @property int $is_usaha_sendiri_bersama
 * @property string|null $kd_punya_usaha_sendiri_bersama
 * @property int|null $jumlah_usaha_sendiri_bersama
 * @property string|null $kd_lapangan_usaha_dr_usaha
 * @property string $tulis_lapangan_usaha_dr_usaha
 * @property string $tulis_lapangan_usaha_pekerjaan
 * @property int|null $jumlah_pekerja_dibayar
 * @property int|null $jumlah_pekerja_tidak_dibayar
 * @property string|null $kd_kepemilikan_ijin_usaha
 * @property string|null $kd_omset_usaha_perbulan
 * @property string|null $kd_guna_internet_usaha
 * @property-read \App\Models\Dtks|null $dtks
 * @property-read mixed $umur
 * @property-read \App\Models\Penduduk|null $penduduk
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereBulanMasukRuta($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereBulanMeninggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereBulanPindahTempat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereConfigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereIdDtks($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereIdKeluarga($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereIdPenduduk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereIsUsahaSendiriBersama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereJumlahJamKerjaSemingguLalu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereJumlahJamketKerja($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereJumlahPekerjaDibayar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereJumlahPekerjaTidakDibayar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereJumlahUsahaSendiriBersama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereKdAlasanMasukRuta($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereKdBantuanPemdes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereKdBantuanPemkot($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereKdBantuanPempus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereKdBekerjaSemingguLalu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereKdGiziSeimbang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereKdGunaInternetUsaha($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereKdHubunganDgKk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereKdHubunganDgKrt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereKdIjazahTertinggi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereKdIkutBpjssehatNonpbi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereKdIkutJamsostekBpjsk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereKdIkutKur($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereKdIkutPbijknBpjssehat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereKdIkutPip($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereKdIkutPrakerja($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereKdIkutUmi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereKdImunasasiLengkap($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereKdJamkesSetahun($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereKdJenisKelamin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereKdKedudukanDiPekerjaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereKdKelasTertinggi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereKdKepemilikanIjinUsaha($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereKdKetKeberadaanArt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereKdLapanganUsahaDrUsaha($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereKdLapanganUsahaPekerjaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereKdMemilikiPerawat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereKdMerokokSebulanAkhir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereKdOmsetUsahaPerbulan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereKdPartisipasiSekolah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereKdPendidikanTertinggi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereKdPenyakitKronisMenahun($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereKdPunyaAktaMeniggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereKdPunyaAktanikahCerai($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereKdPunyaKartuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereKdPunyaNpwp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereKdPunyaUsahaSendiriBersama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereKdSeringSedihDepresi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereKdSulitBelajarIntelektual($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereKdSulitGerakTanganJari($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereKdSulitIngatKonsentrasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereKdSulitJalanNaiktangga($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereKdSulitMandiri($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereKdSulitPahamBicaraKom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereKdSulitPendengaran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereKdSulitPenglihatan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereKdSulitPerilakuEmosi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereKdTempatTinggalSaatIni($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereNpwp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota wherePendapatanSebulanTerakhir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereTahunMasukRuta($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereTahunMeninggal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereTahunPindahTempat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereTulisLapanganUsahaDrUsaha($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereTulisLapanganUsahaPekerjaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|DtksAnggota withoutConfigId($configId = '')
 * @mixin \Eloquent
 */
class DtksAnggota extends BaseModel
{
    use ConfigId;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dtks_anggota';

    /**
     * The guarded with the model.
     *
     * @var array
     */
    protected $guarded = [];

    protected $casts = [
        'tgl_lahir'  => 'date:Y-m-d',
        'created_at' => 'date:Y-m-d H:i:s',
        'updated_at' => 'date:Y-m-d H:i:s',
    ];

    /**
     * All of the relationships to be touched.
     *
     * @var array
     */
    protected $touches = ['dtks'];

    /**
     * Define a one-to-one relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function dtks()
    {
        return $this->belongsTo(Dtks::class, 'id_dtks', 'id')->withoutGlobalScope(\App\Scopes\ConfigIdScope::class);
    }

    public function penduduk()
    {
        return $this->belongsTo(Penduduk::class, 'id_penduduk', 'id')->withoutGlobalScope(\App\Scopes\ConfigIdScope::class);
    }

    public function getUmurAttribute()
    {
        return $this->penduduk->umur;
    }
}
