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
 * Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

defined('BASEPATH') || exit('No direct script access allowed');

use App\Models\LogPenduduk;
use App\Models\Penduduk;
use App\Models\PendudukAsuransi;
use Illuminate\Support\Facades\DB;
use OpenSpout\Reader\Common\Creator\ReaderEntityFactory;

class Impor_model extends MY_Model
{
    public $error_tulis_penduduk; // error pada pemanggilan terakhir tulis_tweb_penduduk()
    public $daftar_kolom = [
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

    public function __construct()
    {
        parent::__construct();
        // Sediakan memory paling sedikit 512M
        preg_match('/^(\d+)(M)$/', ini_get('memory_limit'), $matches);
        $memory_limit = $matches[1] ?: 0;
        if ($memory_limit < 512) {
            ini_set('memory_limit', '512M');
        }
        set_time_limit(3600);
        $this->load->model(['referensi_model', 'penduduk_model']);
        $this->load->library('Spreadsheet_Excel_Reader');

        // Data referensi tambahan
        $sex = [
            'L'  => 1,
            'LK' => 1,
            'P'  => 2,
            'Pr' => 2,
        ];

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

        $status_dasar = [
            'PINDAH DALAM NEGERI' => 3,
            'PINDAH LUAR NEGERI'  => 3,
        ];

        $golongan_darah = [
            'Tdk Th' => 13,
        ];

        $this->kode_sex               = $this->referensi_model->impor_list_data('tweb_penduduk_sex', $sex);
        $this->kode_hubungan          = $this->referensi_model->impor_list_data('tweb_penduduk_hubungan');
        $this->kode_agama             = $this->referensi_model->impor_list_data('tweb_penduduk_agama');
        $this->kode_pendidikan_kk     = $this->referensi_model->impor_list_data('tweb_penduduk_pendidikan_kk', $pendidikan);
        $this->kode_pendidikan_sedang = $this->referensi_model->impor_list_data('tweb_penduduk_pendidikan');
        $this->kode_pekerjaan         = $this->referensi_model->impor_list_data('tweb_penduduk_pekerjaan');
        $this->kode_status            = $this->referensi_model->impor_list_data('tweb_penduduk_kawin', $status);
        $this->kode_golongan_darah    = $this->referensi_model->impor_list_data('tweb_golongan_darah', $golongan_darah);
        $this->kode_ktp_el            = array_change_key_case(unserialize(KTP_EL));
        $this->kode_status_rekam      = array_flip($this->referensi_model->list_status_rekam());
        $this->kode_status_dasar      = $this->referensi_model->impor_list_data('tweb_status_dasar', $status_dasar);
        $this->kode_cacat             = $this->referensi_model->impor_list_data('tweb_cacat');
        $this->kode_cara_kb           = $this->referensi_model->impor_list_data('tweb_cara_kb');
        $this->kode_warganegara       = $this->referensi_model->impor_list_data('tweb_penduduk_warganegara');
        $this->kode_hamil             = $this->referensi_model->impor_list_data('ref_penduduk_hamil');
        $this->kode_asuransi          = PendudukAsuransi::pluck('id')->all();
        $this->logpenduduk            = new LogPenduduk();
    }

    /**
     * ========================================================
     * IMPOR EXCEL
     * ========================================================
     */
    private function file_import_valid()
    {
        // error 1 = UPLOAD_ERR_INI_SIZE; lihat Upload.php
        // TODO: pakai cara upload yg disediakan Codeigniter
        if ($_FILES['userfile']['error'] == 1) {
            $upload_mb = max_upload();
            set_session('error', ' -> Ukuran file melebihi batas ' . $upload_mb . ' MB');

            return false;
        }
        $mime_type_excel = ['application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel.sheet.macroenabled.12'];
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
    protected function get_kode($daftar_kode, $nilai)
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

    protected function get_konversi_kode($daftar_kode, $nilai)
    {
        $nilai = trim($nilai);

        if (ctype_digit($nilai)) {
            return $nilai;
        }

        return $this->get_kode($daftar_kode, $nilai);
    }

    protected function data_import_valid($isi_baris)
    {
        // Kolom yang harus diisi
        if ($isi_baris['nik'] == '') {
            return 'NIK tidak boleh kosong';
        }

        // Validasi data setiap kolom ber-kode
        if ($isi_baris['sex'] != '' && ! ($isi_baris['sex'] >= 1 && $isi_baris['sex'] <= 2)) {
            return 'kode jenis kelamin ' . $isi_baris['sex'] . '  tidak dikenal';
        }
        if ($isi_baris['agama_id'] != '' && ! ($isi_baris['agama_id'] >= 1 && $isi_baris['agama_id'] <= 7)) {
            return 'kode agama ' . $isi_baris['agama_id'] . '  tidak dikenal';
        }
        if ($isi_baris['pendidikan_kk_id'] != '' && ! ($isi_baris['pendidikan_kk_id'] >= 1 && $isi_baris['pendidikan_kk_id'] <= 10)) {
            return 'kode pendidikan ' . $isi_baris['pendidikan_kk_id'] . '  tidak dikenal';
        }
        if ($isi_baris['pendidikan_sedang_id'] != '' && ! ($isi_baris['pendidikan_sedang_id'] >= 1 && $isi_baris['pendidikan_sedang_id'] <= 18)) {
            return 'kode pendidikan_sedang ' . $isi_baris['pendidikan_sedang_id'] . '  tidak dikenal';
        }
        if ($isi_baris['pekerjaan_id'] != '' && ! ($isi_baris['pekerjaan_id'] >= 1 && $isi_baris['pekerjaan_id'] <= 89)) {
            return 'kode pekerjaan ' . $isi_baris['pekerjaan_id'] . '  tidak dikenal';
        }
        if ($isi_baris['status_kawin'] != '' && ! ($isi_baris['status_kawin'] >= 1 && $isi_baris['status_kawin'] <= 4)) {
            return 'kode status_kawin ' . $isi_baris['status_kawin'] . ' tidak dikenal';
        }
        if ($isi_baris['kk_level'] != '' && ! ($isi_baris['kk_level'] >= 1 && $isi_baris['kk_level'] <= 11)) {
            return 'kode status hubungan ' . $isi_baris['kk_level'] . '  tidak dikenal';
        }
        if ($isi_baris['warganegara_id'] != '' && ! ($isi_baris['warganegara_id'] >= 1 && $isi_baris['warganegara_id'] <= 3)) {
            return 'kode warganegara ' . $isi_baris['warganegara_id'] . '  tidak dikenal';
        }
        if ($isi_baris['golongan_darah_id'] != '' && ! ($isi_baris['golongan_darah_id'] >= 1 && $isi_baris['golongan_darah_id'] <= 13)) {
            return 'kode golongan_darah ' . $isi_baris['golongan_darah_id'] . '  tidak dikenal';
        }
        if ($isi_baris['cacat_id'] != '' && ! ($isi_baris['cacat_id'] >= 1 && $isi_baris['cacat_id'] <= 7)) {
            return 'kode cacat ' . $isi_baris['cacat_id'] . '  tidak dikenal';
        }
        if ($isi_baris['cara_kb_id'] != '' && ! ($isi_baris['cara_kb_id'] >= 1 && $isi_baris['cara_kb_id'] <= 8) && $isi_baris['cara_kb_id'] != '99') {
            return 'kode cara_kb ' . $isi_baris['cara_kb_id'] . '  tidak dikenal';
        }
        if ($isi_baris['hamil'] != '' && ! ($isi_baris['hamil'] >= 1 && $isi_baris['hamil'] <= 2)) {
            return 'kode hamil ' . $isi_baris['hamil'] . '  tidak dikenal';
        }
        if ($isi_baris['ktp_el'] != '' && ! ($isi_baris['ktp_el'] >= 1 && $isi_baris['ktp_el'] <= 2)) {
            return 'kode ktp_el ' . $isi_baris['ktp_el'] . ' tidak dikenal';
        }
        if ($isi_baris['status_rekam'] != '' && ! ($isi_baris['status_rekam'] >= 1 && $isi_baris['status_rekam'] <= 8)) {
            return 'kode status_rekam ' . $isi_baris['status_rekam'] . ' tidak dikenal';
        }
        if ($isi_baris['status_dasar'] != '' && ! in_array($isi_baris['status_dasar'], [1, 2, 3, 4, 6, 9])) {
            return 'kode status_dasar ' . $isi_baris['status_dasar'] . ' tidak dikenal';
        }

        if ($isi_baris['id_asuransi'] != '' && ! in_array($isi_baris['id_asuransi'], $this->kode_asuransi)) {
            return 'kode asuransi tidak dikenal';
        }

        if ($isi_baris['tag_id_card'] != '' && (strlen($isi_baris['tag_id_card']) < 10 || strlen($isi_baris['tag_id_card']) > 17)) {
            return 'Panjang karakter tag id card minimal 10 karakter dan maksimal 17 karakter';
        }

        if ($isi_baris['lat'] != '' && (strlen($isi_baris['lat']) < 2 || strlen($isi_baris['lat']) > 24)) {
            return 'Panjang karakter lat minimal 2 karakter dan maksimal 24 karakter';
        }

        if ($isi_baris['lng'] != '' && (strlen($isi_baris['lng']) < 2 || strlen($isi_baris['lng']) > 24)) {
            return 'Panjang karakter lng minimal 2 karakter dan maksimal 24 karakter';
        }

        // Validasi data lain
        if (! ctype_digit($isi_baris['nik']) || (strlen($isi_baris['nik']) != 16 && $isi_baris['nik'] != '0')) {
            return 'NIK salah';
        }

        if (! ctype_digit($isi_baris['no_kk']) || strlen($isi_baris['no_kk']) != 16) {
            return 'Nomor KK salah';
        }

        if ($isi_baris['nama'] != '' && cekNama($isi_baris['nama'])) {
            return 'Nama hanya boleh berisi karakter alpha, spasi, titik, koma, tanda petik dan strip';
        }

        if ($isi_baris['ayah_nik'] != '' && (! ctype_digit($isi_baris['ayah_nik']) || (strlen($isi_baris['ayah_nik']) != 16 && $isi_baris['ayah_nik'] != '0'))) {
            return 'NIK ayah salah';
        }

        if ($isi_baris['nama_ayah'] != '' && cekNama($isi_baris['nama_ayah'])) {
            return 'Nama ayah hanya boleh berisi karakter alpha, spasi, titik, koma, tanda petik dan strip';
        }

        if ($isi_baris['ibu_nik'] != '' && (! ctype_digit($isi_baris['ibu_nik']) || (strlen($isi_baris['ibu_nik']) != 16 && $isi_baris['ibu_nik'] != '0'))) {
            return 'NIK ibu salah';
        }
        if ($isi_baris['nama_ibu'] == '') {
            return '';
        }
        if (! cekNama($isi_baris['nama_ibu'])) {
            return '';
        }

        return 'Nama ibu hanya boleh berisi karakter alpha, spasi, titik, koma, tanda petik dan strip';
    }

    protected function format_tanggal($kolom_tanggal)
    {
        // spout mengambil kolom tanggal sebagai DateTime object
        if (is_a($kolom_tanggal, 'DateTime')) {
            return $kolom_tanggal->format('Y-m-d');
        }
        $tanggal = ltrim(trim($kolom_tanggal), "'");
        if (strlen($tanggal) == 0) {
            return $tanggal;
        }

        // Ganti separator tanggal supaya tanggal diproses sebagai dd-mm-YYYY.
        // Kalau pakai '/', strtotime memrosesnya sebagai mm/dd/YYYY.
        // Lihat panduan strtotime: http://php.net/manual/en/function.strtotime.php
        $tanggal = str_replace('/', '-', $tanggal);

        return date('Y-m-d', strtotime($tanggal));
    }

    private function cek_kosong($isi)
    {
        $isi = trim($isi);

        return (in_array($isi, ['', '-'])) ? null : $isi;
    }

    private function get_isi_baris($kolom, $rowData)
    {
        $kolom               = array_flip(array_filter($kolom, 'strlen'));
        $isi_baris['alamat'] = trim($rowData[$kolom['alamat']]);
        $dusun               = ltrim(trim($rowData[$kolom['dusun']]), "'");
        $dusun               = str_replace('_', ' ', $dusun);
        $dusun               = strtoupper($dusun);
        $dusun               = str_replace('DUSUN ', '', $dusun);
        $isi_baris['dusun']  = $dusun;

        $isi_baris['rw']   = ltrim(trim($rowData[$kolom['rw']]), "'");
        $isi_baris['rt']   = ltrim(trim($rowData[$kolom['rt']]), "'");
        $nama              = trim($rowData[$kolom['nama']]);
        $nama              = preg_replace('/[^a-zA-Z0-9,\.\']/', ' ', $nama);
        $isi_baris['nama'] = $nama;

        // Data Disdukcapil adakalanya berisi karakter tambahan pada no_kk dan nik
        // yang tidak tampak (non-printable characters),
        // jadi perlu dibuang
        $no_kk              = trim($rowData[$kolom['no_kk']]);
        $no_kk              = preg_replace('/[^0-9]/', '', $no_kk);
        $isi_baris['no_kk'] = $no_kk;

        $nik              = trim($rowData[$kolom['nik']]);
        $nik              = preg_replace('/[^0-9]/', '', $nik);
        $isi_baris['nik'] = $nik;

        $isi_baris['sex']                  = $this->get_konversi_kode($this->kode_sex, $rowData[$kolom['sex']]);
        $isi_baris['tempatlahir']          = $this->cek_kosong($rowData[$kolom['tempatlahir']]);
        $isi_baris['tanggallahir']         = $this->cek_kosong($this->format_tanggal($rowData[$kolom['tanggallahir']]));
        $isi_baris['agama_id']             = $this->get_konversi_kode($this->kode_agama, $rowData[$kolom['agama_id']]);
        $isi_baris['pendidikan_kk_id']     = $this->get_konversi_kode($this->kode_pendidikan_kk, $rowData[$kolom['pendidikan_kk_id']]);
        $isi_baris['pendidikan_sedang_id'] = $this->get_konversi_kode($this->kode_pendidikan_sedang, $rowData[$kolom['pendidikan_sedang_id']]);
        $isi_baris['pekerjaan_id']         = $this->get_konversi_kode($this->kode_pekerjaan, $rowData[$kolom['pekerjaan_id']]);
        $isi_baris['status_kawin']         = $this->get_konversi_kode($this->kode_status, $rowData[$kolom['status_kawin']]);
        $isi_baris['kk_level']             = $this->get_konversi_kode($this->kode_hubungan, $rowData[$kolom['kk_level']]);
        $isi_baris['warganegara_id']       = $this->get_konversi_kode($this->kode_warganegara, $rowData[$kolom['warganegara_id']]);
        $isi_baris['nama_ayah']            = $this->cek_kosong($rowData[$kolom['nama_ayah']]);
        $isi_baris['nama_ibu']             = $this->cek_kosong($rowData[$kolom['nama_ibu']]);
        $isi_baris['golongan_darah_id']    = $this->get_konversi_kode($this->kode_golongan_darah, $rowData[$kolom['golongan_darah_id']]);
        $isi_baris['akta_lahir']           = $this->cek_kosong($rowData[$kolom['akta_lahir']]);
        $isi_baris['dokumen_pasport']      = $this->cek_kosong($rowData[$kolom['dokumen_pasport']]);
        $isi_baris['tanggal_akhir_paspor'] = $this->cek_kosong($this->format_tanggal($rowData[$kolom['tanggal_akhir_paspor']]));
        $isi_baris['dokumen_kitas']        = $this->cek_kosong($rowData[$kolom['dokumen_kitas']]);
        $isi_baris['ayah_nik']             = $this->cek_kosong($rowData[$kolom['ayah_nik']]);
        $isi_baris['ibu_nik']              = $this->cek_kosong($rowData[$kolom['ibu_nik']]);
        $isi_baris['akta_perkawinan']      = $this->cek_kosong($rowData[$kolom['akta_perkawinan']]);
        $isi_baris['tanggalperkawinan']    = $this->cek_kosong($this->format_tanggal($rowData[$kolom['tanggalperkawinan']]));
        $isi_baris['akta_perceraian']      = $this->cek_kosong($rowData[$kolom['akta_perceraian']]);
        $isi_baris['tanggalperceraian']    = $this->cek_kosong($this->format_tanggal($rowData[$kolom['tanggalperceraian']]));
        $isi_baris['cacat_id']             = $this->get_konversi_kode($this->kode_cacat, $rowData[$kolom['cacat_id']]);
        $isi_baris['cara_kb_id']           = $this->get_konversi_kode($this->kode_cara_kb, $rowData[$kolom['cara_kb_id']]);
        $isi_baris['hamil']                = $this->get_konversi_kode($this->kode_hamil, $rowData[$kolom['hamil']]);
        $isi_baris['ktp_el']               = $this->get_konversi_kode($this->kode_ktp_el, $rowData[$kolom['ktp_el']]);
        $isi_baris['status_rekam']         = $this->get_konversi_kode($this->kode_status_rekam, $rowData[$kolom['status_rekam']]);
        $isi_baris['alamat_sekarang']      = $this->cek_kosong($rowData[$kolom['alamat_sekarang']]);
        $isi_baris['status_dasar']         = $this->get_konversi_kode($this->kode_status_dasar, $rowData[$kolom['status_dasar']]);
        $isi_baris['suku']                 = $this->cek_kosong($rowData[$kolom['suku']]);
        $isi_baris['tag_id_card']          = $this->cek_kosong($rowData[$kolom['tag_id_card']]);
        $isi_baris['id_asuransi']          = $this->get_konversi_kode($this->kode_asuransi, $rowData[$kolom['id_asuransi']]);
        $isi_baris['no_asuransi']          = $this->cek_kosong($rowData[$kolom['no_asuransi']]);
        $isi_baris['lat']                  = $this->cek_kosong($rowData[$kolom['lat']]);
        $isi_baris['lng']                  = $this->cek_kosong($rowData[$kolom['lng']]);

        return $isi_baris;
    }

    protected function tulis_tweb_wil_clusterdesa(&$isi_baris)
    {
        // Masukkan wilayah administratif ke tabel tweb_wil_clusterdesa apabila
        // wilayah administratif ini belum ada

        // --- Masukkan dusun apabila belum ada
        $cek_dusun = $this->config_id()->select('id')->where('dusun', $isi_baris['dusun'])->get('tweb_wil_clusterdesa')->row_array();
        if ($cek_dusun === null) {
            $dusun = [
                [
                    'dusun'     => $isi_baris['dusun'],
                    'rw'        => 0,
                    'rt'        => 0,
                    'config_id' => $this->config_id,
                ],
                [
                    'dusun'     => $isi_baris['dusun'],
                    'rw'        => '-',
                    'rt'        => 0,
                    'config_id' => $this->config_id,
                ],
                [
                    'dusun'     => $isi_baris['dusun'],
                    'rw'        => '-',
                    'rt'        => '-',
                    'config_id' => $this->config_id,
                ],
            ];

            $hasil = $this->db->insert_batch('tweb_wil_clusterdesa', $dusun);
        }

        // --- Masukkan rw apabila belum ada
        $cek_rw = $this->config_id()->select('id')->where('dusun', $isi_baris['dusun'])->where('rw', $isi_baris['rw'])->get('tweb_wil_clusterdesa')->row_array();
        if ($cek_rw === null) {
            $rw = [
                [
                    'dusun'     => $isi_baris['dusun'],
                    'rw'        => $isi_baris['rw'],
                    'rt'        => 0,
                    'config_id' => $this->config_id,
                ],
                [
                    'dusun'     => $isi_baris['dusun'],
                    'rw'        => $isi_baris['rw'],
                    'rt'        => '-',
                    'config_id' => $this->config_id,
                ],
            ];

            $hasil = $this->db->insert_batch('tweb_wil_clusterdesa', $rw);
        }

        // --- Masukkan rt apabila belum ada
        $cek_rt = $this->config_id()->select('id')->where('dusun', $isi_baris['dusun'])->where('rw', $isi_baris['rw'])->where('rt', $isi_baris['rt'])->get('tweb_wil_clusterdesa')->row_array();
        if ($cek_rt === null) {
            $rt = [
                'dusun'     => $isi_baris['dusun'],
                'rw'        => $isi_baris['rw'],
                'rt'        => $isi_baris['rt'],
                'config_id' => $this->config_id,
            ];

            $hasil                   = $this->db->insert('tweb_wil_clusterdesa', $rt);
            $isi_baris['id_cluster'] = $this->db->insert_id();
        } else {
            $isi_baris['id_cluster'] = $cek_rt['id'];
        }
    }

    protected function tulis_tweb_keluarga(&$isi_baris)
    {
        // Penduduk dengan no_kk kosong adalah penduduk lepas
        if ($isi_baris['no_kk'] == '') {
            return false;
        }
        // Masukkan keluarga ke tabel tweb_keluarga apabila
        // keluarga ini belum ada
        $keluarga_baru = false;

        $keluarga_id = $this->config_id()
            ->select('id')
            ->get_where('tweb_keluarga', ['no_kk' => $isi_baris['no_kk']])
            ->row()
            ->id;

        $data['updated_by'] = auth()->id;
        $data['id_cluster'] = $isi_baris['id_cluster'];
        $data['config_id']  = $this->config_id;

        if ($keluarga_id) {
            // Update keluarga apabila sudah ada
            $isi_baris['id_kk'] = $keluarga_id;
            $this->db->where('id', $keluarga_id);
            // Hanya update apabila alamat kosong
            // karena alamat keluarga akan diupdate menggunakan data kepala keluarga di tulis_tweb_pendududk
            $this->db->where('alamat', null);
            $data['alamat'] = $isi_baris['alamat'];
            $this->db->update('tweb_keluarga', $data);
        } else {
            $data['no_kk']  = $isi_baris['no_kk'];
            $data['alamat'] = $isi_baris['alamat'];

            $this->db->insert('tweb_keluarga', $data);
            $isi_baris['id_kk'] = $this->db->insert_id();
            $keluarga_baru      = true;

            // Tulis Log Keluarga Baru
            $this->load->model('keluarga_model');
            $this->keluarga_model->log_keluarga($isi_baris['id_kk'], 1);
        }

        return $keluarga_baru;
    }

    protected function tulis_tweb_penduduk($isi_baris)
    {
        $this->load->model('penduduk_model');
        $this->error_tulis_penduduk = null;

        $data = [];

        // Siapkan data penduduk
        $kolom_baris = $this->db->field_data('tweb_penduduk');

        foreach ($kolom_baris as $kolom) {
            if (! empty($isi_baris[$kolom->name])) {
                $data[$kolom->name] = $isi_baris[$kolom->name];
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
        // Masukkan penduduk ke tabel tweb_penduduk apabila
        // penduduk ini belum ada
        // Penduduk dianggap baru apabila NIK tidak diketahui (nilai 0)
        $penduduk_baru = false;
        if ($isi_baris['nik'] == 0) {
            // Update penduduk NIK sementara dengan ketentuan
            // 1. Cek nama
            // 2. Cek tempat lahir
            // 3. Cek tgl lahir
            // Jika ke 3 data tsb sama, maka data sebelumnya dianggap sama, selain itu dianggap penduduk yg berbeda/baru
            $cek_data         = $this->config_id()->get_where('tweb_penduduk', ['nama' => $isi_baris['nama'], 'tempatlahir' => $isi_baris['tempatlahir'], 'tanggallahir' => $isi_baris['tanggallahir']])->row_array();
            $isi_baris['nik'] = $cek_data['nik'] ?? $this->penduduk_model->nik_sementara();
        }

        // Hamil hanya untuk jenis kelamin perempuan (2)
        if ($data['sex'] == '1') {
            unset($data['hamil']);
        }

        $res = $this->config_id()->get_where('tweb_penduduk', ['nik' => $isi_baris['nik']])->row_array();
        if ($res) {
            // Abaikan status dasar
            if ($data['status_dasar'] != '' && $data['status_dasar'] != $res['status_dasar']) {
                return $this->error_tulis_penduduk['message'] = 'Tidak dapat mengubah status dasar dengan nik ' . $data['nik'] . ' karena telah terdaftar';
            }

            // Abaikan shdk
            if ($data['kk_level'] != '' && $data['kk_level'] != $res['kk_level']) {
                return $this->error_tulis_penduduk['message'] = 'Tidak dapat mengubah status hubungan dengan nik ' . $data['nik'] . ' karena telah terdaftar';
            }

            // Abaikan no kk
            $keluarga = $this->config_id()->get_where('tweb_keluarga', ['id' => $res['id_kk']])->row_array();
            if ($isi_baris['no_kk'] != '' && $isi_baris['no_kk'] != $keluarga['no_kk']) {
                return $this->error_tulis_penduduk['message'] = 'Tidak dapat mengubah nomor kk dengan nik ' . $data['nik'] . ' karena telah terdaftar';
            }

            // Abaikan alamat
            $keluarga = $this->config_id()->get_where('tweb_keluarga', ['id' => $res['id_kk']])->row_array();
            if ($isi_baris['alamat'] != '' && $isi_baris['alamat'] != $keluarga['alamat']) {
                return $this->error_tulis_penduduk['message'] = 'Tidak dapat mengubah alamat dengan nik ' . $data['nik'] . ' karena telah terdaftar';
            }

            // Abaikan dusun
            $cluster = $this->config_id()->get_where('tweb_wil_clusterdesa', ['id' => $keluarga['id_cluster']])->row_array();
            if ($isi_baris['dusun'] != '' && $isi_baris['dusun'] != $cluster['dusun']) {
                return $this->error_tulis_penduduk['message'] = 'Tidak dapat mengubah dusun dengan nik ' . $data['nik'] . ' karena telah terdaftar';
            }

            // Abaikan rw
            $cluster = $this->config_id()->get_where('tweb_wil_clusterdesa', ['id' => $keluarga['id_cluster']])->row_array();
            if ($isi_baris['rw'] != '' && $isi_baris['rw'] != $cluster['rw']) {
                return $this->error_tulis_penduduk['message'] = 'Tidak dapat mengubah rw dengan nik ' . $data['nik'] . ' karena telah terdaftar';
            }

            // Abaikan rt
            $cluster = $this->config_id()->get_where('tweb_wil_clusterdesa', ['id' => $keluarga['id_cluster']])->row_array();
            if ($isi_baris['rt'] != '' && $isi_baris['rt'] != $cluster['rt']) {
                return $this->error_tulis_penduduk['message'] = 'Tidak dapat mengubah rt dengan nik ' . $data['nik'] . ' karena telah terdaftar';
            }

            if ($data['status_dasar'] != -1) {
                if ($this->penduduk_model->cekTagIdCard($data['tag_id_card'], $res['id'])) {
                    return $this->error_tulis_penduduk['message'] = 'Tag ID Card ' . $data['tag_id_card'] . ' sudah digunakan pada NIK : ' . $data['nik'];
                }

                $data['nik'] = $res['nik'];

                // Hanya update apabila status dasar valid (data SIAK)
                $data['updated_at'] = date('Y-m-d H:i:s');
                $data['updated_by'] = auth()->id;
                $this->config_id()->where('id', $res['id']);
                if (! $this->db->update('tweb_penduduk', $data)) {
                    $this->error_tulis_penduduk = $this->db->error();
                }
            }
            $penduduk_baru = $res['id'];
        } else {
            if ($this->setting->tgl_data_lengkap_aktif != 0) {
                return $this->error_tulis_penduduk['message'] = 'Tidak dapat menambahkan penduduk dengan nik ' . $data['nik'] . ' karena data sudah ditetapkan lengkap';
            }

            if ($data['nama'] == '' || $isi_baris['no_kk'] == '' || $data['kk_level'] == '' || $isi_baris['dusun'] == '' || $isi_baris['rt'] == '' || $isi_baris['rw'] == '') {
                return $this->error_tulis_penduduk['message'] = 'nama, nomor kk, shdk, dusun, rt, rw harus diisi untuk penduduk baru';
            }

            if ($this->penduduk_model->cekTagIdCard($data['tag_id_card'])) {
                return $this->error_tulis_penduduk['message'] = 'Tag ID Card ' . $data['tag_id_card'] . ' sudah digunakan pada NIK : ' . $data['nik'];
            }

            // Konfersi nik 0 sesuai format nik sementara
            $data['nik'] = $isi_baris['nik'];

            if ($data['status_dasar'] == -1) {
                $data['status_dasar'] = 9;
            } // Tidak Valid
            $data['created_at'] = date('Y-m-d H:i:s');
            $data['created_by'] = auth()->id;
            $data['config_id']  = $this->config_id;
            if (! $this->db->insert('tweb_penduduk', $data)) {
                $this->error_tulis_penduduk = $this->db->error();
            }
            $penduduk_baru = $this->db->insert_id();

            // Insert ke log_penduduk pada penduduk baru
            $kode_peristiwa = $data['status_dasar'];
            if ($data['status_dasar'] == 1 || $data['status_dasar'] == 9) {
                $kode_peristiwa = 5;
            }

            $log['tgl_peristiwa']  = $data['created_at'];
            $log['kode_peristiwa'] = $kode_peristiwa;
            $log['tgl_lapor']      = $data['created_at'];
            $log['id_pend']        = $penduduk_baru;
            $log['created_by']     = $data['created_by'];
            $log['config_id']      = $this->config_id;
            $this->penduduk_model->tulis_log_penduduk_data($log);
        }

        // Tambah atau perbarui lokasi penduduk
        $this->penduduk_map($penduduk_baru, $isi_baris['lat'], $isi_baris['lng']);

        // Update nik_kepala dan id_cluster di keluarga apabila baris ini kepala keluarga
        // dan sudah ada NIK
        if ($data['kk_level'] == 1) {
            $this->config_id()
                ->where('id', $data['id_kk'])
                ->update('tweb_keluarga', [
                    'nik_kepala' => $penduduk_baru,
                    'id_cluster' => $isi_baris['id_cluster'],
                    'alamat'     => $isi_baris['alamat'],
                ]);
        }

        return $penduduk_baru;
    }

    private function penduduk_map($id = 0, $lat = null, $lng = null)
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

        // Hapus data lat dan lng yang null
        DB::table('tweb_penduduk_map')->orWhereNull(['id', 'lat', 'lng'])->delete();
    }

    private function hapus_data_penduduk(): void
    {
        $tabel_penduduk = ['tweb_wil_clusterdesa', 'tweb_keluarga', 'tweb_penduduk', 'log_keluarga', 'log_penduduk', 'log_perubahan_penduduk', 'log_surat', 'tweb_rtm'];

        foreach ($tabel_penduduk as $tabel) {
            $this->config_id()->delete($tabel);
        }

        // Hapus peserta bantuan dengan sasaran penduduk, keluarga, rumah tangga, kelompok
        $program = $this->config_id()
            ->select('id')
            ->where_in('sasaran', [1, 2, 3, 4])
            ->get('program')
            ->result_array();

        if ($program) {
            $this->config_id()
                ->where_in('program_id', array_column($program, 'id'))
                ->delete('program_peserta');
        }
    }

    /** Tidak boleh menghapus data penduduk jika:
     * dalam demo_mode, atau
     * status penduduk sudah lengkap
     * tidak ada lagi data tweb_penduduk contoh awal (created_by = -1)
     */
    public function boleh_hapus_penduduk()
    {
        $data_awal = $this->config_id()
            ->from('tweb_penduduk')
            ->where('created_by <=', 0)
            ->count_all_results();
        if (config_item('demo_mode') || $data_awal == 0) {
            return false;
        }

        return ! $this->setting->tgl_data_lengkap_aktif;
    }

    public function impor_excel($hapus = false)
    {
        try {
            if ($this->file_import_valid() == false) {
                return;
            }

            $reader = ReaderEntityFactory::createXLSXReader();
            $reader->setShouldPreserveEmptyRows(true);
            $reader->open($_FILES['userfile']['tmp_name']);

            // Pengguna bisa menentukan apakah data penduduk yang ada dihapus dulu
            // atau tidak sebelum melakukan impor
            if ($hapus && $this->boleh_hapus_penduduk()) {
                $this->hapus_data_penduduk();
            }

            foreach ($reader->getSheetIterator() as $sheet) {
                $gagal         = 0;
                $ganda         = 0;
                $pesan         = '';
                $baris_data    = 0;
                $baris_pertama = false;
                $data_penduduk = [];
                $daftar_kolom  = [];

                if ($sheet->getName() == 'Kode Data') {
                    continue;
                }

                $data_excel = collect($sheet->getRowIterator())->map(static fn ($row) => collect($row->getCells())->map(static fn ($cell) => $cell->getValue()))
                    ->chunk(500)
                    ->toArray();

                foreach ($data_excel as $row) {
                    foreach ($row as $rowData) {
                        $baris_data++;

                        // Baris kedua = '###' menunjukkan telah sampai pada baris data terakhir
                        if ($rowData[1] == '###') {
                            break;
                        }

                        // Baris pertama diabaikan, berisi nama kolom
                        if (! $baris_pertama) {
                            $baris_pertama = true;
                            $daftar_kolom  = $rowData;

                            foreach ($daftar_kolom as $kolom) {
                                if (! in_array($kolom, $this->daftar_kolom)) {
                                    return set_session('error', 'Data penduduk gagal diimpor, nama kolom ' . $kolom . ' tidak sesuai.');
                                }
                            }

                            continue;
                        }

                        $this->db->query('SET character_set_connection = utf8');
                        $this->db->query('SET character_set_client = utf8');
                        $isi_baris      = $this->get_isi_baris($daftar_kolom, $rowData);
                        $error_validasi = $this->data_import_valid($isi_baris);
                        if (empty($error_validasi)) {
                            $this->tulis_tweb_wil_clusterdesa($isi_baris);
                            $this->tulis_tweb_keluarga($isi_baris);

                            // Untuk pesan jika data yang sama akan diganti
                            if ($index = array_search($isi_baris['nik'], $data_penduduk) && $isi_baris['nik'] != '0') {
                                $ganda++;
                                $pesan .= $baris_data . ') NIK ' . $isi_baris['nik'] . ' sama dengan baris ' . ($index + 2) . '<br>';
                            }
                            $data_penduduk[] = $isi_baris['nik'];

                            $this->tulis_tweb_penduduk($isi_baris);
                            if ($error = $this->error_tulis_penduduk) {
                                $gagal++;
                                $pesan .= $baris_data . ') ' . $error['message'] . '<br>';
                            }
                        } else {
                            $gagal++;
                            $pesan .= $baris_data . ') ' . $error_validasi . '<br>';
                        }
                    }
                }

                if (($baris_data - 1) <= 0) {
                    return set_session('error', 'Data penduduk gagal diimpor');
                }

                $pesan_impor = [
                    'gagal'  => $gagal,
                    'ganda'  => $ganda,
                    'pesan'  => $pesan,
                    'sukses' => ($baris_data - 1) - $gagal,
                ];

                set_session('pesan_impor', $pesan_impor);
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

    public function impor_bip($hapus = false)
    {
        try {
            if ($this->file_import_valid() == false) {
                return;
            }

            $data = new Spreadsheet_Excel_Reader($_FILES['userfile']['tmp_name']);

            $this->db->query('SET character_set_connection = utf8');
            $this->db->query('SET character_set_client = utf8');

            // Pengguna bisa menentukan apakah data penduduk yang ada dihapus dulu
            // atau tidak sebelum melakukan impor
            if ($hapus) {
                $this->hapus_data_penduduk();
            }

            require_once APPPATH . '/models/Bip_model.php';
            $bip = new BIP_Model($data);
            $bip->impor_bip();
        } catch (Exception $e) {
            log_message('error', $e->getMessage());

            return set_session('error', 'Data penduduk gagal diimpor.');
        }
    }
}
