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

class Pembangunan_model extends MY_Model
{
    public const ENABLE     = 1;
    public const DISABLE    = 0;
    public const ORDER_ABLE = [
        2 => 'p.judul',
        3 => 'p.sumber_dana',
        4 => 'p.anggaran',
        5 => 'max_persentase',
        6 => 'p.volume',
        7 => 'p.tahun_anggaran',
        8 => 'p.pelaksana_kegiatan',
        9 => 'alamat',
    ];

    protected $tipe  = 'rencana';
    protected $table = 'pembangunan';

    public function set_tipe(string $tipe)
    {
        $this->tipe = $tipe;

        return $this;
    }

    public function get_data(string $search = '', $tahun = 'semua')
    {
        $this->lokasi_pembangunan_query();
        $this->db->select([
            'p.*',
            'IF(p.sifat_proyek = "BARU", "&#10004", "-") AS sifat_proyek_baru',
            'IF(p.sifat_proyek = "LANJUTAN", "&#10004", "-") AS sifat_proyek_lanjutan',
            '(CASE WHEN MAX(CAST(d.persentase AS UNSIGNED INTEGER)) IS NOT NULL THEN CONCAT(MAX(CAST(d.persentase as UNSIGNED INTEGER)), "%") ELSE CONCAT("belum ada progres") END) AS max_persentase',
            'IF(p.perubahan_anggaran = 0, p.anggaran, p.perubahan_anggaran) AS jml_anggaran',
        ])
            ->from("{$this->table} p")
            ->join('pembangunan_ref_dokumentasi d', 'd.id_pembangunan = p.id', 'left')
            ->join('tweb_wil_clusterdesa w', 'p.id_lokasi = w.id', 'left')
            ->group_by('p.id');

        $this->get_tipe();
        $this->config_id('p');

        if ($search !== '' && $search !== '0') {
            $this->db
                ->group_start()
                ->like('p.sumber_dana', $search)
                ->or_like('p.judul', $search)
                ->or_like('p.keterangan', $search)
                ->or_like('p.volume', $search)
                ->or_like('p.tahun_anggaran', $search)
                ->or_like('p.pelaksana_kegiatan', $search)
                ->or_like('p.lokasi', $search)
                ->or_like('p.anggaran', $search)
                ->group_end();
        }

        if ($tahun !== 'semua') {
            $this->db->where('p.tahun_anggaran', $tahun);
        }

        return $this->db;
    }

    public function paging_pembangunan($page_number = 1)
    {
        $jml_data = $this->get_data('', 'semua')->count_all_results();

        return $this->paginasi($page_number, $jml_data);
    }

    public function list_lokasi_pembangunan($status = null)
    {
        $this->lokasi_pembangunan_query();
        $this->config_id('p');

        if (null !== $status) {
            $this->db->where('p.status = 1');
        }

        return $this->db
            ->select('p.*')
            ->from("{$this->table} p")
            ->join('tweb_wil_clusterdesa w', 'p.id_lokasi = w.id', 'left')
            ->get()
            ->result();
    }

    public function slug($slug = null)
    {
        $this->lokasi_pembangunan_query();
        $this->config_id('p');

        return $this->db->select('p.*')
            ->from("{$this->table} p")
            ->join('tweb_wil_clusterdesa w', 'p.id_lokasi = w.id', 'left')
            ->where('p.slug', $slug)
            ->get()
            ->row();
    }

    public function list_filter_tahun()
    {
        $this->config_id('p');
        $this->get_tipe();

        return $this->db
            ->select('tahun_anggaran')
            ->distinct()
            ->from("{$this->table} as p")
            ->join('pembangunan_ref_dokumentasi d', 'd.id_pembangunan = p.id', 'left')
            ->group_by('p.id')
            ->order_by('tahun_anggaran', 'DESC')
            ->get()
            ->result();
    }

    public function get_tipe(): void
    {
        if (empty($this->tipe)) {
            return;
        } // Untuk semua pembangunan

        if ($this->tipe == 'kegiatan') {
            $this->db->where('d.persentase !=', null);
            $this->db->where('d.persentase !=', '100%');
        }

        if ($this->tipe == 'rencana') {
            $this->db->where('d.persentase is NULL', null, false);
        }

        if ($this->tipe == 'hasil') {
            $this->db->where('d.persentase !=', null);
            $this->db->where('d.persentase =', '100%');
        }
    }

    protected function lokasi_pembangunan_query()
    {
        $this->db->select(
            "(CASE WHEN p.id_lokasi = w.id THEN CONCAT(
				(CASE WHEN w.rt != '0' THEN CONCAT('RT ', w.rt, ' / ') ELSE '' END),
				(CASE WHEN w.rw != '0' THEN CONCAT('RW ', w.rw, ' - ') ELSE '' END),
				w.dusun
			) ELSE CASE WHEN p.lokasi IS NOT NULL THEN p.lokasi ELSE '=== Lokasi Tidak Ditemukan ===' END END) AS alamat"
        );
    }
}
