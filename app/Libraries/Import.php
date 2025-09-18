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

namespace App\Libraries;

use App\Enums\AgamaEnum;
use App\Enums\CacatEnum;
use App\Enums\CaraKBEnum;
use App\Enums\GolonganDarahEnum;
use App\Enums\HamilEnum;
use App\Enums\PekerjaanEnum;
use App\Enums\PendidikanKKEnum;
use App\Enums\PendidikanSedangEnum;
use App\Enums\SasaranEnum;
use App\Enums\SHDKEnum;
use App\Enums\StatusDasarEnum;
use App\Enums\StatusKawinEnum;
use App\Enums\WargaNegaraEnum;
use App\Libraries\BIP\Bip;
use App\Models\BantuanPeserta;
use App\Models\Keluarga;
use App\Models\LogKeluarga;
use App\Models\LogPenduduk;
use App\Models\Penduduk;
use App\Models\PendudukAsuransi;
use App\Models\PendudukHubungan;
use App\Models\PendudukSaja;
use App\Models\StatusKtp;
use App\Models\Wilayah;
use Carbon\Carbon;
use DateTimeImmutable;
use Exception;
use Illuminate\Support\Facades\DB;
use OpenSpout\Reader\XLSX\Reader;

class Import
{
    public const DAFTAR_KOLOM = [
        'alamat',
        'dusun',
        'rw',
        'rt',
        'nama',
        'no_kk',
        'nik',
        'sex',
        'tempatlahir',
        'tanggallahir',
        'agama_id',
        'pendidikan_kk_id',
        'pendidikan_sedang_id',
        'pekerjaan_id',
        'status_kawin',
        'kk_level',
        'warganegara_id',
        'ayah_nik',
        'nama_ayah',
        'ibu_nik',
        'nama_ibu',
        'golongan_darah_id',
        'akta_lahir',
        'dokumen_pasport',
        'tanggal_akhir_paspor',
        'dokumen_kitas',
        'akta_perkawinan',
        'tanggalperkawinan',
        'akta_perceraian',
        'tanggalperceraian',
        'cacat_id',
        'cara_kb_id',
        'hamil',
        'ktp_el',
        'status_rekam',
        'alamat_sekarang',
        'status_dasar',
        'suku',
        'tag_id_card',
        'id_asuransi',
        'no_asuransi',
        'lat',
        'lng',
    ];

    protected $kodeSex;
    protected $kodeHubungan;
    protected $kodeAgama;
    protected $kodePendidikanKK;
    protected $kodePendidikanSedang;
    protected $kodePekerjaan;
    protected $kodeStatus;
    protected $kodeGolonganDarah;
    protected $kodeKtpEl;
    protected $kodeStatusRekam;
    protected $kodeStatusDasar;
    protected $kodeCacat;
    protected $kodeCaraKb;
    protected $kodeWargaNegara;
    protected $kodeHamil;
    protected $kodeAsuransi;
    protected $errorTulisPenduduk;
    protected $infoTulisPenduduk;

    public function __construct()
    {
        // Sediakan memory paling sedikit 512M
        preg_match('/^(\d+)(M)$/', ini_get('memory_limit'), $matches);
        $memoryLimit = $matches[1] ?: 0;
        if ($memoryLimit < 512) {
            ini_set('memory_limit', '512M');
        }
        set_time_limit(3600);

        // Data referensi tambahan

        $pendidikan = [
            'Tidak/Blm Sekolah'                => 1,
            'Tidak Tamat SD/Sederajat'         => 2,
            'Akademi/Diploma III/Sarjana Muda' => 7,
            'Strata-II'                        => 9,
        ];

        $status = [
            'BK' => 1,
            'K'  => 2,
            'CH' => 3,
            'CM' => 4,
        ];

        $statusDasar = [
            'PINDAH DALAM NEGERI' => 3,
            'PINDAH LUAR NEGERI'  => 3,
        ];

        $golonganDarah = [
            'Tdk Th' => 13,
        ];

        $this->kodeSex = [
            'laki-laki' => 1,
            'perempuan' => 2,
            'l'         => 1,
            'lk'        => 1,
            'p'         => 2,
            'pr'        => 2,
        ];
        $this->kodeHubungan         = array_change_key_case(PendudukHubungan::pluck('id', 'nama')->toArray());
        $this->kodeAgama            = array_change_key_case(array_combine(AgamaEnum::values(), AgamaEnum::keys()));
        $this->kodePendidikanKK     = array_change_key_case(array_merge(array_combine(PendidikanKKEnum::values(), PendidikanKKEnum::keys()), $pendidikan));
        $this->kodePendidikanSedang = array_change_key_case(array_combine(PendidikanSedangEnum::values(), PendidikanSedangEnum::keys()));
        $this->kodePekerjaan        = array_change_key_case(array_combine(PekerjaanEnum::values(), PekerjaanEnum::keys()));
        $this->kodeStatus           = array_change_key_case(array_merge(array_combine(StatusKawinEnum::values(), StatusKawinEnum::keys()), $status));
        $this->kodeGolonganDarah    = array_change_key_case(array_merge(array_combine(GolonganDarahEnum::values(), GolonganDarahEnum::keys()), $golonganDarah));
        $this->kodeKtpEl            = array_change_key_case(unserialize(KTP_EL));
        $this->kodeStatusRekam      = StatusKtp::selectRaw('lower(nama) as nama, cast(status_rekam as int) as status_rekam')->pluck('status_rekam', 'nama')->toArray();
        $this->kodeStatusDasar      = array_change_key_case(array_merge(array_combine(StatusDasarEnum::values(), StatusDasarEnum::keys()), $statusDasar));
        $this->kodeCacat            = array_change_key_case(array_combine(CacatEnum::values(), CacatEnum::keys()));
        $this->kodeCaraKb           = array_change_key_case(array_combine(CaraKBEnum::values(), CaraKBEnum::keys()));
        $this->kodeWargaNegara      = array_change_key_case(array_combine(WargaNegaraEnum::values(), WargaNegaraEnum::keys()));
        $this->kodeHamil            = array_change_key_case(array_combine(HamilEnum::values(), HamilEnum::keys()));
        $this->kodeAsuransi         = PendudukAsuransi::pluck('id')->all();
    }

