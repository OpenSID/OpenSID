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

use App\Enums\StatusPendudukEnum;

defined('BASEPATH') || exit('No direct script access allowed');

/**
 * @property int $id
 * @property int $config_id
 * @property string $nama
 * @property string $nik
 * @property int|null $id_kk
 * @property int $kk_level
 * @property string|null $id_rtm
 * @property int|null $rtm_level
 * @property int $sex
 * @property string $tempatlahir
 * @property \Illuminate\Support\Carbon $tanggallahir
 * @property int $agama_id
 * @property int $pendidikan_kk_id
 * @property int|null $pendidikan_sedang_id
 * @property int $pekerjaan_id
 * @property int $status_kawin
 * @property int $warganegara_id
 * @property string $dokumen_pasport
 * @property string $dokumen_kitas
 * @property string|null $ayah_nik
 * @property string|null $ibu_nik
 * @property string $nama_ayah
 * @property string $nama_ibu
 * @property string|null $foto
 * @property string $golongan_darah_id
 * @property int|null $id_cluster
 * @property int|null $status
 * @property string|null $alamat_sebelumnya
 * @property string|null $alamat_sekarang
 * @property int $status_dasar
 * @property int|null $hamil
 * @property int|null $cacat_id
 * @property int|null $sakit_menahun_id
 * @property string|null $akta_lahir
 * @property string|null $akta_perkawinan
 * @property string|null $tanggalperkawinan
 * @property string|null $akta_perceraian
 * @property string|null $tanggalperceraian
 * @property int|null $cara_kb_id
 * @property string|null $telepon
 * @property string|null $tanggal_akhir_paspor
 * @property string|null $no_kk_sebelumnya
 * @property int|null $ktp_el
 * @property int|null $status_rekam
 * @property string|null $waktu_lahir
 * @property int|null $tempat_dilahirkan
 * @property int|null $jenis_kelahiran
 * @property int|null $kelahiran_anak_ke
 * @property int|null $penolong_kelahiran
 * @property int|null $berat_lahir
 * @property string|null $panjang_lahir
 * @property string|null $tag_id_card
 * @property int|null $id_asuransi
 * @property string|null $no_asuransi
 * @property int|null $status_asuransi
 * @property string|null $email
 * @property string|null $email_token
 * @property string|null $email_tgl_kadaluarsa
 * @property string|null $email_tgl_verifikasi
 * @property string|null $telegram
 * @property string|null $telegram_token
 * @property string|null $telegram_tgl_kadaluarsa
 * @property string|null $telegram_tgl_verifikasi
 * @property int|null $bahasa_id
 * @property string|null $ket
 * @property bool $is_historical
 * @property string|null $negara_asal
 * @property string|null $tempat_cetak_ktp
 * @property string|null $tanggal_cetak_ktp
 * @property string|null $suku
 * @property string|null $marga
 * @property string|null $adat
 * @property string|null $pekerja_migran
 * @property string|null $bpjs_ketenagakerjaan
 * @property string|null $hubung_warga
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property int|null $created_by
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $updated_by
 * @property-read \App\Models\Wilayah|null $Wilayah
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Activity> $activities
 * @property-read int|null $activities_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Bantuan> $bantuan
 * @property-read int|null $bantuan_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Dokumen> $dokumen
 * @property-read int|null $dokumen_count
 * @property-read string $agama
 * @property-read mixed $alamat_wilayah
 * @property-read mixed $alamat_wilayah_kartu_keluarga
 * @property-read string $asuransi
 * @property-read string $bahasa
 * @property-read string $cacat
 * @property-read string $cara_kb
 * @property-read string $di_lahirkan
 * @property-read string $golongan_darah
 * @property-read string $jenis_kelamin
 * @property-read mixed $jenis_kelamin_id
 * @property-read string $jenis_kelamin_inisial
 * @property-read string $jenis_lahir
 * @property-read string $jml_anak
 * @property-read mixed $lokasi
 * @property-read string $nama_asuransi
 * @property-read string $pekerjaan
 * @property-read mixed $pendidikan
 * @property-read mixed $pendidikan_k_k
 * @property-read mixed $pendidikan_sedang
 * @property-read string $penduduk_hubungan
 * @property-read string $penduduk_status
 * @property-read string $penolong_lahir
 * @property-read mixed $sakit_menahun
 * @property-read string $status_hamil
 * @property-read string $status_perkawinan
 * @property-read mixed $umur
 * @property-read string $url_foto
 * @property-read string $usia
 * @property-read string $wajib_k_t_p
 * @property-read string $warganegara
 * @property-read \App\Models\KIA|null $kia_anak
 * @property-read \App\Models\KIA|null $kia_ibu
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LogPenduduk> $log
 * @property-read int|null $log_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LogSurat> $logSurat
 * @property-read int|null $log_surat_count
 * @property-read \App\Models\LogPenduduk|null $log_latest
 * @property-read \App\Models\PendudukMandiri|null $mandiri
 * @property-read \App\Models\PendudukMap|null $map
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \App\Models\Pamong|null $pamong
 * @property-read \App\Models\Pamong|null $pamongUser
 * @property-read \App\Models\User|null $pembuat
 * @property-read \App\Models\User|null $pengubah
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\PesanMandiri> $pesan
 * @property-read int|null $pesan_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BantuanPeserta> $pesertaBantuan
 * @property-read int|null $peserta_bantuan_count
 * @property-read \App\Models\Rtm|null $rtm
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja ayah($idKk)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja batasiUmur($tglPemilihan, $umurObj = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja dpt($tglPemilihan = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja dusun($dusun = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja eksporData()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja filterLog(array $filters)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja filters(array $filters = [], array $allowedFilters = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja hidup(int $value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja hubungWarga()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja ibu($idKk)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja kepalaKeluarga()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja listPendudukAjax($cari = '', $filter = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja listPendudukBersuratAjax($cari = '')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja orderKeluarga()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja status($value = 1)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja statusDasar(array $value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja statusPenduduk($value = 1)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja urut()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja wajibKtp()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereAdat($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereAgamaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereAktaLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereAktaPerceraian($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereAktaPerkawinan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereAlamatSebelumnya($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereAlamatSekarang($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereAyahNik($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereBahasaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereBeratLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereBpjsKetenagakerjaan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereCacatId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereCaraKbId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereConfigId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereDokumenKitas($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereDokumenPasport($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereEmailTglKadaluarsa($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereEmailTglVerifikasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereEmailToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereFoto($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereGolonganDarahId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereHamil($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereHubungWarga($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereIbuNik($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereIdAsuransi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereIdCluster($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereIdKk($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereIdRtm($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereIsHistorical($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereJenisKelahiran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereKelahiranAnakKe($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereKet($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereKkLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereKtpEl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereMarga($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereNama($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereNamaAyah($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereNamaIbu($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereNegaraAsal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereNik($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereNoAsuransi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereNoKkSebelumnya($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja wherePanjangLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja wherePekerjaMigran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja wherePekerjaanId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja wherePendidikanKkId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja wherePendidikanSedangId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja wherePenolongKelahiran($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereRtmLevel($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereSakitMenahunId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereSex($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereStatusAsuransi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereStatusDasar($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereStatusKawin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereStatusRekam($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereSuku($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereTagIdCard($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereTanggalAkhirPaspor($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereTanggalCetakKtp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereTanggallahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereTanggalperceraian($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereTanggalperkawinan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereTelegram($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereTelegramTglKadaluarsa($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereTelegramTglVerifikasi($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereTelegramToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereTelepon($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereTempatCetakKtp($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereTempatDilahirkan($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereTempatlahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereWaktuLahir($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja whereWarganegaraId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PendudukSaja withoutConfigId($configId = '')
 * @property-read \App\Models\Keluarga|null $keluarga
 * @property-read \App\Models\LogPenduduk|null $logMati
 * @mixin \Eloquent
 */
