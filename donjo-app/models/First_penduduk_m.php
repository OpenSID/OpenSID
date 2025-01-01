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

defined('BASEPATH') || exit('No direct script access allowed');

class First_penduduk_m extends MY_Model
{
    public function wilayah()
    {
        $sql = "SELECT u.*, a.nama AS nama_kadus, a.nik AS nik_kadus,
		(SELECT COUNT(rw.id) FROM tweb_wil_clusterdesa rw WHERE dusun = u.dusun AND rw <> '-' AND rt = '-') AS jumlah_rw,
		(SELECT COUNT(v.id) FROM tweb_wil_clusterdesa v WHERE dusun = u.dusun AND v.rt <> '0' AND v.rt <> '-') AS jumlah_rt,
		(SELECT COUNT(p.id) FROM tweb_penduduk p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa WHERE dusun = u.dusun) and status_dasar=1) AS jumlah_warga,
		(SELECT COUNT(p.id) FROM tweb_penduduk p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa WHERE dusun = u.dusun) AND p.sex = 1 and status_dasar = 1) AS jumlah_warga_l,
		(SELECT COUNT(p.id) FROM tweb_penduduk p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa WHERE dusun = u.dusun) AND p.sex = 2 and status_dasar = 1) AS jumlah_warga_p,
		(SELECT COUNT(p.id) FROM tweb_keluarga k inner join tweb_penduduk p ON k.nik_kepala = p.id  WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa WHERE dusun = u.dusun) AND p.kk_level = 1 and status_dasar = 1) AS jumlah_kk
		FROM tweb_wil_clusterdesa u LEFT JOIN tweb_penduduk a ON u.id_kepala = a.id WHERE u.rt = '0' AND u.rw = '0'  ";

        $this->db->query($sql);
        $data = $this->config_id()
            ->select("
                u.*, a.nama AS nama_kadus, a.nik AS nik_kadus,
                (SELECT COUNT(rw.id) FROM tweb_wil_clusterdesa rw WHERE rw.config_id = u.config_id AND dusun = u.dusun AND rw <> '-' AND rt = '-') AS jumlah_rw,
                (SELECT COUNT(v.id) FROM tweb_wil_clusterdesa v WHERE v.config_id = u.config_id AND dusun = u.dusun AND v.rt <> '0' AND v.rt <> '-') AS jumlah_rt,
                (SELECT COUNT(p.id) FROM tweb_penduduk p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa c WHERE c.config_id = u.config_id AND c.dusun = u.dusun) and status_dasar=1) AS jumlah_warga,
                (SELECT COUNT(p.id) FROM tweb_penduduk p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa c WHERE c.config_id = u.config_id AND c.dusun = u.dusun) AND p.sex = 1 and status_dasar = 1) AS jumlah_warga_l,
                (SELECT COUNT(p.id) FROM tweb_penduduk p WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa c WHERE c.config_id = u.config_id AND c.dusun = u.dusun) AND p.sex = 2 and status_dasar = 1) AS jumlah_warga_p,
                (SELECT COUNT(p.id) FROM tweb_keluarga k inner join tweb_penduduk p ON k.nik_kepala = p.id  WHERE p.id_cluster IN(SELECT id FROM tweb_wil_clusterdesa c WHERE c.config_id = u.config_id AND dusun = u.dusun) AND p.kk_level = 1 and status_dasar = 1) AS jumlah_kk
            ")
            ->from('tweb_wil_clusterdesa u')
            ->join('tweb_penduduk a', 'u.id_kepala = a.id', 'left')
            ->where('u.rt', '0')
            ->where('u.rw', '0')
            ->order_by('u.dusun')
            ->get()
            ->result_array();
        //Formating Output
        $counter = count($data);

        //Formating Output
        for ($i = 0; $i < $counter; $i++) {
            $data[$i]['no'] = $i + 1;
        }

        return $data;
    }

    // TODO: OpenKAB - Sesuaikan jika Modul Admin sudah disesuaikan
    public function master_indikator()
    {
        return $this->config_id('u')
            ->select('m.id, m.nama AS master, m.subjek_tipe, s.subjek, p.nama AS periode, p.tahun_pelaksanaan AS tahun,  p.id AS id_periode')
            ->distinct()
            ->from('analisis_indikator u')
            ->join('analisis_master m', 'u.id_master = m.id', 'left')
            ->join('analisis_ref_subjek s', 'm.subjek_tipe = s.id', 'left')
            ->join('analisis_periode p', 'p.id_master = m.id', 'left')
            ->where('u.is_publik', 1)
            ->where('p.aktif', 1)
            ->order_by('m.nama')
            ->get()
            ->result_array();
    }

    // TODO: OpenKAB - Sesuaikan jika Modul Admin sudah disesuaikan
    public function list_indikator($master = null)
    {
        if (empty($master)) {
            $master = $this->master_indikator()[0]['id'];
        }

        $data = $this->config_id('u')
            ->select('u.id, u.nomor, u.id_master, u.pertanyaan AS indikator, s.subjek, p.nama AS periode, p.tahun_pelaksanaan AS tahun, m.nama AS master, m.subjek_tipe, p.id AS id_periode')
            ->from('analisis_indikator u')
            ->join('analisis_master m', 'u.id_master = m.id', 'left')
            ->join('analisis_ref_subjek s', 'm.subjek_tipe = s.id', 'left')
            ->join('analisis_periode p', 'p.id_master = m.id', 'left')
            ->where('u.is_publik', 1)
            ->where('p.aktif', 1)
            ->where('u.id_master', $master)
            ->order_by('LPAD(u.nomor, 10, " ")')
            ->get()
            ->result_array();
        $counter = count($data);

        for ($i = 0; $i < $counter; $i++) {
            $data[$i]['no'] = $i + 1;
        }

        return $data;
    }

    // TODO: OpenKAB - Sesuaikan jika Modul Admin sudah disesuaikan
    public function get_indikator($id = 0)
    {
        return $this->config_id()
            ->select('id_master, pertanyaan')
            ->from('analisis_indikator')
            ->where('id', $id)
            ->get()->row_array();
    }

    // TODO: OpenKAB - Sesuaikan jika Modul Admin sudah disesuaikan
    public function list_jawab($id = 0, $sb = 0, $per = 0)
    {
        $data = $this->db
            ->select('*')
            ->from('analisis_parameter')
            ->where('id_indikator', $id)
            ->order_by('kode_jawaban ASC')
            ->get()
            ->result_array();
        $counter = count($data);

        for ($i = 0; $i < $counter; $i++) {
            switch ($sb) {
                case 1: $this->db->join('tweb_penduduk p', 'r.id_subjek = p.id', 'left')
                    ->join('tweb_wil_clusterdesa a', 'p.id_cluster = a.id', 'left');
                    break;

                case 2: $this->db->join('tweb_keluarga v', 'r.id_subjek = v.id', 'left')
                    ->join('tweb_penduduk p', 'v.nik_kepala = p.id', 'left')
                    ->join('tweb_wil_clusterdesa a', 'p.id_cluster = a.id', 'left');
                    break;

                case 3: $this->db->join('tweb_rtm v', 'r.id_subjek = v.id', 'left')
                    ->join('tweb_penduduk p', 'v.nik_kepala = p.id', 'left')
                    ->join('tweb_wil_clusterdesa a', 'p.id_cluster = a.id', 'left');
                    break;

                case 4: $this->db->join('kelompok v', 'r.id_subjek = v.id', 'left')
                    ->join('tweb_penduduk p', 'v.id_ketua = p.id', 'left')
                    ->join('tweb_wil_clusterdesa a', 'p.id_cluster = a.id', 'left');
                    break;
            }

            $jml = $this->db
                ->select('COUNT(r.id_subjek) AS jml')
                ->from('analisis_respon r')
                ->where('r.id_parameter', $data[$i]['id'])
                ->where('r.id_periode', $per)
                ->get()
                ->row()
                ->jml;
            $data[$i]['nilai'] = $jml;
            $data[$i]['no']    = $i + 1;
        }

        return $data;
    }
}