    /**
     * ========================================================
     * IMPOR EXCEL
     * ========================================================
     */
    private function fileImportValid()
    {
        // error 1 = UPLOAD_ERR_INI_SIZE; lihat Upload.php
        // TODO: pakai cara upload yg disediakan Codeigniter
        if ($_FILES['userfile']['error'] == 1) {
            $upload_mb = max_upload();
            set_session('error', ' -> Ukuran file melebihi batas ' . $upload_mb . ' MB');

            return false;
        }
        $mime_type_excel = ['application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel.sheet.macroenabled.12', 'application/wps-office.xlsx'];
        if (! in_array(strtolower($_FILES['userfile']['type']), $mime_type_excel)) {
            set_session('error', ' -> Jenis file salah: ' . $_FILES['userfile']['type']);

            return false;
        }

        return true;
    }

    /**
     * Konversi tulisan menjadi kode angka
     *
     * @param array		tulisan => kode angka
     * @param string	tulisan yang akan dikonversi
     * @param mixed $daftar_kode
     * @param mixed $nilai
     *
     * @return int kode angka, -1 kalau tidak ada kodenya
     */
    protected function getKode($daftar_kode, $nilai)
    {
        /*
         *
         * Hapus spasi pada daftar kode
         * Contoh:
         * SLTA / SEDERAJAT => SLTA/SEDERAJAT
         *
         */
        $daftar_kode = array_combine(str_replace(' ', '', array_keys($daftar_kode)), array_values($daftar_kode));

        $nilai = str_replace(' ', '', strtolower($nilai));
        $nilai = preg_replace('/\\s*\\/\\s*/', '/', $nilai);

        if (! empty($nilai) && $nilai != '-' && ! array_key_exists($nilai, $daftar_kode)) {
            return $nilai;
        } // kode salah

        return $daftar_kode[$nilai];
    }

    protected function konversiKode($daftar_kode, $nilai)
    {
        $nilai = trim($nilai);

        if (ctype_digit($nilai)) {
            return $nilai;
        }

        return $this->getKode($daftar_kode, $nilai);
    }

