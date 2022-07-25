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

defined('BASEPATH') || exit('No direct script access allowed');

class Pendapat_model extends MY_Model
{
    protected $table = 'pendapat';

    public function __construct()
    {
        parent::__construct();
    }

    public function insert(array $data)
    {
        $this->db->insert($this->table, $data);

        return $this->db->affected_rows();
    }

    public function get_pilihan($tipe, $pilih)
    {
        $this->db->where('pilihan', $pilih);
        $data = $this->get_pendapat($tipe);

        return $data['total'];
    }

    public function get_data($tipe)
    {
        $kondisi = $this->kondisi($tipe);

        return $this->db
            ->select('p.nama, u.pengguna, DATE(tanggal) AS tanggal, pilihan')
            ->from('pendapat u')
            ->join('tweb_penduduk p', 'p.id = u.pengguna', 'left')
            ->where($kondisi['where'])
            ->order_by('u.tanggal desc')
            ->get()
            ->result_array();
    }

    public function get_pendapat($tipe)
    {
        $kondisi  = $this->kondisi($tipe);
        $pendapat = $this->db
            ->select('COUNT(pilihan) AS jumlah, pilihan')
            ->where($kondisi['where'])
            ->group_by('pilihan')
            ->order_by('pilihan')
            ->get($this->table)
            ->result_array();

        $total = 0;

        foreach ($pendapat as $jumlah) {
            $total += $jumlah['jumlah'];
        }

        return [
            'lblx'     => $kondisi['lblx'],
            'judul'    => $kondisi['judul'],
            'pendapat' => $pendapat,
            'total'    => $total,
        ];
    }

    protected function kondisi($tipe)
    {
        $tgl = date('Y-m-d');
        $bln = date('m');
        $thn = date('Y');

        $lblx = 'TANGGAL';

        switch ($tipe) {
            // Hari ini
            case 1:
                $judul = 'Hari Ini ( ' . tgl_indo2($tgl) . ')';
                $where = [
                    'DATE(`tanggal`) = ' => $tgl,
                ];
                break;

                // Kemarin
            case 2:
                $judul = 'Kemarin ( ' . tgl_indo2($this->op_tgl('-1 days', $tgl)) . ')';
                $where = [
                    'DATE(`tanggal`) = ' => $this->op_tgl('-1 days', $tgl),
                ];
                break;

                // Minggu ini
            case 3:
                $judul = 'Dari tanggal ' . tgl_indo2($this->op_tgl('-6 days', $tgl)) . ' - ' . tgl_indo2($tgl);
                $where = [
                    'DATE(`tanggal`) >= ' => $this->op_tgl('-6 days', $tgl),
                    'DATE(`tanggal`) <= ' => $tgl,
                ];
                break;

                // Bulan ini
            case 4:
                $judul = 'Bulan ' . ucwords(getBulan($bln)) . ' ' . $thn;
                $where = [
                    'MONTH(`tanggal`) = ' => $bln,
                    'YEAR(`tanggal`)  = ' => $thn,
                ];
                break;

                // Tahun ini
            case 5:
                $lblx  = 'BULAN';
                $judul = 'Tahun ' . $thn;
                $where = [
                    'YEAR(tanggal) = ' => $thn,
                ];
                break;

                // Semua jumlah pendapat
            default:
                $lblx  = 'TAHUN';
                $judul = 'Setiap Tahun';
                $where = [
                    'tanggal != ' => 'NOT NULL',
                ];
                break;
        }

        return [
            'lblx'   => $lblx,
            'judul'  => $judul,
            'select' => $select,
            'where'  => $where,
        ];
    }

    protected function op_tgl(string $op, string $tgl)
    {
        return date('Y-m-d', strtotime($op, strtotime($tgl)));
    }
}
