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

class Grup_model extends MY_Model
{
    protected $table = 'user_grup';

    public function grup_akses($id_grup)
    {
        return $this->config_id('m')
            ->select('m.*')
            ->select('if(a.akses & 1 = 1, 1, 0) as akses_baca')
            ->select('if(a.akses & 2 = 2, 1, 0) as akses_ubah')
            ->select('if(a.akses & 4 = 4, 1, 0) as akses_hapus')
            ->select('if(a.akses > 0, 1, 0) as ada_akses')
            ->from('setting_modul m')
            ->join('grup_akses a', "a.id_modul = m.id and a.id_grup = {$id_grup}", 'left')
            ->where('m.parent', 0)
            ->where('m.aktif', 1)
            ->order_by('m.urut')
            ->get()
            ->result_array();
    }

    private function get_submodul($grup, $modul)
    {
        return $this->config_id('p')
            ->select('sub.id, sub.modul, sub.url')
            ->select('if(a.akses & 1 = 1, 1, 0) as akses_baca')
            ->select('if(a.akses & 2 = 2, 1, 0) as akses_ubah')
            ->select('if(a.akses & 4 = 4, 1, 0) as akses_hapus')
            ->select('if(a.akses > 0, 1, 0) as ada_akses')
            ->from('setting_modul p')
            ->join('setting_modul sub', 'sub.parent = p.id')
            ->join('grup_akses a', "sub.id = a.id_modul and a.id_grup = {$grup}", 'left')
            ->where('p.id', $modul)
            ->where('sub.aktif', 1)
            ->order_by('sub.urut')
            ->get()->result_array();
    }

    public function akses_submodul($grup)
    {
        $parent = $this->config_id()
            ->select('id')
            ->where('parent', 0)
            ->where('aktif', 1)
            ->get('setting_modul')
            ->result_array();
        $parent = array_column($parent, 'id');

        $data = [];

        foreach ($parent as $modul) {
            $data[$modul] = $this->get_submodul($grup, $modul);
            // Juga ambil sub-submodul. Asumsi hanya ada sampai dua tingkat submodul saja
            $subparent = array_column($data[$modul], 'id');

            foreach ($subparent as $submodul) {
                $sub_sub = $this->get_submodul($grup, $submodul);
                if (! empty($sub_sub)) {
                    $data[$modul] = array_merge($data[$modul], $sub_sub);
                }
            }
        }

        return array_filter($data);
    }

    private function get_hak_akses($grup)
    {
        $hak_akses = $this->config_id('a')
            ->select('if (m.url <> "", m.url, concat("Menu ", m.modul)) as url, a.akses')
            ->select('if(a.akses & 1 = 1 or m.parent = 0, 1, 0) as "b"')
            ->select('if(a.akses & 2 = 2, 1, 0) as "u"')
            ->select('if(a.akses & 4 = 4, 1, 0) as "h"')
            ->from('grup_akses a')
            ->join('setting_modul m', 'm.id = a.id_modul')
            ->where('id_grup', $grup)
            ->order_by('akses DESC')
            ->get()
            ->result_array();

        // Hilangkan kolom modul
        $akses_saja = $hak_akses;
        delete_col($akses_saja, 0);

        // Buat array dengan key modul
        return array_combine(array_column($hak_akses, 'url'), $akses_saja);
    }

    /*
     * Memilih modul awal yg dapat diakses
     * Digunakan menentukan modul awal di donjo-app/controllers/Main.php
     */
    public function modul_awal($grup)
    {
        if (! $this->session->hak_akses_url) {
            $this->session->hak_akses_url = $this->get_hak_akses($grup);
        }

        $modul = array_keys($this->session->hak_akses_url);
        if (in_array('beranda', $modul)) {
            return 'beranda';
        }

        if ($modul === []) {
            unset($this->session->hak_akses_url);
            $this->cache->hapus_cache_untuk_semua('_cache_modul');

            redirect('siteman/logout');
        }

        return $this->config_id()
            ->select('url')
            ->where('aktif', 1)
            ->where_in('url', $modul)
            ->limit(1)
            ->get('setting_modul')
            ->row()
            ->url;
    }

    /*
        Cek hak akses url modul untuk mengaktifkan menu navigasi utama.
        Dipanggil dari Modul_model.php
    */
    public function ada_akses_url($grup, $url_modul = '', $akses = '')
    {
        if (! $this->session->hak_akses_url) {
            $hak_akses                    = $this->get_hak_akses($grup);
            $this->session->hak_akses_url = $hak_akses;
        }

        return $this->session->hak_akses_url[$url_modul][$akses];
    }

    /*
     * Cek hak akses untuk mengakses controller.
     * Dipanggil dari Admin_Controller
     */
    public function ada_akses($grup, $controller, $akses)
    {
        if (! $this->session->hak_akses) {
            $hak_akses = $this->get_hak_akses($grup);

            // Simpan akses controller di session, karena hak akses adalah menurut controller dan
            // belum berdasarkan masing2 aksi. Gunakan hak akses tertinggi per controller.
            // $hak_akses sudah terurut hak akses tertinggi duluan
            $akses_controller = [];

            foreach ($hak_akses as $url => $akses_url) {
                $controller_url = explode('/', $url)[0];
                if ($akses_controller[$controller_url]) {
                    continue;
                }
                $akses_controller[$controller_url] = $akses_url;
            }

            $this->session->hak_akses = $akses_controller;
        }

        return $this->session->hak_akses[$controller][$akses];
    }
}
