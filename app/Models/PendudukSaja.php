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

namespace App\Models;

use App\Enums\AgamaEnum;
use App\Enums\CacatEnum;
use App\Enums\GolonganDarahEnum;
use App\Enums\JenisKelaminEnum;
use App\Enums\PekerjaanEnum;
use App\Enums\PendidikanKKEnum;
use App\Enums\SHDKEnum;
use App\Enums\StatusKawinEnum;
use App\Enums\StatusPendudukEnum;
use App\Enums\WargaNegaraEnum;

defined('BASEPATH') || exit('No direct script access allowed');

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

    public static function cekTagIdCard($cek = null, $kecuali = null)
    {
        $tagIdCard = self::select('tag_id_card')->when($kecuali, static fn ($q) => $q->where('id', '!=', $kecuali))->whereNotNull('tag_id_card')->pluck('tag_id_card', 'tag_id_card')->toArray();

        return in_array($cek, $tagIdCard);
    }

    public function dataPribadi($id)
    {
        $penduduk                      = self::with(['wilayah', 'keluarga' => static fn ($q) => $q->withOnly([])])->find($id);
        $data                          = $penduduk->toArray();
        $data['hubungan']              = SHDKEnum::valueOf($penduduk->kk_level);
        $data['kepala_kk']             = PendudukSaja::kepalaKeluarga()->where('id_kk', $penduduk->id_kk)->first()?->nama;
        $data['gol_darah']             = GolonganDarahEnum::valueOf($penduduk->golongan_darah_id);
        $data['pendidikan']            = PendidikanKKEnum::valueOf($penduduk->pendidikan_kk_id);
        $data['status']                = StatusPendudukEnum::valueOf($penduduk->status);
        $data['pek']                   = PekerjaanEnum::valueOf($penduduk->pekerjaan_id);
        $data['men']                   = CacatEnum::valueOf($penduduk->cacat_id);
        $data['wn']                    = WargaNegaraEnum::valueOf($penduduk->warganegara_id);
        $data['agama']                 = AgamaEnum::valueOf($penduduk->agama_id);
        $data['rw']                    = $penduduk->wilayah->rw;
        $data['rt']                    = $penduduk->wilayah->rt;
        $data['dusun']                 = $penduduk->wilayah->dusun;
        $data['umur']                  = $penduduk->umur;
        $data['sex']                   = JenisKelaminEnum::valueOf($penduduk->sex);
        $data['alamat']                = $penduduk->keluarga->alamat;
        $data['info_pilihan_penduduk'] = 'NIK: ' . $penduduk->nik . ' - ' . $penduduk->nama . '\nAlamat : RT-' . $penduduk->wilayah->rt . ', RW-' . $penduduk->wilayah->rw . ' ' . $penduduk->wilayah->dusun;
        $data['alamat_wilayah']        = $penduduk->alamat_wilayah;
        $this->formatDataSurat($data);

        return $data;
    }

    public function dataAyah($id)
    {
        $penduduk = self::findOrFail($id);
        $data     = [];
        //cari kepala keluarga pria kalau penduduknya seorang anak dalam keluarga
        if ($penduduk->isAnak()) {
            $data = self::select(['id'])->ayah($penduduk->id_kk)->first()->toArray() ?? [];
        }

        // jika tidak ada Cari berdasarkan ayah_nik
        if (empty($data) && ! empty($penduduk->ayah_nik)) {
            $data = self::select(['id'])->where('nik', $penduduk->ayah_nik)->first()->toArray() ?? [];
        }
        if (isset($data['id'])) {
            $ayahId = $data['id'];

            return $this->dataPribadi($ayahId);
        }

        // Ambil data sebisanya dari data ayah penduduk
        $ayah['nik']  = $penduduk['ayah_nik'];
        $ayah['nama'] = $penduduk['nama_ayah'];

        return $ayah;
    }

    public function dataIbu($id)
    {
        $penduduk = self::findOrFail($id);
        $data     = [];
        //cari kepala keluarga pria kalau penduduknya seorang anak dalam keluarga
        if ($penduduk->isAnak()) {
            $data = self::select(['id'])->ibu($penduduk->id_kk)->first()->toArray() ?? [];
        }

        // jika tidak ada Cari berdasarkan ayah_nik
        if (empty($data) && ! empty($penduduk->ayah_nik)) {
            $data = self::select(['id'])->where('nik', $penduduk->ayah_nik)->first()->toArray() ?? [];
        }
        if (isset($data['id'])) {
            $ibuId = $data['id'];

            return $this->dataPribadi($ibuId);
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
            $penduduk       = self::with(['wilayah', 'keluarga' => static fn ($q) => $q->withOnly([])])->find($id);
            $kepalakeluarga = self::kepalaKeluarga()->where('id_kk', $penduduk->id_kk)->first();
            $data           = $penduduk->toArray();

            $data['gol_darah']       = GolonganDarahEnum::valueOf($penduduk->golongan_darah_id);
            $data['sex']             = JenisKelaminEnum::valueOf($penduduk->sex);
            $data['sex_id']          = $penduduk->sex;
            $data['umur']            = $penduduk->umur;
            $data['status_kawin']    = StatusKawinEnum::valueOf($penduduk->status_kawin);
            $data['status_kawin_id'] = $penduduk->status_kawin;
            $data['warganegara']     = WargaNegaraEnum::valueOf($penduduk->warganegara_id);
            $data['agama']           = AgamaEnum::valueOf($penduduk->agama_id);
            $data['pendidikan']      = PendidikanKKEnum::valueOf($penduduk->pendidikan_kk_id);
            $data['hubungan']        = SHDKEnum::valueOf($penduduk->kk_level);
            $data['pekerjaan']       = PekerjaanEnum::valueOf($penduduk->pekerjaan_id);
            $data['rw']              = $penduduk->wilayah->rw;
            $data['rt']              = $penduduk->wilayah->rt;
            $data['dusun']           = $penduduk->wilayah->dusun;
            $data['alamat']          = $penduduk->keluarga->alamat;
            $data['cacat']           = CacatEnum::valueOf($penduduk->cacat_id);
            $data['nik_kk']          = $kepalakeluarga->nik;
            $data['telepon_kk']      = $kepalakeluarga->telepon;
            $data['email_kk']        = $kepalakeluarga->email;
            $data['kepala_kk']       = $kepalakeluarga->nama;
            $data['bdt']             = Rtm::where('no_kk', $penduduk->id_rtm)->first()?->bdt;
        $data['nik']                 = substr($penduduk->nik, 1, 1) == 0 ? 0 : $penduduk->nik;
        $data['no_kk']               = substr($penduduk->keluarga->no_kk, 1, 1) == 0 ? 0 : $penduduk->keluarga->no_kk;
        $data['alamat_wilayah']      = $penduduk->alamat_wilayah;
        $this->formatDataSurat($data);

        return $data;
    }

    public function scopeListPendudukAjax($query, $cari = '', $filter = [])
    {
        $query->with(['wilayah'])->select(['id', 'nik', 'tag_id_card', 'nama', 'sex', 'id_cluster']);

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
            $query->where('id_kk', static function ($query) use ($filter) {
                $query->select('id_kk')
                    ->from('tweb_penduduk')
                    ->where('id', $filter['hubungan']);
            })->where('id', '!=', $filter['hubungan']);
        }

        // ambil data selain yang dikecualikan
        if ($filter['kecuali']) {
            $query->whereNotIn('id', $filter['kecuali']);
        }

        if ($filter['bersurat']) {
            $query->join('log_surat', 'tweb_penduduk.id', '=', 'log_surat.id_pend');
        }

        if ($cari) {
            $query->where(static function ($query) use ($cari) {
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