    protected function dataImportValid($isiBaris)
    {
        // Kolom yang harus diisi
        if ($isiBaris['nik'] == '') {
            return 'NIK tidak boleh kosong';
        }

        // Validasi data setiap kolom ber-kode
        if ($isiBaris['sex'] != '' && ! ($isiBaris['sex'] >= 1 && $isiBaris['sex'] <= 2)) {
            return 'kode jenis kelamin ' . $isiBaris['sex'] . '  tidak dikenal';
        }
        if ($isiBaris['agama_id'] != '' && ! ($isiBaris['agama_id'] >= 1 && $isiBaris['agama_id'] <= 7)) {
            return 'kode agama ' . $isiBaris['agama_id'] . '  tidak dikenal';
        }
        if ($isiBaris['pendidikan_kk_id'] != '' && ! ($isiBaris['pendidikan_kk_id'] >= 1 && $isiBaris['pendidikan_kk_id'] <= 10)) {
            return 'kode pendidikan ' . $isiBaris['pendidikan_kk_id'] . '  tidak dikenal';
        }
        if ($isiBaris['pendidikan_sedang_id'] != '' && ! ($isiBaris['pendidikan_sedang_id'] >= 1 && $isiBaris['pendidikan_sedang_id'] <= 18)) {
            return 'kode pendidikan_sedang ' . $isiBaris['pendidikan_sedang_id'] . '  tidak dikenal';
        }
        if ($isiBaris['pekerjaan_id'] != '' && ! ($isiBaris['pekerjaan_id'] >= 1 && $isiBaris['pekerjaan_id'] <= 89)) {
            return 'kode pekerjaan ' . $isiBaris['pekerjaan_id'] . '  tidak dikenal';
        }
        if ($isiBaris['status_kawin'] != '' && ! ($isiBaris['status_kawin'] >= 1 && $isiBaris['status_kawin'] <= 4)) {
            return 'kode status_kawin ' . $isiBaris['status_kawin'] . ' tidak dikenal';
        }
        if ($isiBaris['kk_level'] != '' && ! ($isiBaris['kk_level'] >= 1 && $isiBaris['kk_level'] <= 11)) {
            return 'kode status hubungan ' . $isiBaris['kk_level'] . '  tidak dikenal';
        }
        if ($isiBaris['warganegara_id'] != '' && ! ($isiBaris['warganegara_id'] >= 1 && $isiBaris['warganegara_id'] <= 3)) {
            return 'kode warganegara ' . $isiBaris['warganegara_id'] . '  tidak dikenal';
        }
        if ($isiBaris['golongan_darah_id'] != '' && ! ($isiBaris['golongan_darah_id'] >= 1 && $isiBaris['golongan_darah_id'] <= 13)) {
            return 'kode golongan_darah ' . $isiBaris['golongan_darah_id'] . '  tidak dikenal';
        }
        if ($isiBaris['cacat_id'] != '' && ! ($isiBaris['cacat_id'] >= 1 && $isiBaris['cacat_id'] <= 7)) {
            return 'kode cacat ' . $isiBaris['cacat_id'] . '  tidak dikenal';
        }
        if ($isiBaris['cara_kb_id'] != '' && ! ($isiBaris['cara_kb_id'] >= 1 && $isiBaris['cara_kb_id'] <= 8) && $isiBaris['cara_kb_id'] != '99') {
            return 'kode cara_kb ' . $isiBaris['cara_kb_id'] . '  tidak dikenal';
        }
        if ($isiBaris['hamil'] != '' && ! ($isiBaris['hamil'] >= 1 && $isiBaris['hamil'] <= 2)) {
            return 'kode hamil ' . $isiBaris['hamil'] . '  tidak dikenal';
        }
        if ($isiBaris['ktp_el'] != '' && ! ($isiBaris['ktp_el'] >= 1 && $isiBaris['ktp_el'] <= 2)) {
            return 'kode ktp_el ' . $isiBaris['ktp_el'] . ' tidak dikenal';
        }
        if ($isiBaris['status_rekam'] != '' && ! ($isiBaris['status_rekam'] >= 1 && $isiBaris['status_rekam'] <= 8)) {
            return 'kode status_rekam ' . $isiBaris['status_rekam'] . ' tidak dikenal';
        }
        if ($isiBaris['status_dasar'] != '' && ! in_array($isiBaris['status_dasar'], [1, 2, 3, 4, 6, 9])) {
            return 'kode status_dasar ' . $isiBaris['status_dasar'] . ' tidak dikenal';
        }

        if ($isiBaris['id_asuransi'] != '' && ! in_array($isiBaris['id_asuransi'], $this->kodeAsuransi)) {
            return 'kode asuransi tidak dikenal';
        }

        if ($isiBaris['tag_id_card'] != '' && (strlen($isiBaris['tag_id_card']) < 10 || strlen($isiBaris['tag_id_card']) > 17)) {
            return 'Panjang karakter tag id card minimal 10 karakter dan maksimal 17 karakter';
        }

        if ($isiBaris['lat'] != '' && (strlen($isiBaris['lat']) < 2 || strlen($isiBaris['lat']) > 24)) {
            return 'Panjang karakter lat minimal 2 karakter dan maksimal 24 karakter';
        }

        if ($isiBaris['lng'] != '' && (strlen($isiBaris['lng']) < 2 || strlen($isiBaris['lng']) > 24)) {
            return 'Panjang karakter lng minimal 2 karakter dan maksimal 24 karakter';
        }

        // Validasi data lain
        if (empty($isiBaris['tanggallahir'])) {
            return 'Tanggal lahir tidak boleh kosong';
        }

        if (! $this->cekValidasiTanggal($isiBaris['tanggallahir'])) {
            return 'Tanggal lahir (' . $isiBaris['tanggallahir'] . ') tidak valid. Format tanggal harus dd-mm-yyyy';
        }

        if (! empty($isiBaris['tanggalperkawinan']) && ! $this->cekValidasiTanggal($isiBaris['tanggalperkawinan'])) {
            return 'Tanggal perkawinan (' . $isiBaris['tanggalperkawinan'] . ') tidak valid. Format tanggal harus dd-mm-yyyy';
        }

        if (! empty($isiBaris['tanggalperceraian']) && ! $this->cekValidasiTanggal($isiBaris['tanggalperceraian'])) {
            return 'Tanggal perceraian (' . $isiBaris['tanggalperceraian'] . ') tidak valid. Format tanggal harus dd-mm-yyyy';
        }

        if (! ctype_digit($isiBaris['nik']) || (strlen($isiBaris['nik']) != 16 && $isiBaris['nik'] != '0')) {
            return 'NIK salah';
        }

        if (! ctype_digit($isiBaris['no_kk']) || strlen($isiBaris['no_kk']) != 16) {
            return 'Nomor KK salah';
        }

        if ($isiBaris['nama'] != '' && cekNama($isiBaris['nama'])) {
            return 'Nama hanya boleh berisi karakter alpha, spasi, titik, koma, tanda petik dan strip';
        }

        if ($isiBaris['ayah_nik'] != '' && (! ctype_digit($isiBaris['ayah_nik']) || (strlen($isiBaris['ayah_nik']) != 16 && $isiBaris['ayah_nik'] != '0'))) {
            return 'NIK ayah salah';
        }

        if ($isiBaris['nama_ayah'] != '' && cekNama($isiBaris['nama_ayah'])) {
            return 'Nama ayah hanya boleh berisi karakter alpha, spasi, titik, koma, tanda petik dan strip';
        }

        if ($isiBaris['ibu_nik'] != '' && (! ctype_digit($isiBaris['ibu_nik']) || (strlen($isiBaris['ibu_nik']) != 16 && $isiBaris['ibu_nik'] != '0'))) {
            return 'NIK ibu salah';
        }
        if ($isiBaris['nama_ibu'] == '') {
            return '';
        }
        if (! cekNama($isiBaris['nama_ibu'])) {
            return '';
        }

        return 'Nama ibu hanya boleh berisi karakter alpha, spasi, titik, koma, tanda petik dan strip';
    }

