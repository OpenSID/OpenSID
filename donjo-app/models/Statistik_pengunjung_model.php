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
 * Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2023 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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

    /**
     * Counter pengunjung visitor.
     *
     * @return void
     */
    public function counter_visitor()
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

    /**
     * Sistem operasi pengunjung.
     *
     * @return string
     */
    public function os()
    {
        return $this->agent->platform();
    }

    /**
     * IP Address pengunjung.
     *
     * @return string
     */
    public function ip_address()
    {
        return $this->input->ip_address();
    }

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

    /**
     * Data mixed jumlah statitik pengunjung.
     *
     * @param int|null
     * @param mixed $type
     *
     * @return mixed
     */
    public function get_pengunjung($type)
    {
        $tgl = date('Y-m-d');
        $bln = date('m');
        $thn = date('Y');

        $this->config_id()->select_sum('Jumlah');

        switch ($type) {
            // Hari Ini
            case 1:
                $this->db->select('Tanggal');
                $this->kondisi($type);
                $this->db->group_by('Tanggal');

                $data['lblx']  = 'Tanggal';
                $data['judul'] = 'Hari Ini ( ' . tgl_indo2($tgl) . ')';
                break;

                // Kemarin
            case 2:
                $this->db->select('Tanggal');
                $this->kondisi($type);
                $this->db->group_by('Tanggal');

                $data['lblx']  = 'Tanggal';
                $data['judul'] = 'Kemarin ( ' . tgl_indo2($this->op_tgl('-1 days', $tgl)) . ')';
                break;

                // 7 Hari (Minggu Ini)
            case 3:
                $this->db->select('Tanggal');
                $this->kondisi($type);
                $this->db->group_by('Tanggal');

                $data['lblx']  = 'Tanggal';
                $data['judul'] = 'Dari Tanggal ' . tgl_indo2($this->op_tgl('-6 days', $tgl)) . ' - ' . tgl_indo2($tgl);
                break;

                // 1 bulan(tgl 1 sampai akhir bulan)
            case 4:
                $this->db->select('Tanggal');
                $this->kondisi($type);
                $this->db->group_by('Tanggal');

                $data['lblx']  = 'Tanggal';
                $data['judul'] = 'Bulan ' . ucwords(getBulan($bln)) . ' ' . $thn;
                break;

                // 1 tahun / 12 Bulan
            case 5:
                $this->db->select('MONTH(`Tanggal`) AS Tanggal');
                $this->kondisi($type);
                $this->db->group_by('MONTH(`Tanggal`)');

                $data['lblx']  = 'Bulan';
                $data['judul'] = 'Tahun ' . $thn;
                break;

                // Semua Data
            default:
                $this->db->select('YEAR(`Tanggal`) AS Tanggal');
                $this->db->group_by('YEAR(`Tanggal`)');

                $data['lblx']  = 'Tahun';
                $data['judul'] = 'Setiap Tahun';
                break;
        }

        $this->db->order_by('Tanggal', 'asc');
        $pengunjung         = $this->db->get($this->table)->result_array();
        $data['pengunjung'] = $pengunjung;

        $jml = 0;

        foreach ($pengunjung as $total) {
            $jml += $total['Jumlah'];
        }

        $data['Total'] = $jml;

        return $data;
    }

    /**
     * Get pengungunjung hari ini.
     *
     * @return object
     */
    public function get_pengunjung_hari_ini()
    {
        return $this->config_id_exist($this->table)->where('Tanggal', date('Y-m-d'))->get($this->table)->row();
    }

    /**
     * Get pengunjung kemarin
     *
     * @return object
     */
    public function get_pengunjung_kemarin()
    {
        return $this->get_pengunjung_total(2);
    }

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

        return $this->config_id_exist($this->table)->get($this->table)->row()->Jumlah;
    }

    /**
     * Insert visitor hari ini
     *
     * @return void
     */
    public function insert_visitor()
    {
        if (! $this->db->field_exists('config_id', $this->table)) {
            return false;
        }

        $insert = [
            'Tanggal'   => date('Y-m-d'),
            'ipAddress' => json_encode(['ip_address' => [$this->ip_address()]]),
            'Jumlah'    => 1,
            'config_id' => identitas('id'),
        ];

        return $this->db->insert($this->table, $insert);
    }

    /**
     * Increment visitor hari ini.
     *
     * @param int jumlah
     * @param json $lastIpAddress
     *
     * @return void
     */
    public function increment_visitor(int $jumlah, $lastIpAddress)
    {
        $ip_address = json_decode($lastIpAddress, true);

        $this->config_id_exist($this->table)
            ->where('Tanggal', date('Y-m-d'))
            ->update($this->table, [
                'ipAddress' => json_encode(['ip_address' => array_merge([$this->ip_address()], $ip_address['ip_address'])]),
                'Jumlah'    => $jumlah + 1,
            ]);
    }

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
