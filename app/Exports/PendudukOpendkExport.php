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

namespace App\Exports;

use App\Services\DataEkspor;
use Rap2hpoutre\FastExcel\FastExcel;

class PendudukOpendkExport
{
    protected $fields = [];

    public function filename($name = null)
    {
        return $name ?? namafile('penduduk_opendk');
    }

    /**
     * @return mixed[][]
     */
    public function data(): array
    {
        $daftar_kolom = [
            ['Alamat', 'alamat'],
            ['Dusun', 'dusun'],
            ['RW', 'rw'],
            ['RT', 'rt'],
            ['Nama', 'nama'],
            ['Nomor KK', 'nomor_kk'],
            ['Nomor NIK', 'nomor_nik'],
            ['Jenis Kelamin', 'jenis_kelamin'],
            ['Tempat Lahir', 'tempat_lahir'],
            ['Tanggal Lahir', 'tanggal_lahir'],
            ['Agama', 'agama'],
            ['Pendidikan (dlm KK)', 'pendidikan_dlm_kk'],
            ['Pendidikan (sdg ditempuh)', 'pendidikan_sdg_ditempuh'],
            ['Pekerjaan', 'pekerjaan'],
            ['Kawin', 'kawin'],
            ['Hub. Keluarga', 'hubungan_keluarga'],
            ['Kewarganegaraan', 'kewarganegaraan'],
            ['Nama Ayah', 'nama_ayah'],
            ['Nama Ibu', 'nama_ibu'],
            ['Gol. Darah', 'gol_darah'],
            ['Akta Lahir', 'akta_lahir'],
            ['Nomor Dokumen Paspor', 'nomor_dokumen_pasport'],
            ['Tanggal Akhir Paspor', 'tanggal_akhir_pasport'],
            ['Nomor Dokumen KITAS', 'nomor_dokumen_kitas'],
            ['NIK Ayah', 'nik_ayah'],
            ['NIK Ibu', 'nik_ibu'],
            ['Nomor Akta Perkawinan', 'nomor_akta_perkawinan'],
            ['Tanggal Perkawinan', 'tanggal_perkawinan'],
            ['Nomor Akta Perceraian', 'nomor_akta_perceraian'],
            ['Tanggal Perceraian', 'tanggal_perceraian'],
            ['Cacat', 'cacat'],
            ['Cara KB', 'cara_kb'],
            ['Hamil', 'hamil'],
            ['KTP-el', 'ktp_el'],
            ['Status Rekam', 'status_rekam'],
            ['Alamat Sekarang', 'alamat_sekarang'],
        ];

        $judul = array_column($daftar_kolom, 1);

        // Kolom tambahan khusus OpenDK
        $judul[] = 'id';
        $judul[] = 'foto';
        $judul[] = 'status_dasar';
        $judul[] = 'created_at';
        $judul[] = 'updated_at';
        $judul[] = 'desa_id';

        $this->fields = $judul;

        $dataExport = DataEkspor::tambah_penduduk_sinkronasi_opendk();

        if (empty($dataExport)) {
            return [emptyData($this->fields)];
        }

        return $dataExport;
    }

    public function download()
    {
        return (new FastExcel())->data($this->data())->download($this->filename());
    }

    public function export()
    {
        $filePath = sys_get_temp_dir() . '/' . $this->filename() . '.xlsx';

        return (new FastExcel())->data($this->data())->export($filePath);
    }

    public function zip(): string
    {
        $ci       = &get_instance();
        $penduduk = $this->export();
        $ci->zip->read_file($penduduk);
        $filename = $this->filename() . '.zip';
        $ci->zip->archive(LOKASI_SINKRONISASI_ZIP . $filename);

        return $filename;
    }
}