    protected function formatTanggal($kolom_tanggal)
    {
        try {
            return Carbon::parse($kolom_tanggal)->format('Y-m-d');
        } catch (Exception $e) {
            log_message('error', 'Format tanggal (' . $kolom_tanggal . ') tidak valid. Format tanggal harus dd-mm-yyyy');

            return false;
        }
    }

    protected function cekValidasiTanggal($tanggal)
    {
        try {
            $date = Carbon::createFromFormat('Y-m-d', $tanggal);

            return $date && $date->format('Y-m-d') === $tanggal;
        } catch (Exception $e) {
            return false;
        }
    }

    private function cekKosong($isi)
    {
        if ($isi instanceof DateTimeImmutable) {
            return $isi->format('Y-m-d');
        }

        $isi = trim($isi);

        return (in_array($isi, ['', '-'])) ? null : $isi;
    }

    private function getIsiBaris($kolom, $rowData)
    {
        $kolom              = array_flip(array_filter($kolom, 'strlen'));
        $isiBaris['alamat'] = trim($rowData[$kolom['alamat']]);
        $dusun              = ltrim(trim($rowData[$kolom['dusun']]), "'");
        $dusun              = str_replace('_', ' ', $dusun);
        $dusun              = strtoupper($dusun);
        $dusun              = str_replace('DUSUN ', '', $dusun);
        $isiBaris['dusun']  = $dusun;

        $isiBaris['rw']   = ltrim(trim($rowData[$kolom['rw']]), "'");
        $isiBaris['rt']   = ltrim(trim($rowData[$kolom['rt']]), "'");
        $nama             = trim($rowData[$kolom['nama']]);
        $nama             = preg_replace('/[^a-zA-Z0-9,\.\']/', ' ', $nama);
        $isiBaris['nama'] = $nama;

        // Data Disdukcapil adakalanya berisi karakter tambahan pada no_kk dan nik
        // yang tidak tampak (non-printable characters),
        // jadi perlu dibuang
        $no_kk             = trim($rowData[$kolom['no_kk']]);
        $no_kk             = preg_replace('/[^0-9]/', '', $no_kk);
        $isiBaris['no_kk'] = $no_kk;

        $nik             = trim($rowData[$kolom['nik']]);
        $nik             = preg_replace('/[^0-9]/', '', $nik);
        $isiBaris['nik'] = $nik;

        $isiBaris['sex']                  = $this->konversiKode($this->kodeSex, $rowData[$kolom['sex']]);
        $isiBaris['tempatlahir']          = $this->cekKosong($rowData[$kolom['tempatlahir']]);
        $isiBaris['tanggallahir']         = $this->cekKosong($this->formatTanggal($rowData[$kolom['tanggallahir']]));
        $isiBaris['agama_id']             = $this->konversiKode($this->kodeAgama, $rowData[$kolom['agama_id']]);
        $isiBaris['pendidikan_kk_id']     = $this->konversiKode($this->kodePendidikanKK, $rowData[$kolom['pendidikan_kk_id']]);
        $isiBaris['pendidikan_sedang_id'] = $this->konversiKode($this->kodePendidikanSedang, $rowData[$kolom['pendidikan_sedang_id']]);
        $isiBaris['pekerjaan_id']         = $this->konversiKode($this->kodePekerjaan, $rowData[$kolom['pekerjaan_id']]);
        $isiBaris['status_kawin']         = $this->konversiKode($this->kodeStatus, $rowData[$kolom['status_kawin']]);
        $isiBaris['kk_level']             = $this->konversiKode($this->kodeHubungan, $rowData[$kolom['kk_level']]);
        $isiBaris['warganegara_id']       = $this->konversiKode($this->kodeWargaNegara, $rowData[$kolom['warganegara_id']]);
        $isiBaris['nama_ayah']            = $this->cekKosong($rowData[$kolom['nama_ayah']]);
        $isiBaris['nama_ibu']             = $this->cekKosong($rowData[$kolom['nama_ibu']]);
        $isiBaris['golongan_darah_id']    = $this->konversiKode($this->kodeGolonganDarah, $rowData[$kolom['golongan_darah_id']]);
        $isiBaris['akta_lahir']           = $this->cekKosong($rowData[$kolom['akta_lahir']]);
        $isiBaris['dokumen_pasport']      = $this->cekKosong($rowData[$kolom['dokumen_pasport']]);
        $isiBaris['tanggal_akhir_paspor'] = $this->cekKosong($this->formatTanggal($rowData[$kolom['tanggal_akhir_paspor']]));
        $isiBaris['dokumen_kitas']        = $this->cekKosong($rowData[$kolom['dokumen_kitas']]);
        $isiBaris['ayah_nik']             = $this->cekKosong($rowData[$kolom['ayah_nik']]);
        $isiBaris['ibu_nik']              = $this->cekKosong($rowData[$kolom['ibu_nik']]);
        $isiBaris['akta_perkawinan']      = $this->cekKosong($rowData[$kolom['akta_perkawinan']]);
        $isiBaris['tanggalperkawinan']    = $this->cekKosong($this->formatTanggal($rowData[$kolom['tanggalperkawinan']]));
        $isiBaris['akta_perceraian']      = $this->cekKosong($rowData[$kolom['akta_perceraian']]);
        $isiBaris['tanggalperceraian']    = $this->cekKosong($this->formatTanggal($rowData[$kolom['tanggalperceraian']]));
        $isiBaris['cacat_id']             = $this->konversiKode($this->kodeCacat, $rowData[$kolom['cacat_id']]);
        $isiBaris['cara_kb_id']           = $this->konversiKode($this->kodeCaraKb, $rowData[$kolom['cara_kb_id']]);
        $isiBaris['hamil']                = $this->konversiKode($this->kodeHamil, $rowData[$kolom['hamil']]);
        $isiBaris['ktp_el']               = $this->konversiKode($this->kodeKtpEl, $rowData[$kolom['ktp_el']]);
        $isiBaris['status_rekam']         = $this->konversiKode($this->kodeStatusRekam, $rowData[$kolom['status_rekam']]);
        $isiBaris['alamat_sekarang']      = $this->cekKosong($rowData[$kolom['alamat_sekarang']]);
        $isiBaris['status_dasar']         = $this->konversiKode($this->kodeStatusDasar, $rowData[$kolom['status_dasar']]);
        $isiBaris['suku']                 = $this->cekKosong($rowData[$kolom['suku']]);
        $isiBaris['tag_id_card']          = $this->cekKosong($rowData[$kolom['tag_id_card']]);
        $isiBaris['id_asuransi']          = $this->konversiKode($this->kodeAsuransi, $rowData[$kolom['id_asuransi']]);
        $isiBaris['no_asuransi']          = $this->cekKosong($rowData[$kolom['no_asuransi']]);
        $isiBaris['lat']                  = $this->cekKosong($rowData[$kolom['lat']]);
        $isiBaris['lng']                  = $this->cekKosong($rowData[$kolom['lng']]);

        return $isiBaris;
    }

