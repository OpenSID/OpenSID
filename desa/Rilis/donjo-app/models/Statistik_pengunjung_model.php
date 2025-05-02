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

class Statistik_pengunjung_model extends MY_Model
{
    /**
     * Table sys_traffic
     *
     * @var string
     */
    protected $table = 'sys_traffic';

    /**
     * Constructor statistik pengunjung.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->library('user_agent');
    }

    // TODO:: Hapus ini, masih dipanggil di MY_Controller untuk menghitung pengunjung
    /**
     * Counter pengunjung visitor.
     */
    public function counter_visitor(): void
    {
        if (isset($this->session->pengunjungOnline) || null === identitas()) {
            return;
        }

        if (null === ($visitor = $this->get_pengunjung_hari_ini())) {
            $this->insert_visitor();
        } else {
            $this->increment_visitor((int) $visitor->Jumlah, $visitor->ipAddress);
        }

        $this->session->set_userdata('pengunjungOnline', date('Y-m-d'));
    }

    // TODO:: Hapus ini, masih dipanggil di modul ini
    /**
     * Sistem operasi pengunjung.
     *
     * @return string
     */
    public function os()
    {
        return $this->agent->platform();
    }

    // TODO:: Hapus ini, masih dipanggil di modul ini
    /**
     * IP Address pengunjung.
     *
     * @return string
     */
    public function ip_address()
    {
        return $this->input->ip_address();
    }

    // TODO:: Hapus ini, masih dipanggil di modul ini
    /**
     * Browser pengunjung.
     *
     * @return string
     */
    public function browser()
    {
        if ($this->agent->is_browser()) {
            $browser = $this->agent->browser() . ' ' . $this->agent->version();
        } elseif ($this->agent->is_robot()) {
            $browser = $this->agent->robot();
        } elseif ($this->agent->is_mobile()) {
            $browser = $this->agent->mobile();
        } else {
            $browser = 'Tidak ditemukan';
        }

        return $browser;
    }

    // TODO:: Hapus ini, masih dipanggil di modul ini
    /**
     * Get pengungunjung hari ini.
     *
     * @return object
     */
    public function get_pengunjung_hari_ini()
    {
        return $this->config_id()->where('Tanggal', date('Y-m-d'))->get($this->table)->row();
    }

    // TODO:: Hapus ini, masih dipanggil di modul ini
    /**
     * Get pengunjung kemarin
     *
     * @return object
     */
    public function get_pengunjung_kemarin()
    {
        return $this->get_pengunjung_total(2);
    }

    // TODO:: Hapus ini, masih dipanggil di modul ini
    /**
     * Total pengunjung total jumlah.
     *
     * @param int|null
     * @param mixed|null $type
     *
     * @return object
     */
    public function get_pengunjung_total($type = null)
    {
        $this->db->select_sum('Jumlah');
        $this->kondisi($type);

        return $this->config_id()->get($this->table)->row()->Jumlah;
    }

    /**
     * Insert visitor hari ini
     *
     * @return void
     */
    public function insert_visitor()
    {
        $insert = [
            'Tanggal'   => date('Y-m-d'),
            'ipAddress' => json_encode(['ip_address' => [$this->ip_address()]]),
            'Jumlah'    => 1,
            'config_id' => identitas('id'),
        ];

        return $this->db->insert($this->table, $insert);
    }

    // TODO:: Hapus ini, masih dipanggil di modul ini
    /**
     * Increment visitor hari ini.
     *
     * @param int jumlah
     * @param json $lastIpAddress
     */
    public function increment_visitor(int $jumlah, $lastIpAddress): void
    {
        $ip_address = json_decode($lastIpAddress, true);

        $this->config_id()
            ->where('Tanggal', date('Y-m-d'))
            ->update($this->table, [
                'ipAddress' => json_encode(['ip_address' => array_merge([$this->ip_address()], $ip_address['ip_address'] ?? [])], JSON_THROW_ON_ERROR),
                'Jumlah'    => $jumlah + 1,
            ]);
    }

    // TODO:: Hapus ini, masih dipanggil di statistik pengunjung
    /**
     * Get statistik pengunjung.
     *
     * @return array
     */
    public function get_statistik()
    {
        return [
            'hari_ini'   => $this->get_pengunjung_hari_ini()->Jumlah,
            'kemarin'    => $this->get_pengunjung_kemarin(),
            'total'      => $this->get_pengunjung_total(),
            'os'         => $this->os(),
            'ip_address' => $this->ip_address(),
            'browser'    => $this->browser(),
        ];
    }

    // TODO:: Hapus ini, masih dipanggil di modul ini
    /**
     * Where clause kondisi tanggal.
     *
     * @param int|null $type
     *
     * @return void
     */
    protected function kondisi($type)
    {
        $tgl = date('Y-m-d');
        $bln = date('m');
        $thn = date('Y');

        switch ($type) {
            // Hari ini
            case 1:
                $this->db->where('Tanggal', $tgl);
                break;

                // Kemarin
            case 2:
                $this->db->where('Tanggal', $this->op_tgl('-1 days', $tgl));
                break;

                // Minggu ini
            case 3:
                $this->db->where([
                    'Tanggal >=' => $this->op_tgl('-6 days', $tgl),
                    'Tanggal <=' => $tgl,
                ]);
                break;

                // Bulan ini
            case 4:
                $this->db->where([
                    'MONTH(`Tanggal`) = ' => $bln,
                    'YEAR(`Tanggal`)  = ' => $thn,
                ]);
                break;

                // Tahun ini
            case 5:
                $this->db->where('YEAR(Tanggal) =', $thn);
                break;

                // Semua jumlah pengunjung
            default:
                break;
        }
    }

    // TODO:: Hapus ini, masih dipanggil di modul ini
    /**
     * Rentang tanggal.
     *
     * @return string
     */
    protected function op_tgl(string $op, string $tgl)
    {
        return date('Y-m-d', strtotime($op, strtotime($tgl)));
    }
}
