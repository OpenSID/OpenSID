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

use App\Libraries\Paging;
use App\Models\FormatSurat;

defined('BASEPATH') || exit('No direct script access allowed');

class Keluar_model extends MY_Model
{
    public function autocomplete()
    {
        $sql   = [];
        $sql[] = '(' . $this->config_id()->select('no_surat')
            ->from('log_surat')
            ->get_compiled_select()
                            . ')';
        $sql[] = '(' . $this->config_id('u')->select(
            '(
                CASE WHEN n.nama IS NOT NULL
                    THEN n.nama
                    ELSE u.nama_non_warga
                END) as nama'
        )
            ->from('log_surat u')
            ->join('tweb_penduduk n', 'u.id_pend = n.id', 'left')
            ->get_compiled_select()
                            . ')';
        $sql[] = '(' . $this->config_id('u')->select('p.nama')
            ->from('log_surat u')
            ->join('tweb_desa_pamong s', 'u.id_pamong = s.pamong_id', 'left')
            ->join('tweb_penduduk p', 's.id_pend = p.id', 'left')
            ->get_compiled_select()
                            . ')';
        $sql  = implode('UNION', $sql);
        $data = $this->db->query($sql)->result_array();

        return autocomplete_data_ke_str($data);
    }

    private function search_sql(): void
    {
        $cari = $this->session->cari;
        if (empty($cari)) {
            $this->db->where('u.id !=', null);
        } else {
            $this->db
                ->group_start()
                ->like('u.no_surat', $cari, 'BOTH')
                ->or_like('n.nama', $cari, 'BOTH')
                ->or_like('s.pamong_nama', $cari, 'BOTH')
                ->or_like('p.nama', $cari, 'BOTH')
                ->group_end();
        }
    }

    private function tahun_sql(): void
    {
        $tahun = $this->session->tahun;
        if (empty($tahun)) {
            $this->db->where('YEAR(u.tanggal) !=', '');
        } else {
            $this->db->where('YEAR(u.tanggal)', $tahun);
        }
    }

    private function bulan_sql(): void
    {
        $bulan = $this->session->bulan;
        if (empty($bulan)) {
            $this->db->where('MONTH(u.tanggal) !=', '');
        } else {
            $this->db->where('MONTH(u.tanggal)', $bulan);
        }
    }

    private function jenis_sql(): void
    {
        $jenis = $this->session->jenis;
        if (empty($jenis)) {
            $this->db->where('u.id_format_surat !=', '');
        } else {
            $this->db->where('u.id_format_surat', $jenis);
        }
    }

    public function navigasi(): void
    {
        $isAdmin = $this->session->isAdmin->pamong;
        if (isset($this->session->masuk)) {
            if ($isAdmin->jabatan_id == kades()->id) {
                if (setting('tte') == 1) {
                    $this->db->where('verifikasi_kades', '0')->or_where('tte', '0');
                } else {
                    $this->db->where('verifikasi_kades', '0');
                }
            } elseif ($isAdmin->jabatan_id == sekdes()->id) {
                $this->db->where('verifikasi_sekdes', '0');
            } else {
                $this->db->where('verifikasi_operator', '0');
            }
        } elseif (isset($this->session->ditolak)) {
            $this->db->where('verifikasi_operator', '-1');
        } else {
            $isAdmin = $this->session->isAdmin->pamong;
            if ($isAdmin->jabatan_id == kades()->id && setting('verifikasi_kades') == 1) {
                $this->db->where('verifikasi_kades', '1')
                    ->or_group_start()
                    ->where('verifikasi_operator')
                    ->where('verifikasi_sekdes')
                    ->group_end();
            } elseif ($isAdmin->jabatan_id == sekdes()->id && setting('verifikasi_sekdes') == 1) {
                $this->db->where('verifikasi_sekdes', '1')->or_where('verifikasi_operator');
            } else {
                $this->db->where('verifikasi_operator', '1')->or_where('verifikasi_operator');
            }
        }
    }

    private function verifikasi(): void
    {
        // jika kepdesa
        $isAdmin = $this->session->isAdmin->pamong;
        if ($isAdmin->jabatan_id == kades()->id && setting('verifikasi_kades') == 1) {
            $this->db->group_start()
                ->where_in('verifikasi_kades', ['1', '0'])
                ->group_end();
            $this->db->select('verifikasi_kades as verifikasi');
            $raw_status_periksa = 'CASE when verifikasi_kades = 1 THEN IF(tte is null,verifikasi_kades,2) ELSE 0 end AS status_periksa';
            $this->db->select($raw_status_periksa);
        } elseif ($isAdmin->jabatan_id == sekdes()->id && setting('verifikasi_sekdes') == 1) {
            $this->db->group_start()
                ->where_in('verifikasi_sekdes', ['1', '0'])
                ->or_where('verifikasi_operator')
                ->group_end();
            $this->db->select('verifikasi_sekdes as verifikasi');
            $raw_status_periksa = 'CASE WHEN verifikasi_sekdes = 1 THEN IF(tte is null,IF(verifikasi_kades is null,1 , verifikasi_kades), tte)
            ELSE 0 end AS status_periksa';
            $this->db->select($raw_status_periksa);
        } else {
            $this->db->select('verifikasi_operator as verifikasi');
            $raw_status_periksa = 'CASE when verifikasi_operator = 1 THEN IF(tte is null,IF(verifikasi_kades is null,IF(verifikasi_sekdes is null, 1, verifikasi_sekdes),verifikasi_kades),tte) ELSE 0 end AS status_periksa';
            $this->db->select($raw_status_periksa);
        }
    }

    private function filterku_sql($nik = ''): void
    {
        if (! empty($nik)) {
            $this->db->where('u.id_pend', $nik);
        }
    }

    public function paging($p = 1, $o = 0)
    {
        $this->list_data_sql();
        $from = $this->db->last_query();

        $row      = $this->db->query("select COUNT(*) AS jml from ({$from}) as data")->row_array();
        $jml_data = $row['jml'];

        $paging          = new Paging();
        $cfg['page']     = $p;
        $cfg['per_page'] = $this->session->per_page;
        $cfg['num_rows'] = $jml_data;
        $paging->init($cfg);

        return $paging;
    }

    private function list_data_sql()
    {
        // TODO : Sederhanakan, ini berulang
        $this->config_id('u')
            ->join('tweb_penduduk AS n', 'u.id_pend = n.id', 'left')
            ->join('tweb_surat_format AS k', 'u.id_format_surat = k.id', 'left')
            ->join('tweb_desa_pamong AS s', 'u.id_pamong = s.pamong_id', 'left')
            ->join('tweb_penduduk AS p', 's.id_pend = p.id', 'left')
            ->join('user AS w', 'u.id_user = w.id', 'left');
        $this->db->where('deleted_at');

        $this->db->group_start();
        $this->search_sql();
        $this->tahun_sql();
        $this->bulan_sql();
        $this->jenis_sql();
        $this->db->group_end();

        $this->db->group_start();
        $this->verifikasi();
        $this->navigasi();
        $this->db->group_end();

        return $this->db->get('log_surat u');
    }

    // TODO:: Ganti cara ini, gunakan App/Models/Pamong.php
    // $limit = 0 mengambil semua
    public function list_data($o = 0, $offset = 0, $limit = null)
    {
        //Ordering SQL
        switch ($o) {
            case 1: $this->db->order_by('(u.no_surat) * 1');
                break;

            case 2: $this->db->order_by('(u.no_surat) * 1 DESC');
                break;

            case 3: $this->db->order_by('nama');
                break;

            case 4: $this->db->order_by('nama', 'DESC');
                break;

            case 5: $this->db->order_by('u.tanggal');
                break;

            case 6: $this->db->order_by('u.tanggal', 'DESC');
                break;

            default:$this->db->order_by('u.tanggal', 'DESC');
        }

        // TODO : Sederhanakan, ini berulang
        $this->db
            ->select('u.*, n.nama AS nama, w.nama AS nama_user, n.nik AS nik, k.nama AS format, k.url_surat as berkas, k.kode_surat as kode_surat, s.id_pend as pamong_id_pend, s.gelar_depan, s.gelar_belakang')
            ->select('u.nama_pamong as pamong_nama')
            ->select('k.url_surat, k.jenis')
            ->where('u.status !=', null)
            ->limit($limit, $offset);

        $data = $this->list_data_sql()->result_array();
        //Formating Output
        $j       = $offset;
        $counter = count($data);

        for ($i = 0; $i < $counter; $i++) {
            $data[$i]['no'] = $j + 1;
            $data[$i]['t']  = $data[$i]['id_pend'];

            if ($data[$i]['id_pend'] == -1) {
                $data[$i]['id_pend'] = 'Masuk';
            } else {
                $data[$i]['id_pend'] = 'Keluar';
                if (in_array($data[$i]['jenis'], FormatSurat::RTF)) {
                    $this->rincian_file($data, $i);
                }
            }

            $j++;
        }

        return $data;
    }

    private function rincian_file(&$data, int $i): void
    {
        $nama_surat = pathinfo($data[$i]['nama_surat'], PATHINFO_FILENAME);

        if ($nama_surat !== '' && $nama_surat !== '0') {
            $berkas_rtf      = $nama_surat . '.rtf';
            $berkas_pdf      = $nama_surat . '.pdf';
            $berkas_lampiran = $nama_surat . '_lampiran.pdf';
        } else {
            $berkas_rtf      = $data[$i]['berkas'] . '_' . $data[$i]['nik'] . '_' . date('Y-m-d') . '.rtf';
            $berkas_pdf      = $data[$i]['berkas'] . '_' . $data[$i]['nik'] . '_' . date('Y-m-d') . '.pdf';
            $berkas_lampiran = $data[$i]['berkas'] . '_' . $data[$i]['nik'] . '_' . date('Y-m-d') . '._lampiran.pdf';
        }

        $data[$i]['file_rtf']      = LOKASI_ARSIP . $berkas_rtf;
        $data[$i]['file_pdf']      = LOKASI_ARSIP . $berkas_pdf;
        $data[$i]['file_lampiran'] = LOKASI_ARSIP . $berkas_lampiran;
    }

    public function update_keterangan($id, $data): void
    {
        $outp = $this->config_id()
            ->where('id', $id)
            ->update('log_surat', $data);

        status_sukses($outp); //Tampilkan Pesan
    }

    public function paging_perorangan($nik = '', $p = 1, $o = 0)
    {
        if (! empty($nik)) {
            $this->db->select('count(*) as jml');
            $row      = $this->list_data_perorangan_sql($nik)->row_array();
            $jml_data = $row['jml'];
        } else {
            $jml_data = 0;
        }

        $paging          = new Paging();
        $cfg['page']     = $p;
        $cfg['per_page'] = $this->session->per_page;
        $cfg['num_rows'] = $jml_data;
        $paging->init($cfg);

        return $paging;
    }

    private function list_data_perorangan_sql($nik)
    {
        // TODO : Sederhanakan, ini berulang
        $this->config_id('u')
            ->where('deleted_at')
            ->join('tweb_penduduk AS n', 'u.id_pend = n.id', 'left')
            ->join('tweb_surat_format AS k', 'u.id_format_surat = k.id', 'left')
            ->join('tweb_desa_pamong AS s', 'u.id_pamong = s.pamong_id', 'left')
            ->join('tweb_penduduk AS p', 's.id_pend = p.id', 'left')
            ->join('user AS w', 'u.id_user = w.id', 'left');
        $this->filterku_sql($nik);

        return $this->db->get('log_surat u');
    }

    public function list_data_perorangan($nik = '', $o = 0, $offset = 0, $limit = 500)
    {
        if (empty($nik)) {
            return [];
        }

        //Ordering SQL
        switch ($o) {
            case 1: $this->db->order_by('(u.no_surat) * 1');
                break;

            case 2: $this->db->order_by('(u.no_surat) * 1 DESC');
                break;

            case 3: $this->db->order_by('nama');
                break;

            case 4: $this->db->order_by('nama', 'DESC');
                break;

            case 5: $this->db->order_by('u.tanggal');
                break;

            case 6: $this->db->order_by('u.tanggal', 'DESC');
                break;

            default:  $this->db->order_by('u.tanggal', 'DESC');
        }

        // TODO : Sederhanakan, ini berulang
        $this->db->select('u.*, n.nama AS nama, w.nama AS nama_user, n.nik AS nik, k.nama AS format, k.url_surat as berkas, k.kode_surat as kode_surat')
            ->select('(case when p.nama is not null then p.nama else s.pamong_nama end) as pamong_nama')
            ->limit($limit, $offset);

        $data = $this->list_data_perorangan_sql($nik)->result_array();

        //Formating Output
        $j       = $offset;
        $counter = count($data);

        for ($i = 0; $i < $counter; $i++) {
            $data[$i]['no'] = $j + 1;
            $this->rincian_file($data, $i);
            $j++;
        }

        return $data;
    }

    public function nama_surat_arsip($url, $nik, $nomor)
    {
        // Nama surat untuk surat keterangan di mana NIK = 1234567890123456 dan
        // nomor surat = 503/V.58.IV.135/III pada tanggal 27 Juli 2016 akan seperti ini:
        // surat_ket_pengantar_1234567890123456_2016-07-27_503-V.58.IV.135-III.rtf
        $nomor_surat = str_replace("'", '', $nomor);
        $nomor_surat = preg_replace('/[^a-zA-Z0-9.	]/', '-', $nomor_surat);

        return $url . '_' . $nik . '_' . date('Y-m-d') . '_' . $nomor_surat . '.rtf';
    }

    public function log_surat($data_log_surat): void
    {
        $url_surat  = $data_log_surat['url_surat'];
        $nama_surat = $data_log_surat['nama_surat'];
        unset($data_log_surat['url_surat'], $data_log_surat['pamong_nama']);

        foreach ($data_log_surat as $key => $val) {
            $data[$key] = $val;
        }

        $query = $this->config_id()
            ->select('id')
            ->where('url_surat', $url_surat)
            ->get('tweb_surat_format');
        if ($query->num_rows() > 0) {
            $pam                     = $query->row_array();
            $data['id_format_surat'] = $pam['id'];
        } else {
            $data['id_format_surat'] = $url_surat;
        }

        $data['id_pamong'] = $data_log_surat['id_pamong'];
        if ($data['id_pamong'] == '') {
            $data['id_pamong'] = 1;
        }

        $data['bulan']   = date('m');
        $data['tahun']   = date('Y');
        $data['tanggal'] = date('Y-m-d H:i:s');
        $data['status']  = 1; // Cetak
        //print_r($data);
        if (! empty($nama_surat)) { /**
            Ekspor Dok:
            Penambahan atau update log disesuaikan dengan file surat yang tersimpan di arsip,
            sehingga hanya ada satu entri di log surat untuk setiap versi surat di arsip.
            File surat disimpan di arsip untuk setiap URL-NIK-nomor surat-tanggal yang unik,
            lihat fungsi nama_surat_arsip (kolom nama_surat di tabel log_surat).
            Entri itu akan berisi timestamp (pencetakan) terakhir untuk file surat yang bersangkutan.
        */
            $log_id = $this->config_id()->select('id')->from('log_surat')->where('nama_surat', $nama_surat)->where('deleted_at is null')->limit(1)->get()->row()->id;
        } else { // Cetak:
            // Sama dengan aturan Ekspor Dok, hanya URL-NIK-nomor surat-tanggal diambil dari data kolom
            $log_id = $this->config_id()
                ->select('id')
                ->from('log_surat')
                ->where('id_format_surat', $data['id_format_surat'])
                ->where('id_pend', $data['id_pend'])
                ->where('no_surat', $data['no_surat'])
                ->where('DATE_FORMAT(tanggal, "%Y-%m-%d") =', date('Y-m-d'))
                ->where('deleted_at is null')
                ->limit(1)
                ->get()
                ->row()
                ->id;
        }
        if ($log_id) {
            $this->config_id()
                ->where('id', $log_id)
                ->update('log_surat', $data);
        } else {
            $data['config_id'] = identitas('id');
            $this->db->insert('log_surat', $data);
        }
    }

    public function grafik()
    {
        return $this->config_id('l')
            ->select('f.nama, COUNT(l.id) as jumlah')
            ->from('log_surat l')
            ->join('tweb_surat_format f', 'l.id_format_surat=f.id', 'left')
            ->group_by('f.nama')
            ->get()
            ->result_array();
    }

    public function delete($id = ''): void
    {
        $arsip = $this->config_id()
            ->select('nama_surat, lampiran, urls_id, status')
            ->where('id', $id)
            ->get('log_surat')
            ->row_array();

        if ($arsip['status'] == 0) { // jika masih draft maka hapus data
            $output = $this->config_id()->where('id', $id)->delete('log_surat');
        } else {
            $output = $this->config_id()->where('id', $id)->update('log_surat', ['deleted_at' => date('Y-m-d')]);
        }

        status_sukses($output);
    }

    public function list_penduduk()
    {
        $sql   = 'SELECT id, nik, nama FROM tweb_penduduk WHERE status = 1';
        $query = $this->db->query($sql);
        $data  = $query->result_array();
        //Formating Output
        $counter = count($data);

        //Formating Output
        for ($i = 0; $i < $counter; $i++) {
            $data[$i]['alamat'] = 'Alamat :' . $data[$i]['nama'];
        }

        return $data;
    }

    public function list_tahun_surat()
    {
        return $this->config_id()
            ->distinct()
            ->select('YEAR(tanggal) AS tahun')
            ->order_by('YEAR(tanggal)', 'DESC')
            ->get('log_surat')
            ->result_array();
    }

    public function list_bulan_surat()
    {
        return $this->config_id()
            ->distinct()
            ->select('MONTH(tanggal) AS bulan')
            ->where('YEAR(tanggal)', $this->session->tahun)
            ->order_by('MONTH(tanggal)', 'ASC')
            ->get('log_surat')
            ->result_array();
    }

    public function list_jenis_surat()
    {
        return $this->config_id('u')
            ->distinct()
            ->select(['k.id', 'k.nama as nama_surat'])
            ->from('log_surat u')
            ->join('tweb_surat_format k', 'u.id_format_surat = k.id', 'left')
            ->order_by('nama_surat')
            ->where('k.nama is not null')
            ->get()
            ->result_array();
    }

    public function verifikasi_data_surat($id, $kode_desa)
    {
        $this->load->model('penomoran_surat_model');

        $pj = setting('sebutan_pj_kepala_desa');

        $data = $this->config_id('l')
            ->select('l.*,
                    k.nama AS perihal,
                    k.kode_surat,
                    k.format_nomor_global,
                    k.format_nomor,
                    n.nama AS nama_penduduk,
                    concat(if(s.status_pejabat = 1, \'' . $pj . ' \', \'\'), l.nama_jabatan) AS pamong_jabatan')
            ->select('nama_pamong as pamong_nama')
            ->from('log_surat l')
            ->join('tweb_penduduk n', 'l.id_pend = n.id', 'left')
            ->join('tweb_surat_format k', 'l.id_format_surat = k.id', 'left')
            ->join('tweb_desa_pamong s', 'l.id_pamong = s.pamong_id', 'left')
            ->join('ref_jabatan r', 's.jabatan_id = r.id', 'left')
            ->join('tweb_penduduk p', 's.id_pend = p.id', 'left')
            ->where('l.id', $id)
            ->get()
            ->row();

        if (! $data) {
            return false;
        }

        $format['config']['kode_desa'] = $kode_desa;
        $format['input']['nomor']      = $data->no_surat;
        $format['surat']               = [
            'format_nomor_global' => $data->format_nomor_global,
            'format_nomor'        => $data->format_nomor,
            'cek_thn'             => $data->tahun,
            'cek_bln'             => $data->bulan,
            'nomor'               => $data->no_surat,
            'kode_surat'          => $data->kode_surat,
        ];

        $data->nomor_surat = FormatSurat::format_penomoran_surat($format);

        // Filter Output
        $output = [
            'nomor_surat'    => $data->nomor_surat,
            'tanggal'        => $data->tanggal,
            'perihal'        => $data->perihal,
            'nama_penduduk'  => $data->nama_penduduk ?? $data->nama_non_warga,
            'pamong_nama'    => $data->pamong_nama,
            'pamong_jabatan' => $data->pamong_jabatan,
        ];

        return (object) $output;
    }

    public function get_surat($id = 0)
    {
        return $this->config_id()
            ->select('nama_surat, lampiran, keterangan')
            ->where('id', $id)
            ->get('log_surat')
            ->row();
    }
}