    protected function tulisWilayah(&$isiBaris)
    {
        // Masukkan wilayah administratif ke tabel tweb_wil_clusterdesa apabila
        // wilayah administratif ini belum ada

        // --- Masukkan dusun apabila belum ada
        $belumAdaDusun = Wilayah::where('dusun', $isiBaris['dusun'])->doesntExist();
        if ($belumAdaDusun) {
            $dusun = [
                [
                    'dusun'     => $isiBaris['dusun'],
                    'rw'        => 0,
                    'rt'        => 0,
                    'config_id' => identitas('id'),
                ],
                [
                    'dusun'     => $isiBaris['dusun'],
                    'rw'        => '-',
                    'rt'        => 0,
                    'config_id' => identitas('id'),
                ],
                [
                    'dusun'     => $isiBaris['dusun'],
                    'rw'        => '-',
                    'rt'        => '-',
                    'config_id' => identitas('id'),
                ],
            ];

            $hasil = Wilayah::insert($dusun);
        }

        // --- Masukkan rw apabila belum ada
        $belumAdaRw = Wilayah::where('dusun', $isiBaris['dusun'])->where('rw', $isiBaris['rw'])->doesntExist();
        if ($belumAdaRw) {
            $rw = [
                [
                    'dusun'     => $isiBaris['dusun'],
                    'rw'        => $isiBaris['rw'],
                    'rt'        => 0,
                    'config_id' => identitas('id'),
                ],
                [
                    'dusun'     => $isiBaris['dusun'],
                    'rw'        => $isiBaris['rw'],
                    'rt'        => '-',
                    'config_id' => identitas('id'),
                ],
            ];

            $hasil = Wilayah::insert($rw);
        }

        // --- Masukkan rt apabila belum ada
        $cekRt = Wilayah::where('dusun', $isiBaris['dusun'])->where('rw', $isiBaris['rw'])->where('rt', $isiBaris['rt'])->first();
        if (! $cekRt) {
            $rt = [
                'dusun'     => $isiBaris['dusun'],
                'rw'        => $isiBaris['rw'],
                'rt'        => $isiBaris['rt'],
                'config_id' => identitas('id'),
            ];

            $rt                     = Wilayah::create($rt);
            $isiBaris['id_cluster'] = $rt->id;
        } else {
            $isiBaris['id_cluster'] = $cekRt->id;
        }
    }