class PendudukSaja extends Penduduk
{
   protected $appends = [];
   protected $with    = [];

    /** Tidak boleh menghapus data penduduk jika:
     * dalam demo_mode, atau
     * status penduduk sudah lengkap
     * tidak ada lagi data tweb_penduduk contoh awal (created_by = -1)
     */
    public static function bolehHapusPenduduk()
    {
        $data_awal = self::where('created_by', '<=', 0)->count();
        if (config_item('demo_mode') || $data_awal == 0) {
            return false;
        }

        return ! setting('tgl_data_lengkap_aktif');
    }

    public static function cekTagIdCard($cek = null, $kecuali = null): bool
    {
        $tagIdCard = self::select('tag_id_card')->when($kecuali, static fn ($q) => $q->where('id', '!=', $kecuali))->whereNotNull('tag_id_card')->pluck('tag_id_card', 'tag_id_card')->toArray();

        return in_array($cek, $tagIdCard);
    }

    public function dataPribadi($id)
    {
        $penduduk                      = self::with(['wilayah', 'keluarga' => static fn ($q) => $q->withOnly([])])->find($id);
        $data                          = $penduduk->toArray();
        $data['hubungan']              = $penduduk->penduduk_hubungan;
        $data['kepala_kk']             = PendudukSaja::kepalaKeluarga()->where('id_kk', $penduduk->id_kk)->first()?->nama;
        $data['gol_darah']             = $penduduk->golongan_darah;
        $data['pendidikan']            = $penduduk->pendidikan_kk;
        $data['status']                = StatusPendudukEnum::valueOf($penduduk->status);
        $data['pek']                   = $penduduk->pekerjaan;
        $data['men']                   = $penduduk->cacat;
        $data['wn']                    = $penduduk->warganegara;
        $data['agama']                 = $penduduk->agama;
        $data['rw']                    = $penduduk->wilayah->rw;
        $data['rt']                    = $penduduk->wilayah->rt;
        $data['dusun']                 = $penduduk->wilayah->dusun;
        $data['umur']                  = $penduduk->umur;
        $data['sex']                   = $penduduk->jenis_kelamin;
        $data['alamat']                = $penduduk->keluarga->alamat;
        $data['info_pilihan_penduduk'] = 'NIK: ' . $penduduk->nik . ' - ' . $penduduk->nama . '\nAlamat : RT-' . $penduduk->wilayah->rt . ', RW-' . $penduduk->wilayah->rw . ' ' . $penduduk->wilayah->dusun;
        $data['alamat_wilayah']        = $penduduk->alamat_wilayah;
        $this->formatDataSurat($data);

        return $data;
    }

