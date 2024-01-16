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

// Model ini digunakan untuk data referensi statis yg tidak disimpan pd database atau sebagai referensi global

define('JENIS_PERATURAN_DESA', serialize([
    'Peraturan Desa',
    'Peraturan Kepala Desa',
    'Peraturan Bersama Kepala Desa',
]));

define('KATEGORI_PUBLIK', serialize([
    'Informasi Berkala'      => '1',
    'Informasi Serta-merta'  => '2',
    'Informasi Setiap Saat'  => '3',
    'Informasi Dikecualikan' => '4',
]));

define('STATUS_PERMOHONAN', serialize([
    'Belum Lengkap'        => '0',
    'Sedang Diperiksa'     => '1',
    'Menunggu Tandatangan' => '2',
    'Siap Diambil'         => '3',
    'Sudah Diambil'        => '4',
    'Dibatalkan'           => '5',
]));

define('LINK_TIPE', serialize([
    '1'  => 'Artikel Statis',
    '8'  => 'Kategori Artikel',
    '2'  => 'Statistik Penduduk',
    '3'  => 'Statistik Keluarga',
    '4'  => 'Statistik Program Bantuan',
    '5'  => 'Halaman Statis Lainnya',
    '6'  => 'Artikel Keuangan',
    '7'  => 'Kelompok',
    '11' => 'Lembaga',
    '9'  => 'Data Suplemen',
    '10' => 'Status IDM',
    '99' => 'Eksternal',
]));

// Statistik Penduduk
define('STAT_PENDUDUK', serialize([
    '13'               => 'Umur (Rentang)',
    '15'               => 'Umur (Kategori)',
    '0'                => 'Pendidikan Dalam KK',
    '14'               => 'Pendidikan Sedang Ditempuh',
    '1'                => 'Pekerjaan',
    '2'                => 'Status Perkawinan',
    '3'                => 'Agama',
    '4'                => 'Jenis Kelamin',
    'hubungan_kk'      => 'Hubungan Dalam KK',
    '5'                => 'Warga Negara',
    '6'                => 'Status Penduduk',
    '7'                => 'Golongan Darah',
    '9'                => 'Penyandang Cacat',
    '10'               => 'Penyakit Menahun',
    '16'               => 'Akseptor KB',
    '17'               => 'Akta Kelahiran',
    '18'               => 'Kepemilikan KTP',
    '19'               => 'Asuransi Kesehatan',
    'covid'            => 'Status Covid',
    'suku'             => 'Suku / Etnis',
    'bpjs-tenagakerja' => 'BPJS Ketenagakerjaan',
    'hamil'            => 'Status Kehamilan',
    'buku-nikah'       => 'Buku Nikah',
    'kia'              => 'Kepemilikan KIA',
    'akta-kematian'    => 'Kepemilikan Akta Kematian',
]));

// Statistik Keluarga
define('STAT_KELUARGA', serialize([
    'kelas_sosial' => 'Kelas Sosial',
]));

// Statistik RTM
define('STAT_RTM', serialize([
    'bdt' => 'BDT',
]));

// Statistik Bantuan
define('STAT_BANTUAN', serialize([
    'bantuan_penduduk' => 'Penerima Bantuan Penduduk',
    'bantuan_keluarga' => 'Penerima Bantuan Keluarga',
]));

// Statistik Lainnya
define('STAT_LAINNYA', serialize([
    'dpt'                     => 'Calon Pemilih',
    'data-wilayah'            => 'Wilayah Administratif',
    'peraturan-desa'          => 'Produk Hukum',
    'informasi_publik'        => 'Informasi Publik',
    'peta'                    => 'Peta',
    'data_analisis'           => 'Data Analisis',
    'status-sdgs'             => 'SDGs [Desa]',
    'lapak'                   => 'Lapak [Desa]',
    'pembangunan'             => 'Pembangunan',
    'galeri'                  => 'Galeri',
    'pengaduan'               => 'Pengaduan',
    'data-vaksinasi'          => 'Vaksin',
    'pemerintah'              => '[Pemerintah Desa]',
    'layanan-mandiri/beranda' => 'Layanan Mandiri',
]));

// Jabatan Kelompok
define('JABATAN_KELOMPOK', serialize([
    1  => 'KETUA',
    2  => 'WAKIL KETUA',
    3  => 'SEKRETARIS',
    4  => 'BENDAHARA',
    90 => 'ANGGOTA',
]));