    protected function tulisKeluarga(&$isiBaris)
    {
        // Penduduk dengan no_kk kosong adalah penduduk lepas
        if ($isiBaris['no_kk'] == '') {
            return false;
        }
        // Masukkan keluarga ke tabel tweb_keluarga apabila
        // keluarga ini belum ada
        $keluargaBaru = false;

        $keluarga = Keluarga::select('id')->where(['no_kk' => $isiBaris['no_kk']])->first();

        $data['updated_by'] = ci_auth()->id;
        $data['id_cluster'] = $isiBaris['id_cluster'];
        $data['config_id']  = identitas('id');

        if ($keluarga) {
            // Update keluarga apabila sudah ada
            $isiBaris['id_kk'] = $keluarga->id;
            // Hanya update apabila alamat kosong
            // karena alamat keluarga akan diupdate menggunakan data kepala keluarga di tulis_tweb_pendududk
            if (! $keluarga->alamat) {
                $keluarga->alamat = $isiBaris['alamat'];
                $keluarga->save();
            }
        } else {
            $data['no_kk']     = $isiBaris['no_kk'];
            $data['alamat']    = $isiBaris['alamat'];
            $keluarga          = Keluarga::create($data);
            $isiBaris['id_kk'] = $keluarga->id;
            $keluargaBaru      = true;

            // Tulis Log Keluarga Baru
            $log_keluarga = [
                'id_kk'           => $isiBaris['id_kk'],
                'id_peristiwa'    => 1,
                'tgl_peristiwa'   => date('Y-m-d H:i:s'),
                'id_pend'         => null,
                'id_log_penduduk' => null,
                'updated_by'      => auth()->id,
            ];

            LogKeluarga::create($log_keluarga);
        }

        return $keluargaBaru;
    }

