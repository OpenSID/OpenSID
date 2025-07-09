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

use App\Models\FormatSurat;

defined('BASEPATH') || exit('No direct script access allowed');

class Penomoran_surat_model extends MY_Model
{
    /**
     * Cari surat dengan nomor terakhir sesuai setting aplikasi
     *
     * @param string 	nama tabel surat
     * @param mixed      $type
     * @param mixed|null $url
     *
     * @return array surat terakhir
     */
    public function get_surat_terakhir($type, $url = null)
    {
        $setting = setting('penomoran_surat');

        if ($setting == 3) {
            $last_sl = $this->get_surat_terakhir_type('log_surat', null, 1);
            $last_sm = $this->get_surat_terakhir_type('surat_masuk', null, 1);
            $last_sk = $this->get_surat_terakhir_type('surat_keluar', null, 1);

            $surat[$last_sl['no_surat']]   = $last_sl;
            $surat[$last_sm['nomor_urut']] = $last_sm;
            $surat[$last_sk['nomor_urut']] = $last_sk;
            krsort($surat);

            return current($surat);
        }

        return $this->get_surat_terakhir_type($type, $url);
    }

    private function get_surat_terakhir_type($type, $url = null, $setting = null)
    {
        $thn                 = date('Y');
        $setting || $setting = setting('penomoran_surat');

        switch ($type) {
            default: show_error("Function {$self}(): Unknown type `{$type}`");

                // no break
            case 'log_surat':
                if ($setting == 1) {
                    if ($type == 'log_surat') {
                        $this->db->where('deleted_at');
                    }
                    $this->config_id()
                        ->from("{$type}")
                        ->where('YEAR(tanggal)', $thn)
                        ->where('status', 1)
                        ->order_by('CAST(no_surat as unsigned) DESC')
                        ->limit(1);
                } elseif ($setting == 4) {
                    $kode_surat = FormatSurat::where('url_surat', $url)->first()->kode_surat;
                    if ($type == 'log_surat') {
                        $this->db->where('deleted_at');
                    }
                    $this->config_id('l')
                        ->select('*, f.nama, l.id id_surat')
                        ->from("{$type} l")
                        ->join('tweb_surat_format f', 'f.id=l.id_format_surat', 'RIGHT')
                        ->group_start()
                        ->where('kode_surat', $kode_surat)
                        ->group_end()
                        ->where('YEAR(l.tanggal)', $thn)
                        ->order_by('CAST(l.no_surat as unsigned) DESC');
                } else {
                    if ($type == 'log_surat') {
                        $this->db->where('deleted_at');
                    }
                    $this->config_id('l')
                        ->select('*, f.nama, l.id id_surat')
                        ->from("{$type} l")
                        ->join('tweb_surat_format f', 'f.id=l.id_format_surat', 'RIGHT')
                        ->group_start()
                        ->where('url_surat', $url)
                        ->or_where("url_surat = REPLACE(REPLACE('{$url}', 'erangan', ''), '-', '_')")
                        ->group_end()
                        ->where('YEAR(l.tanggal)', $thn)
                        ->order_by('CAST(l.no_surat as unsigned) DESC');
                }
                break;

            case 'surat_masuk':
                $this->config_id()
                    ->from("{$type}")
                    ->where('YEAR(tanggal_surat)', $thn)
                    ->order_by('CAST(nomor_urut as unsigned) DESC')
                    ->limit(1);
                break;

            case 'surat_keluar':
                $this->config_id()
                    ->from("{$type}")
                    ->where('YEAR(tanggal_surat)', $thn)
                    ->order_by('CAST(nomor_urut as unsigned) DESC')
                    ->limit(1);
        }
        $surat                                             = $this->db->get()->row_array();
        $surat['nomor_urut']    || $surat['nomor_urut']    = $surat['no_surat'];
        $surat['no_surat']      || $surat['no_surat']      = $surat['nomor_urut'];
        $surat['tanggal_surat'] || $surat['tanggal_surat'] = $surat['tanggal'];
        $surat['tanggal']       || $surat['tanggal']       = $surat['tanggal_surat'];
        $surat['tanggal']                                  = tgl_indo2($surat['tanggal']);

        return $surat;
    }

