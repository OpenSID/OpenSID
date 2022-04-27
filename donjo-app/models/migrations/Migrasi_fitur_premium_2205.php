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

use Illuminate\Support\Facades\Schema;

defined('BASEPATH') || exit('No direct script access allowed');

class Migrasi_fitur_premium_2205 extends MY_model
{
    public function up()
    {
        $hasil = true;

        // Jalankan migrasi sebelumnya
        $hasil = $hasil && $this->jalankan_migrasi('migrasi_fitur_premium_2204');
        $hasil = $hasil && $this->hapusTabelTidakDigunakan($hasil);
        $hasil = $hasil && $this->pebaikiNotifikasi($hasil);

        return $hasil && $this->migrasi_2022042751($hasil);
    }

    protected function migrasi_2022042751($hasil)
    {
        // Hapus key covid_data dan provinsi_covid
        return $hasil && $this->db
            ->where_in('key', ['covid_data', 'provinsi_covid'])
            ->delete('setting_aplikasi');
    }

    protected function hapusTabelTidakDigunakan($hasil)
    {
        $daftartabel = [
            'detail_log_penduduk',
            'klasifikasi_analisis_keluarga',
            'pertanyaan',
            'tweb_surat_atribut',
        ];

        foreach ($daftartabel as $tabel) {
            Schema::dropIfExists($tabel);
        }

        return $hasil;
    }

    protected function pebaikiNotifikasi($hasil)
    {
        return $hasil && $this->db->where('kode', 'persetujuan_penggunaan')->update('notifikasi', ['aksi' => 'notif/update_pengumuman,siteman/logout']);
    }
}