    public function dataAyah($id, $fallbackId = null)
    {
        $penduduk = self::findOrFail($id);
        $data     = [];
        //cari kepala keluarga pria kalau penduduknya seorang anak dalam keluarga
        if ($penduduk->isAnak()) {
            $data = self::select(['id'])->ayah($penduduk->id_kk)->first()?->toArray() ?? [];
        }

        // jika tidak ada Cari berdasarkan ayah_nik
        if (empty($data) && ! empty($penduduk->ayah_nik)) {
            $data = self::select(['id'])->where('nik', $penduduk->ayah_nik)->first()?->toArray() ?? [];
        }

        if (isset($data['id'])) {
            $ayahId = $data['id'];

            return $this->dataPribadi($ayahId);
        }

        if ($fallbackId) {
            return $this->dataPribadi($fallbackId);
        }

        // Ambil data sebisanya dari data ayah penduduk
        $ayah['nik']  = $penduduk['ayah_nik'];
        $ayah['nama'] = $penduduk['nama_ayah'];

        return $ayah;
    }

    public function dataIbu($id, $fallbackId = null)
    {
        $penduduk = self::findOrFail($id);
        $data     = [];
        //cari kepala keluarga pria kalau penduduknya seorang anak dalam keluarga
        if ($penduduk->isAnak()) {
            $data = self::select(['id'])->ibu($penduduk->id_kk)->first()?->toArray() ?? [];
        }

        // jika tidak ada Cari berdasarkan ibu_nik
        if (empty($data) && ! empty($penduduk->ibu_nik)) {
            $data = self::select(['id'])->where('nik', $penduduk->ibu_nik)->first()?->toArray() ?? [];
        }

        if (isset($data['id'])) {
            $ibuId = $data['id'];

            return $this->dataPribadi($ibuId);
        }

        if ($fallbackId) {
            return $this->dataPribadi($fallbackId);
        }

        // Ambil data sebisanya dari data ayah penduduk
        $ibu['nik']  = $penduduk['ibu_nik'];
        $ibu['nama'] = $penduduk['nama_ibu'];

        return $ibu;
    }

    public function formatDataSurat(&$data): void
    {
        // Asumsi kolom "alamat_wilayah" sdh dalam format ucwords
        $kolomUpper = ['tanggallahir', 'tempatlahir', 'dusun', 'pekerjaan', 'gol_darah', 'agama', 'sex',
            'status_kawin', 'pendidikan', 'hubungan', 'nama_ayah', 'nama_ibu', 'alamat', 'alamat_sebelumnya',
            'cacat', ];

        foreach ($kolomUpper as $kolom) {
            if (isset($data[$kolom])) {
                $data[$kolom] = set_ucwords($data[$kolom]);
            }
        }
        if (isset($data['pendidikan'])) {
            $data['pendidikan'] = kasus_lain('pendidikan', $data['pendidikan']);
        }

        if (isset($data['pekerjaan'])) {
            $data['pekerjaan'] = kasus_lain('pekerjaan', $data['pekerjaan']);
        }
    }