    /**
     * Periksa apakah nomor surat sudah digunakan sesuai setting aplikasi
     *
     * @param string 		nama tabel surat
     * @param int 	nomor urut atau nomor surat
     * @param string 		url surat untuk layanan surat
     * @param mixed      $type
     * @param mixed      $nomor_surat
     * @param mixed|null $url
     *
     * @return bool apakah nomor surat sudah ada atau belum
     */
    public function nomor_surat_duplikat($type, $nomor_surat, $url = null)
    {
        $thn     = date('Y');
        $setting = setting('penomoran_surat');
        if ($setting == 3) {
            // Nomor urut gabungan surat layanan, surat masuk dan surat keluar
            $sql = [];

            $sql[] = '(' . $this->config_id()
                ->from('log_surat')
                ->select('no_surat as nomor_urut')
                ->where('YEAR(tanggal)', $thn)
                ->where('deleted_at')
                ->where('no_surat', $nomor_surat)
                ->get_compiled_select() . ')';

            $sql[] = '(' . $this->config_id()
                ->from('surat_masuk')
                ->select('nomor_urut')
                ->where('YEAR(tanggal_surat)', $thn)
                ->where('nomor_urut', $nomor_surat)
                ->get_compiled_select() . ')';

            $sql[] = '(' . $this->config_id()
                ->from('surat_keluar')
                ->select('nomor_urut')
                ->where('YEAR(tanggal_surat)', $thn)
                ->where('nomor_urut', $nomor_surat)
                ->get_compiled_select() . ')';

            $sql       = implode('UNION', $sql);
            $jml_surat = $this->db->query($sql)->num_rows();

            return $jml_surat > 0;
        }

        switch ($type) {
            default: show_error("Function {$self}(): Unknown type `{$type}`");

                // no break
            case 'log_surat':
                if ($setting == 1) {
                    if ($type == 'log_surat') {
                        $this->db->where('deleted_at');
                    }
                    $this->config_id()
                        ->from("{$type}")
                        ->where('YEAR(tanggal)', $thn)
                        ->where('no_surat', $nomor_surat);
                } elseif ($setting == 4) {
                    $kode_surat = FormatSurat::where('url_surat', $url)->first()->kode_surat;
                    if ($type == 'log_surat') {
                        $this->db->where('deleted_at');
                    }
                    $this->config_id('l')
                        ->select('*, f.nama, l.id id_surat')
                        ->from("{$type} l")
                        ->where('no_surat', $nomor_surat)
                        ->join('tweb_surat_format f', 'f.id=l.id_format_surat', 'RIGHT')
                        ->group_start()
                        ->where('kode_surat', $kode_surat)
                        ->group_end()
                        ->where('YEAR(l.tanggal)', $thn);
                } else {
                    if ($type == 'log_surat') {
                        $this->db->where('deleted_at');
                    }
                    $this->config_id('l')
                        ->from("{$type} l")
                        ->join('tweb_surat_format f', 'f.id=l.id_format_surat', 'RIGHT')
                        ->select('*, f.nama, l.id id_surat')
                        ->where('url_surat', $url)
                        ->where('YEAR(l.tanggal)', $thn)
                        ->where('no_surat', $nomor_surat);
                }
                break;

            case 'surat_masuk':
                $this->config_id()
                    ->from("{$type}")
                    ->where('YEAR(tanggal_surat)', $thn)
                    ->where('nomor_urut', $nomor_surat);
                break;

            case 'surat_keluar':
                $this->config_id()
                    ->from("{$type}")
                    ->where('YEAR(tanggal_surat)', $thn)
                    ->where('nomor_urut', $nomor_surat);
        }
        $surat = $this->db->get()->row_array();

        return ! empty($surat);
    }
}
