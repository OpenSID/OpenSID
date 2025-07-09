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

define('KOLOM_IMPOR_SIAK', serialize([
    'no_kk'             => '1',
    'nik'               => '2',
    'nama'              => '3',
    'status_dasar'      => '4',
    'tempatlahir'       => '5',
    'tanggallahir'      => '6',
    'sex'               => '7',
    'ayah_nik'          => '8',
    'nama_ayah'         => '9',
    'ibu_nik'           => '10',
    'nama_ibu'          => '11',
    'status_kawin'      => '12',
    'kk_level'          => '13',
    'agama_id'          => '14',
    'alamat'            => '17',
    'rw'                => '18',
    'rt'                => '19',
    'pendidikan_kk_id'  => '20',
    'pekerjaan_id'      => '21',
    'golongan_darah_id' => '23',
    'cacat_id'          => '24',
    'dokumen_pasport'   => '28',
    'akta_lahir'        => '29',
    'akta_perkawinan'   => '30',
    'tanggalperkawinan' => '31',
    'akta_perceraian'   => '32',
    'tanggalperceraian' => '33',
    'tgl_entri'         => '37',
]));

class Siak_model extends Impor_model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('penduduk_model');
    }

    /* 	======================================================
            IMPOR DATA DALAM FORMAT SIAK
            ======================================================
    */

    private function cari_baris_pertama($data, $baris)
    {
        if ($baris <= 1) {
            return 0;
        }

        $baris_pertama = 1;

        for ($i = 2; $i <= $baris; $i++) {
            // Baris dengan tiga kolom pertama kosong menandakan baris tanpa data
            if ($data->val($i, 1) == '' && $data->val($i, 2) == '' && $data->val($i, 3) == '') {
                continue;
            }

            // Ketemu baris data pertama
            $baris_pertama = $i;
            break;
        }

        return $baris_pertama;
    }

    private function get_isi_baris($data, int $i)
    {
        $kolom_impor         = unserialize(KOLOM_IMPOR_SIAK);
        $isi_baris['alamat'] = trim($data->val($i, $kolom_impor['alamat']));
        // alamat berbentuk 'DSN LIWET'
        $pecah_alamat        = preg_split('/DSN |DS |DUSUN |DSN\\. |DS\\. |DUSUN\\. /i', $isi_baris['alamat']);
        $isi_baris['alamat'] = $pecah_alamat[0];
        $isi_baris['dusun']  = $pecah_alamat[1];
        if (empty($isi_baris['dusun'])) {
            $isi_baris['dusun'] = $isi_baris['alamat'];
        }

        $isi_baris['rw'] = ltrim(trim($data->val($i, $kolom_impor['rw'])), "'");
        $isi_baris['rt'] = ltrim(trim($data->val($i, $kolom_impor['rt'])), "'");

        $nama              = trim($data->val($i, $kolom_impor['nama']));
        $nama              = preg_replace("/[^a-zA-Z,\\.'-]/", ' ', $nama);
        $isi_baris['nama'] = $nama;

        // Konversi status dasar dari string / integer.

        $isi_baris['status_dasar'] = $this->get_konversi_kode($this->kode_status_dasar, $data->val($i, $kolom_impor['status_dasar']));

        // Data Disdukcapil adakalanya berisi karakter tambahan pada no_kk dan nik
        // yang tidak tampak (non-printable characters),
        // jadi perlu dibuang
        $no_kk              = trim($data->val($i, $kolom_impor['no_kk']));
        $no_kk              = preg_replace('/[^0-9]/', '', $no_kk);
        $isi_baris['no_kk'] = $no_kk;

        $isi_baris['nik']              = buang_nondigit($data->val($i, $kolom_impor['nik']));
        $isi_baris['sex']              = $this->get_konversi_kode($this->kode_sex, $data->val($i, $kolom_impor['sex']));
        $isi_baris['tempatlahir']      = trim($data->val($i, $kolom_impor['tempatlahir']));
        $isi_baris['tanggallahir']     = $this->format_tanggal($data->val($i, $kolom_impor['tanggallahir']));
        $isi_baris['agama_id']         = $this->get_konversi_kode($this->kode_agama, $data->val($i, $kolom_impor['agama_id']));
        $isi_baris['pendidikan_kk_id'] = $this->get_konversi_kode($this->kode_pendidikan_kk, $data->val($i, $kolom_impor['pendidikan_kk_id']));
        $isi_baris['pekerjaan_id']     = $this->get_konversi_kode($this->kode_pekerjaan, $this->normalkan_data($data->val($i, $kolom_impor['pekerjaan_id'])));
        $isi_baris['status_kawin']     = $this->get_konversi_kode($this->kode_status, $data->val($i, $kolom_impor['status_kawin']));
        $isi_baris['kk_level']         = $this->get_konversi_kode($this->kode_hubungan, $data->val($i, $kolom_impor['kk_level']));
        $isi_baris['warganegara_id']   = $this->get_konversi_kode($this->kode_warganegara, $data->val($i, $kolom_impor['warganegara_id']));

        $nama_ayah = trim($data->val($i, $kolom_impor['nama_ayah']));
        if ($nama_ayah == '') {
            $nama_ayah = '-';
        }
        $isi_baris['nama_ayah'] = $nama_ayah;

        $nama_ibu = trim($data->val($i, $kolom_impor['nama_ibu']));
        if ($nama_ibu == '') {
            $nama_ibu = '-';
        }
        $isi_baris['nama_ibu'] = $nama_ibu;

        $isi_baris['golongan_darah_id'] = $this->get_konversi_kode($this->kode_golongan_darah, $data->val($i, $kolom_impor['golongan_darah_id']));
        $isi_baris['akta_lahir']        = trim($data->val($i, $kolom_impor['akta_lahir']));
        $isi_baris['dokumen_pasport']   = trim($data->val($i, $kolom_impor['dokumen_pasport']));

        $isi_baris['ayah_nik']          = buang_nondigit($data->val($i, $kolom_impor['ayah_nik']));
        $isi_baris['ibu_nik']           = buang_nondigit($data->val($i, $kolom_impor['ibu_nik']));
        $isi_baris['akta_perkawinan']   = trim($data->val($i, $kolom_impor['akta_perkawinan']));
        $isi_baris['tanggalperkawinan'] = $this->format_tanggal($data->val($i, $kolom_impor['tanggalperkawinan']));
        $isi_baris['akta_perceraian']   = trim($data->val($i, $kolom_impor['akta_perceraian']));
        $isi_baris['tanggalperceraian'] = $this->format_tanggal($data->val($i, $kolom_impor['tanggalperceraian']));
        $isi_baris['cacat_id']          = $this->get_konversi_kode($this->kode_cacat, $data->val($i, $kolom_impor['cacat_id']));

        // Untuk tulis ke log_penduduk
        $isi_baris['status_dasar_orig'] = trim($data->val($i, $kolom_impor['status_dasar']));
        $isi_baris['tgl_entri']         = $this->format_tanggal($data->val($i, $kolom_impor['tgl_entri']));

        return $isi_baris;
    }

    // Normalkan kolom seperti "SLTP / SEDERAJAT" menjadi "sltp/sederajat"
    private function normalkan_data($str)
    {
        return preg_replace('/\s*\/\s*/', '/', strtolower(trim($str)));
    }

    /**
     * Proses impor data bip
     *
     * @param sheet		data excel berisi bip
     * @param mixed $data
     *
     * @return setting $_SESSION untuk info hasil impor
     *                 $_SESSION['gagal']=						jumlah baris yang gagal
     *                 $_SESSION['total_keluarga']=	jumlah keluarga yang diimpor
     *                 $_SESSION['total_penduduk']=	jumlah penduduk yang diimpor
     *                 $_SESSION['baris']=						daftar baris yang gagal
     */
    public function impor_data_bip($data)
    {
        // membaca jumlah baris dari data excel
        $baris = $data->rowcount($sheet_index = 0);
        if ($this->cari_baris_pertama($data, $baris) <= 1) {
            return set_session('error', 'Data penduduk gagal diimpor, data tidak tersedia.');
        }

        $gagal_penduduk = 0;
        $baris_gagal    = '';
        $total_keluarga = 0;
        $total_penduduk = 0;

        // Import data excel mulai baris ke-2 (karena baris pertama adalah nama kolom)
        for ($i = 2; $i <= $baris; $i++) {
            // Baris dengan tiga kolom pertama kosong menandakan baris tanpa data
            if ($data->val($i, 1) == '' && $data->val($i, 2) == '' && $data->val($i, 3) == '') {
                continue;
            }

            $isi_baris      = $this->get_isi_baris($data, $i);
            $error_validasi = $this->data_import_valid($isi_baris);
            if (empty($error_validasi)) {
                $this->tulis_tweb_wil_clusterdesa($isi_baris);
                if ($this->tulis_tweb_keluarga($isi_baris)) {
                    $total_keluarga++;
                }
                $penduduk_baru = $this->tulis_tweb_penduduk($isi_baris);
                if ($penduduk_baru) {
                    $total_penduduk++;
                    // Tulis log kalau status dasar MATI, HILANG atau PINDAH
                    if (in_array($isi_baris['status_dasar'], ['2', '3', '4'])) {
                        $this->tulis_log_penduduk($isi_baris, $penduduk_baru);
                    }
                }
            } else {
                $gagal_penduduk++;
                $baris_gagal .= $i . ' (' . $error_validasi . ')<br>';
            }
        }

        if ($gagal_penduduk == 0) {
            $baris_gagal = 'tidak ada data yang gagal di import.';
        }

        $pesan_impor = [
            'gagal'          => $gagal_penduduk,
            'total_keluarga' => $total_keluarga,
            'total_penduduk' => $total_penduduk,
            'baris'          => $baris_gagal,
        ];

        set_session('pesan_impor', $pesan_impor);

        return set_session('success', 'Data penduduk berhasil diimpor');
    }

    private function tulis_log_penduduk($data, $id): void
    {
        // Tulis log_penduduk
        $log = [
            'id_pend'        => $id,
            'no_kk'          => $data['no_kk'],
            'tgl_peristiwa'  => $data['tgl_entri'],
            'tgl_lapor'      => $data['tgl_entri'],
            'created_by'     => $this->session->user,
            'kode_peristiwa' => $data['status_dasar'],
            'catatan'        => 'Status impor data SIAK: ' . $data['status_dasar_orig'],
        ];
        $this->penduduk_model->tulis_log_penduduk_data($log);
    }
}
