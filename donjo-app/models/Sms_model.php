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

class Sms_model extends MY_Model
{
    public function paging($p = 1)
    {
        $sql = 'SELECT COUNT(i.ID) AS jml ' . $this->list_data_sql();
        $row = $this->db->query($sql)->row_array();

        return $this->paginasi($p, $row['jml']);
    }

    private function list_data_sql()
    {
        return " FROM inbox i
            LEFT JOIN penduduk_hidup p on i.SenderNumber = p.telepon
            LEFT JOIN kontak k on i.SenderNumber = k.telepon
            WHERE i.config_id = {$this->config_id}";
    }

    public function list_data($o = 0, $offset = 0, $limit = 500)
    {
        //Ordering SQL
        switch ($o) {
            case 1:
                $order_sql = ' ORDER BY i.SenderNumber';
                break;

            case 2:
                $order_sql = ' ORDER BY i.SenderNumber DESC';
                break;

            case 3:
                $order_sql = ' ORDER BY i.Class';
                break;

            case 4:
                $order_sql = ' ORDER BY i.Class DESC';
                break;

            case 5:
                $order_sql = ' ORDER BY i.ReceivingDateTime';
                break;

            case 6:
                $order_sql = ' ORDER BY i.ReceivingDateTime DESC';
                break;

            default:
                $order_sql = ' ORDER BY i.ReceivingDateTime DESC';
        }

        //Paging SQL
        $paging_sql = ' LIMIT ' . $offset . ',' . $limit;

        //Main Query
        $sql = 'SELECT i.*, (CASE WHEN k.id_kontak IS NULL THEN p.nama ELSE k.nama END) AS nama, (CASE WHEN k.id_kontak IS NULL THEN p.telepon ELSE k.telepon END) AS SenderNumber ' . $this->list_data_sql();
        $sql .= $order_sql;
        $sql .= $paging_sql;

        $query = $this->db->query($sql);
        $data  = $query->result_array();

        //Formating Output
        $j       = $offset;
        $counter = count($data);

        for ($i = 0; $i < $counter; $i++) {
            $data[$i]['no'] = $j + 1;
            $j++;
        }

        return $data;
    }

    public function paging_terkirim($p = 1)
    {
        $sql = 'SELECT count(s.ID) as jml ' . $this->list_data_terkirim_sql();
        $row = $this->db->query($sql)->row_array();

        return $this->paginasi($p, $row['jml']);
    }

    private function list_data_terkirim_sql()
    {
        return " FROM sentitems s
            LEFT JOIN penduduk_hidup p on s.DestinationNumber = p.telepon AND s.config_id = p.config_id
            LEFT JOIN kontak k on s.DestinationNumber = k.telepon
            WHERE s.config_id = {$this->config_id}";
    }

    public function list_data_terkirim($o = 0, $offset = 0, $limit = 500)
    {
        //Ordering SQL
        switch ($o) {
            case 1:
                $order_sql = ' ORDER BY s.DestinationNumber';
                break;

            case 2:
                $order_sql = ' ORDER BY s.DestinationNumber DESC';
                break;

            case 3:
                $order_sql = ' ORDER BY s.Class';
                break;

            case 4:
                $order_sql = ' ORDER BY s.Class DESC';
                break;

            case 5:
                $order_sql = ' ORDER BY s.SendingDateTime';
                break;

            case 6:
                $order_sql = ' ORDER BY s.SendingDateTime DESC';
                break;

            default:
                $order_sql = ' ORDER BY s.SendingDateTime DESC';
        }

        //Paging SQL
        $paging_sql = ' LIMIT ' . $offset . ',' . $limit;

        //Main Query
        $sql = 'SELECT s.*, (CASE WHEN k.id_kontak IS NULL THEN p.nama ELSE k.nama END) AS nama, (CASE WHEN k.id_kontak IS NULL THEN p.telepon ELSE k.telepon END) AS DestinationNumber ' . $this->list_data_terkirim_sql();
        $sql .= $order_sql;
        $sql .= $paging_sql;

        $query = $this->db->query($sql);
        $data  = $query->result_array();

        //Formating Output
        $j       = $offset;
        $counter = count($data);

        for ($i = 0; $i < $counter; $i++) {
            $data[$i]['no'] = $j + 1;
            $j++;
        }

        return $data;
    }