    protected function tulisPenduduk($isiBaris)
    {
        $this->errorTulisPenduduk = null;
        $this->infoTulisPenduduk  = [];

        $data = [];

        // Siapkan data penduduk
        $kolomBaris = DB::connection()->getSchemaBuilder()->getColumnListing('tweb_penduduk');

        foreach ($kolomBaris as $kolom) {
            if (! empty($isiBaris[$kolom])) {
                $data[$kolom] = $isiBaris[$kolom];
            }
        }

        $data['status'] = '1';  // penduduk impor dianggap aktif

        // Jangan masukkan atau update isian yang kosong
        foreach ($data as $key => $value) {
            if (empty($value)) {
                if (! ($key == 'nik' && $value == '0')) {
                    unset($data[$key]);
                } // Kecuali untuk kolom NIk boleh 0
            }
        }
        // jika kk_level adalah kepala keluarga maka periksa,
        // apakah sudah ada kepala_keluarga untuk keluarga tersebut ?
        // maka ganti menjadi lainnya
        if ($data['kk_level'] == SHDKEnum::KEPALA_KELUARGA) {
            $adaKepalaKeluarga = PendudukSaja::where(['id_kk' => $isiBaris['id_kk'], 'kk_level' => SHDKEnum::KEPALA_KELUARGA])->first();
            if ($adaKepalaKeluarga) {
                $data['kk_level']                   = SHDKEnum::LAINNYA;
                $this->infoTulisPenduduk['message'] = 'Status SHDK pada NIK : ' . $data['nik'] . ' diubah menjadi ' . SHDKEnum::valueOf(SHDKEnum::LAINNYA) . ' karena dalam keluarga tersebut sudah ada kepala keluarga';
            }
        }
        // Masukkan penduduk ke tabel tweb_penduduk apabila
        // penduduk ini belum ada
        // Penduduk dianggap baru apabila NIK tidak diketahui (nilai 0)
        $pendudukBaru = false;
        if ($isiBaris['nik'] == 0) {
            // Update penduduk NIK sementara dengan ketentuan
            // 1. Cek nama
            // 2. Cek tempat lahir
            // 3. Cek tgl lahir
            // Jika ke 3 data tsb sama, maka data sebelumnya dianggap sama, selain itu dianggap penduduk yg berbeda/baru
            $cekData         = PendudukSaja::where(['nama' => $isiBaris['nama'], 'tempatlahir' => $isiBaris['tempatlahir'], 'tanggallahir' => $isiBaris['tanggallahir']])->first();
            $isiBaris['nik'] = $cekData->nik ?? Penduduk::nikSementara();
        }

        // Hamil hanya untuk jenis kelamin perempuan (2)
        if ($data['sex'] == '1') {
            unset($data['hamil']);
        }

        $res = PendudukSaja::where(['nik' => $isiBaris['nik']])->first();
        if ($res) {
            // Abaikan status dasar
            if ($data['status_dasar'] != '' && $data['status_dasar'] != $res['status_dasar']) {
                return $this->errorTulisPenduduk['message'] = 'Tidak dapat mengubah status dasar dengan nik ' . $data['nik'] . ' karena telah terdaftar';
            }

            // Abaikan shdk
            if ($data['kk_level'] != '' && $data['kk_level'] != $res['kk_level']) {
                return $this->errorTulisPenduduk['message'] = 'Tidak dapat mengubah status hubungan dengan nik ' . $data['nik'] . ' karena telah terdaftar';
            }

            // Abaikan no kk
            $keluarga = Keluarga::where(['id' => $res['id_kk']])->first();
            if ($isiBaris['no_kk'] != '' && $isiBaris['no_kk'] != $keluarga['no_kk']) {
                return $this->errorTulisPenduduk['message'] = 'Tidak dapat mengubah nomor kk dengan nik ' . $data['nik'] . ' karena telah terdaftar';
            }

            // Abaikan alamat
            $keluarga = Keluarga::where(['id' => $res['id_kk']])->first();
            if ($isiBaris['alamat'] != '' && $isiBaris['alamat'] != $keluarga['alamat']) {
                return $this->errorTulisPenduduk['message'] = 'Tidak dapat mengubah alamat dengan nik ' . $data['nik'] . ' karena telah terdaftar';
            }

            // Abaikan dusun
            $cluster = Wilayah::where(['id' => $keluarga['id_cluster']])->first();
            if ($isiBaris['dusun'] != '' && $isiBaris['dusun'] != $cluster['dusun']) {
                return $this->errorTulisPenduduk['message'] = 'Tidak dapat mengubah dusun dengan nik ' . $data['nik'] . ' karena telah terdaftar';
            }

            // Abaikan rw
            $cluster = Wilayah::where(['id' => $keluarga['id_cluster']])->first();
            if ($isiBaris['rw'] != '' && $isiBaris['rw'] != $cluster['rw']) {
                return $this->errorTulisPenduduk['message'] = 'Tidak dapat mengubah rw dengan nik ' . $data['nik'] . ' karena telah terdaftar';
            }

            // Abaikan rt
            $cluster = Wilayah::where(['id' => $keluarga['id_cluster']])->first();
            if ($isiBaris['rt'] != '' && $isiBaris['rt'] != $cluster['rt']) {
                return $this->errorTulisPenduduk['message'] = 'Tidak dapat mengubah rt dengan nik ' . $data['nik'] . ' karena telah terdaftar';
            }

            if ($data['status_dasar'] != -1) {
                if (PendudukSaja::cekTagIdCard($data['tag_id_card'], $res['id'])) {
                    return $this->errorTulisPenduduk['message'] = 'Tag ID Card ' . $data['tag_id_card'] . ' sudah digunakan pada NIK : ' . $data['nik'];
                }

                $data['nik'] = $res['nik'];

                // Hanya update apabila status dasar valid (data SIAK)
                $data['updated_at'] = date('Y-m-d H:i:s');
                $data['updated_by'] = ci_auth()->id;

                try {
                    PendudukSaja::where('id', $res['id'])->update($data);
                } catch (Exception $e) {
                    $this->errorTulisPenduduk = $e->getMessage();
                }
            }
            $pendudukBaru = $res['id'];
        } else {
            if (setting('tgl_data_lengkap_aktif') != 0) {
                return $this->errorTulisPenduduk['message'] = 'Tidak dapat menambahkan penduduk dengan nik ' . $data['nik'] . ' karena data sudah ditetapkan lengkap';
            }

            if ($data['nama'] == '' || $isiBaris['no_kk'] == '' || $data['kk_level'] == '' || $isiBaris['dusun'] == '' || $isiBaris['rt'] == '' || $isiBaris['rw'] == '') {
                return $this->errorTulisPenduduk['message'] = 'nama, nomor kk, shdk, dusun, rt, rw harus diisi untuk penduduk baru';
            }

            if (PendudukSaja::cekTagIdCard($data['tag_id_card'])) {
                return $this->errorTulisPenduduk['message'] = 'Tag ID Card ' . $data['tag_id_card'] . ' sudah digunakan pada NIK : ' . $data['nik'];
            }

            // Konfersi nik 0 sesuai format nik sementara
            $data['nik'] = $isiBaris['nik'];

            if ($data['status_dasar'] == -1) {
                $data['status_dasar'] = 9;
            } // Tidak Valid
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = ci_auth()->id;
            $data['config_id']  = identitas('id');

            try {
                $pendudukBaru = PendudukSaja::create($data)->id;
            } catch (Exception $e) {
                $this->errorTulisPenduduk['message'] = $e->getMessage();
            }

            // Insert ke log_penduduk pada penduduk baru
            $kode_peristiwa = $data['status_dasar'];
            if ($data['status_dasar'] == 1 || $data['status_dasar'] == 9) {
                $kode_peristiwa = 5;
            }

            $log['tgl_peristiwa']  = $data['created_at'];
            $log['kode_peristiwa'] = $kode_peristiwa;
            $log['tgl_lapor']      = $data['created_at'];
            $log['id_pend']        = $pendudukBaru;
            $log['created_by']     = $data['created_by'];
            $log['config_id']      = identitas('id');
            LogPenduduk::upsert($log, ['config_id', 'id_pend', 'kode_peristiwa', 'tgl_peristiwa']);
        }

        // Tambah atau perbarui lokasi penduduk
        $this->pendudukMap($pendudukBaru, $isiBaris['lat'], $isiBaris['lng']);

        // Update nik_kepala dan id_cluster di keluarga apabila baris ini kepala keluarga
        // dan sudah ada NIK
        if ($data['kk_level'] == SHDKEnum::KEPALA_KELUARGA) {
            Keluarga::where('id', $data['id_kk'])
                ->update([
                    'nik_kepala' => $pendudukBaru,
                    'id_cluster' => $isiBaris['id_cluster'],
                    'alamat'     => $isiBaris['alamat'],
                ]);
        }

        return $pendudukBaru;
    }

    private function pendudukMap($id = 0, $lat = null, $lng = null)
    {
        if ($lat === null || $lng === null) {
            return false;
        }

        // Ubah data penduduk map
        DB::table('tweb_penduduk_map')->updateOrInsert([
            'id' => $id,
        ], [
            'lat' => $lat,
            'lng' => $lng,
        ]);
    }