// API Server
define('STATUS_AKTIF', serialize([
    '0' => 'Tidak Aktif',
    '1' => 'Aktif',
]));

define('JENIS_NOTIF', serialize([
    'pemberitahuan',
    'pengumuman',
    'peringatan',
]));

define('SERVER_NOTIF', serialize([
    'TrackSID',
]));

define('SUMBER_DANA', serialize([
    1 => 'Pendapatan Asli Daerah',
    2 => 'Alokasi Anggaran Pendapatan dan Belanja Negara (Dana Desa)',
    3 => 'Bagian Hasil Pajak Daerah dan Retribusi Daerah Kabupaten/Kota',
    4 => 'Alokasi Dana Desa',
    5 => 'Bantuan Keuangan dari APBD Provinsi dan APBD Kabupaten/Kota',
    6 => 'Hibah dan Sumbangan yang Tidak Mengikat dari Pihak Ketiga',
    7 => 'Lain-lain Pendapatan Desa yang Sah',
]));

define('STATUS_PEMBANGUNAN', serialize([
    1 => '0%',
    2 => '30%',
    3 => '80%',
    4 => '100%',
]));

// Sumber : https://news.detik.com/berita/d-5825409/jenis-vaksin-di-indonesia-berikut-daftar-hingga-efek-sampingnya
define('JENIS_VAKSIN', serialize([
    'Covovax',
    'Zififax',
    'Sinovac',
    'AstraZeneca',
    'Sinopharm',
    'Moderna',
    'Pfizer',
    'Novavax',
    'Johnson&Johnson',
    'Biofarma',
]));

define('STATUS', serialize([
    1 => 'Ya',
    2 => 'Tidak',
]));

// Sebab Kematian
define('SEBAB', serialize([
    1 => 'Sakit biasa / tua',
    2 => 'Wabah Penyakit',
    3 => 'Kecelakaan',
    4 => 'Kriminalitas',
    5 => 'Bunuh Diri',
    6 => 'Lainnya',
]));

define('PENOLONG_MATI', serialize([
    '1' => 'Dokter',
    '2' => 'Tenaga Kesehatan',
    '3' => 'Kepolisian',
    '4' => 'Lainnya',
]));

class Referensi_model extends MY_Model
{
    public function list_nama($tabel)
    {
        $data = $this->list_data($tabel);
        $list = [];

        foreach ($data as $value) {
            $list[$value['id']] = $value['nama'];
        }

        return $list;
    }

    public function list_data($tabel, $kecuali = '', $termasuk = null)
    {
        if ($kecuali) {
            $this->db->where("id NOT IN ({$kecuali})");
        }

        if ($termasuk) {
            $this->db->where("id IN ({$termasuk})");
        }

        return $this->db->select('*')->order_by('id')->get($tabel)->result_array();
    }

    public function list_ktp_el()
    {
        return array_flip(unserialize(KTP_EL));
    }

    public function list_status_rekam()
    {
        $data = $this->db->select('status_rekam, LOWER(nama) as nama')
            ->get('tweb_status_ktp')->result_array();

        return array_combine(array_column($data, 'status_rekam'), array_column($data, 'nama'));
    }

    public function list_by_id($tabel, $id = 'id')
    {
        $data = $this->config_id()
            ->order_by($id)
            ->get($tabel)
            ->result_array();

        return array_combine(array_column($data, $id), $data);
    }

    public function list_ref($stat = STAT_PENDUDUK)
    {
        return unserialize($stat);
    }

    public function list_ref_flip($s_array)
    {
        return array_flip(unserialize($s_array));
    }

    public function impor_list_data($tabel, $tambahan = [], $kecuali = '', $termasuk = null)
    {
        $data = $this->list_data($tabel, $kecuali, $termasuk);
        $data = array_flip(array_combine(array_column($data, 'id'), array_column($data, 'nama')));

        return array_change_key_case(array_merge($data, $tambahan));
    }

    public function jenis_peraturan_desa()
    {
        $dafault = $this->list_ref(JENIS_PERATURAN_DESA);

        return collect($dafault)->transform(static fn ($item) => str_replace(['Desa', 'desa'], ucwords(setting('sebutan_desa')), $item))->unique()->values();
    }
}