    public function paging_tertunda($p = 1)
    {
        $sql = 'SELECT count(u.ID) as jml ' . $this->list_data_tertunda_sql();
        $row = $this->db->query($sql)->row_array();

        return $this->paginasi($p, $row['jml']);
    }

    private function list_data_tertunda_sql()
    {
        return " FROM outbox u
            LEFT JOIN penduduk_hidup p on u.DestinationNumber = p.telepon AND u.config_id = p.config_id
            LEFT JOIN kontak k on u.DestinationNumber = k.telepon
            WHERE u.config_id = {$this->config_id}";
    }

    public function list_data_tertunda($o = 0, $offset = 0, $limit = 500)
    {
        //Ordering SQL
        switch ($o) {
            case 1:
                $order_sql = ' ORDER BY u.DestinationNumber';
                break;

            case 2:
                $order_sql = ' ORDER BY u.DestinationNumber DESC';
                break;

            case 3:
                $order_sql = ' ORDER BY u.Class';
                break;

            case 4:
                $order_sql = ' ORDER BY u.Class DESC';
                break;

            case 5:
                $order_sql = ' ORDER BY u.SendingDateTime';
                break;

            case 6:
                $order_sql = ' ORDER BY u.SendingDateTime DESC';
                break;

            default:
                $order_sql = ' ORDER BY u.SendingDateTime DESC';
        }

        //Paging SQL
        $paging_sql = ' LIMIT ' . $offset . ',' . $limit;

        //Main Query
        $sql = 'SELECT u.*, (CASE WHEN k.id_kontak IS NULL THEN p.nama ELSE k.nama END) AS nama, (CASE WHEN k.id_kontak IS NULL THEN p.telepon ELSE k.telepon END) AS DestinationNumber ' . $this->list_data_tertunda_sql();
        $sql .= $order_sql;
        $sql .= $paging_sql;

        $query = $this->db->query($sql);
        $data  = $query->result_array();

        //Formating Output
        $j       = $offset;
        $counter = count($data);

        for ($i = 0; $i < $counter; $i++) {
            $data[$i]['no'] = $j + 1;
            $j++;
        }

        return $data;
    }

    public function insert(): void
    {
        $post                      = $this->input->post();
        $data['DestinationNumber'] = bilangan($post['DestinationNumber']);
        $data['TextDecoded']       = htmlentities($post['TextDecoded']);
        $data['config_id']         = $this->config_id;
        $outp                      = $this->db->insert('outbox', $data);

        status_sukses($outp); //Tampilkan Pesan
    }

    public function update($id = ''): void
    {
        $post                = $this->input->post();
        $data['TextDecoded'] = htmlentities($post['TextDecoded']);
        $outp                = $this->config_id()->where('ID', $id)->update('outbox', $data);

        status_sukses($outp); //Tampilkan Pesan
    }

    public function delete($Class = 0, $ID = ''): void
    {
        if ($Class == 2) {
            $tabel = 'sentitems';
        } elseif ($Class == 1) {
            $tabel = 'inbox';
        } else {
            $tabel = 'outbox';
        }

        $outp = $this->config_id()->where('ID', $ID)->delete($tabel);

        status_sukses($outp);
    }

    public function deleteAll($Class = 0): void
    {
        $id_cb = $_POST['id_cb'];

        if (count($id_cb) > 0) {
            foreach ($id_cb as $ID) {
                $this->delete($Class, $ID);
            }
        } else {
            $outp = false;
        }

        status_sukses($outp);
    }

    public function get_sms($Class = 0, $ID = 0)
    {
        if ($Class == 2) {
            $query = $this->config_id()->where('ID', $ID)->get('sentitems');
        } elseif ($Class == 1) {
            $query = $this->config_id()->select('SenderNumber AS DestinationNumber,TextDecoded')->where('ID', $ID)->get('inbox');
        } else {
            $query = $this->config_id()->where('ID', $ID)->get('outbox');
        }

        return $query->row_array();
    }

    public function sendBroadcast($data = [])
    {
        $data['config_id'] = $this->config_id;
        $outp              = $this->db->insert('outbox', $data);

        status_sukses($outp);

        return $outp;
    }
}