    private function hapusDataPenduduk(): void
    {
        $tabelPenduduk = ['tweb_wil_clusterdesa', 'tweb_keluarga', 'tweb_penduduk', 'log_keluarga', 'log_penduduk', 'log_perubahan_penduduk', 'log_surat', 'tweb_rtm'];

        foreach ($tabelPenduduk as $tabel) {
            DB::table($tabel)->where('config_id', identitas('id'))->delete();
        }

        // Hapus peserta bantuan dengan sasaran penduduk, keluarga, rumah tangga, kelompok
        BantuanPeserta::whereIn('program_id', static fn ($q) => $q->select(['id'])->from('program')->whereIn('sasaran', [SasaranEnum::PENDUDUK, SasaranEnum::KELUARGA, SasaranEnum::RUMAH_TANGGA, SasaranEnum::KELOMPOK]))->delete();
    }

    public function imporExcel($hapus = false)
    {
        try {
            if ($this->fileImportValid() == false) {
                return;
            }

            $reader = new Reader();
            // $reader->setShouldPreserveEmptyRows(true);
            $reader->open($_FILES['userfile']['tmp_name']);

            // Pengguna bisa menentukan apakah data penduduk yang ada dihapus dulu
            // atau tidak sebelum melakukan impor
            if ($hapus && PendudukSaja::bolehHapusPenduduk()) {
                $this->hapusDataPenduduk();
            }

            foreach ($reader->getSheetIterator() as $sheet) {
                $gagal        = 0;
                $ganda        = 0;
                $pesan        = '';
                $barisData    = 0;
                $barisPertama = false;
                $dataPenduduk = [];
                $daftarKolom  = [];

                if ($sheet->getName() == 'Data Penduduk') {

                    $dataExcel = collect($sheet->getRowIterator())->map(static fn ($row) => collect($row->getCells())->map(static fn ($cell) => $cell->getValue()))
                        ->chunk(500)
                        ->toArray();
                    DB::statement('SET character_set_connection = utf8');
                    DB::statement('SET character_set_client = utf8');

                    foreach ($dataExcel as $row) {
                        foreach ($row as $rowData) {
                            $barisData++;

                            // Baris kedua = '###' menunjukkan telah sampai pada baris data terakhir
                            if ($rowData[1] == '###') {
                                break;
                            }

                            // Baris pertama diabaikan, berisi nama kolom
                            if (! $barisPertama) {
                                $barisPertama = true;
                                $daftarKolom  = $rowData;

                                foreach ($daftarKolom as $kolom) {
                                    if (! in_array($kolom, self::DAFTAR_KOLOM)) {
                                        return set_session('error', 'Data penduduk gagal diimpor, nama kolom ' . $kolom . ' tidak sesuai.');
                                    }
                                }

                                continue;
                            }

                            $isiBaris      = $this->getIsiBaris($daftarKolom, $rowData);
                            $errorValidasi = $this->dataImportValid($isiBaris);
                            if (empty($errorValidasi)) {
                                $this->tulisWilayah($isiBaris);
                                $this->tulisKeluarga($isiBaris);
                                // Untuk pesan jika data yang sama akan diganti
                                if ($index = array_search($isiBaris['nik'], $dataPenduduk) && $isiBaris['nik'] != '0') {
                                    $ganda++;
                                    $pesan .= $barisData . ') NIK ' . $isiBaris['nik'] . ' sama dengan baris ' . ($index + 2) . '<br>';
                                }
                                $dataPenduduk[] = $isiBaris['nik'];
                                $this->tulisPenduduk($isiBaris);
                                if ($error = $this->errorTulisPenduduk) {
                                    $gagal++;
                                    $pesan .= $barisData . ') ' . $error['message'] . '<br>';
                                }
                                if ($this->infoTulisPenduduk) {
                                    $pesan .= $barisData . ') ' . $this->infoTulisPenduduk['message'] . '<br>';
                                }
                            } else {
                                $gagal++;
                                $pesan .= $barisData . ') ' . $errorValidasi . '<br>';
                            }
                        }
                    }
                    // Hapus data lat dan lng yang null
                    DB::table('tweb_penduduk_map')->orWhereNull(['id', 'lat', 'lng'])->delete();

                    if (($barisData - 1) <= 0) {
                        return set_session('error', 'Data penduduk gagal diimpor');
                    }

                    $pesan_impor = [
                        'gagal'  => $gagal,
                        'ganda'  => $ganda,
                        'pesan'  => $pesan,
                        'sukses' => ($barisData - 1) - $gagal,
                    ];

                    set_session('pesan_impor', $pesan_impor);
                }
            }
            $reader->close();

            return set_session('success', 'Data penduduk berhasil diimpor');
        } catch (Exception $e) {
            log_message('error', $e->getMessage());

            return set_session('error', 'Data penduduk gagal diimpor.');
        }
    }

    /*
     * ====================
     * Selesai IMPOR EXCEL
     * ====================
    */

    public function imporBip($hapus = false)
    {
        try {
            if ($this->fileImportValid() == false) {
                return;
            }

            $data = new SpreadsheetExcelReader($_FILES['userfile']['tmp_name']);

            DB::statement('SET character_set_connection = utf8');
            DB::statement('SET character_set_client = utf8');

            // Pengguna bisa menentukan apakah data penduduk yang ada dihapus dulu
            // atau tidak sebelum melakukan impor
            if ($hapus) {
                $this->hapusDataPenduduk();
            }

            $bip = new Bip($data);
            $bip->imporBip();
        } catch (Exception $e) {
            log_message('error', $e->getMessage());

            return set_session('error', 'Data penduduk gagal diimpor.');
        }
    }
}
