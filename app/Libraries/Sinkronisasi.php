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

use Illuminate\Support\Facades\DB;

class Sinkronisasi
{
    private string $zip_file = '';

    // $file = nama file yg akan diproses
    private function extractFile(string $file)
    {
        $data  = get_csv($this->zip_file, $file);
        $count = count($data);

        for ($i = 0; $i < $count; $i++) {
            if (empty($data[$i]) || ! array_filter($data[$i])) {
                unset($data[$i]);
            }
        }

        return $data;
    }

    public function sinkronkan($file = null)
    {
        $hasil = true;

        // Kolom server berisi daftar jenis penggunaan server, misalanya: '4,5,6'
        // Tidak gunakan kolom jenis json, karena penerapan json berbeda antara MySQL dan MariaDB.
        $server       = setting('penggunaan_server');
        $server_regex = "^{$server}$|,{$server}$|,{$server},|^{$server},";
        $list_tabel   = DB::table('ref_sinkronisasi')->where("TRIM(server) REGEXP '{$server_regex}'")->get()->toArray();

        // Proses tabel yg berlaku untuk jenis penggunaan server
        $this->zip_file = $file;

        foreach ($list_tabel as $tabel) {
            $nama_tabel        = $tabel['tabel'];
            $update_dari_waktu = DB::table($nama_tabel)->selectRaw('MAX(updated_at) as waktu_update')->first()->waktu_update;
            $update_dari_waktu = strtotime($update_dari_waktu);
            $data_tabel        = $this->extractFile($nama_tabel . '.csv');
            $data_tabel        = $this->hapusKolomTersamar($data_tabel, $tabel['tabel']);

            // Hanya ambil data yg telah berubah
            foreach ($data_tabel as $k => $v) {
                if (strtotime($v['updated_at']) <= $update_dari_waktu) {
                    unset($data_tabel[$k]);
                } else {
                    // Data CSV berisi string 'NULL' untuk kolom dengan nilai NULL
                    $data_tabel[$k] = array_map(static fn ($a) => $a == 'NULL' ? null : $a, $data_tabel[$k]);
                }
            }
            if (empty($data_tabel)) {
                continue;
            }

            foreach ($data_tabel as $data) {
                DB::table($tabel['tabel'])->where('id', $data['id'])->update($data);
            }
            $_SESSION['success'] = -1;
        }

        return $hasil;
    }

    // Hapus kolom yang tidak akan diupdate
    private function hapusKolomTersamar($data_tabel, $tabel)
    {
        foreach ($data_tabel as &$item) {
            switch ($tabel) {
                case 'tweb_keluarga':
                    unset($item['no_kk']);
                    break;

                case 'tweb_penduduk':
                    unset($item['nama'], $item['nik']);

                    break;
            }
        }

        return $data_tabel;
    }
}