    // TODO: Ganti cara mengambil data kk, pisahkan dalam variabel lain
    public function dataSurat($id)
    {
        $penduduk       = self::with(['wilayah', 'keluarga' => static fn ($q) => $q->withOnly([])])?->find($id);
        $kepalakeluarga = self::kepalaKeluarga()->where('id_kk', $penduduk?->id_kk)?->first();
        $data           = $penduduk?->toArray();

        $data['gol_darah']       = $penduduk->golongan_darah;
        $data['sex']             = $penduduk->jenis_kelamin;
        $data['sex_id']          = $penduduk->sex;
        $data['umur']            = $penduduk?->umur;
        $data['status_kawin']    = $penduduk->status_perkawinan;
        $data['status_kawin_id'] = $penduduk->status_perkawinan;
        $data['warganegara']     = $penduduk->warganegara;
        $data['agama']           = $penduduk->agama;
        $data['pendidikan']      = $penduduk->pendidikan_kk;
        $data['hubungan']        = $penduduk->penduduk_hubungan;
        $data['pekerjaan']       = $penduduk->pekerjaan;
        $data['rw']              = $penduduk?->wilayah?->rw;
        $data['rt']              = $penduduk?->wilayah?->rt;
        $data['dusun']           = $penduduk?->wilayah?->dusun;
        $data['alamat']          = $penduduk?->keluarga?->alamat;
        $data['cacat']           = $penduduk->cacat;
        $data['nik_kk']          = $kepalakeluarga?->nik;
        $data['telepon_kk']      = $kepalakeluarga?->telepon;
        $data['email_kk']        = $kepalakeluarga?->email;
        $data['kepala_kk']       = $kepalakeluarga?->nama;
        $data['bdt']             = Rtm::where('no_kk', $penduduk?->id_rtm)->first()?->bdt;
        $data['nik']             = substr($penduduk?->nik, 1, 1) == 0 ? 0 : $penduduk?->nik;
        $data['no_kk']           = substr($penduduk?->keluarga?->no_kk, 1, 1) == 0 ? 0 : $penduduk?->keluarga?->no_kk;
        $data['alamat_wilayah']  = $penduduk?->alamat_wilayah;

        if ($penduduk) {
            $this->formatDataSurat($data);
        }

        return $data;
    }

    public function scopeListPendudukAjax($query, $cari = '', $filter = [])
    {
        $query->with(['wilayah'])->select(['tweb_penduduk.id', 'tweb_penduduk.nik', 'tweb_penduduk.tag_id_card', 'tweb_penduduk.nama', 'tweb_penduduk.sex', 'tweb_penduduk.id_cluster']);

        if ($filter['sex']) {
            $query->where('sex', $filter['sex']);
        }

        if ((is_array($filter['status_dasar']) && $filter['status_dasar'])) {
            $query->whereIn('status_dasar', $filter['status_dasar']);
        }

        if ((is_array($filter['kk_level']) && $filter['kk_level'])) {
            $query->whereIn('kk_level', $filter['kk_level']);
        }

        // batasi ambil data dari keluarga yang sama saja
        if ($filter['hubungan']) {
            $query->where('id_kk', static function ($query) use ($filter): void {
                $query->select('id_kk')
                    ->from('tweb_penduduk')
                    ->where('id', $filter['hubungan']);
            })->where('tweb_penduduk.id', '!=', $filter['hubungan']);
        }

        // ambil data selain yang dikecualikan
        if ($filter['kecuali']) {
            $query->whereNotIn('tweb_penduduk.id', $filter['kecuali']);
        }

        if ($filter['bersurat']) {
            $query->join('log_surat', 'tweb_penduduk.id', '=', 'log_surat.id_pend')
                ->groupBy([
                    'tweb_penduduk.id',
                    'tweb_penduduk.nik',
                    'tweb_penduduk.tag_id_card',
                    'tweb_penduduk.nama',
                    'tweb_penduduk.sex',
                    'tweb_penduduk.id_cluster',
                ]);
        }

        if ($cari) {
            $query->where(static function ($query) use ($cari): void {
                $query->where('tweb_penduduk.nik', 'like', "%{$cari}%")
                    ->orWhere('tweb_penduduk.nama', 'like', "%{$cari}%")
                    ->orWhere('tweb_penduduk.tag_id_card', 'like', "%{$cari}%");
            });
        }

        return $query;
    }

    public function scopeListPendudukBersuratAjax($query, $cari = '')
    {
        $filter = ['bersurat' => true];

        return $this->scopeListPendudukAjax($query, $cari, $filter);
    }
}
