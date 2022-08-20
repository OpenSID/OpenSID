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

class Kelompok_model extends MY_Model
{
    protected $table = 'kelompok';
    protected $tipe  = 'kelompok';

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['wilayah_model', 'referensi_model', 'program_bantuan_model']);
    }

    public function set_tipe(string $tipe)
    {
        $this->tipe = $tipe;

        return $this;
    }

    public function autocomplete()
    {
        return $this->autocomplete_str('nama', $this->table);
    }

    private function search_sql()
    {
        if ($search = $this->session->cari) {
            $this->db
                ->group_start()
                ->like('u.nama', $search)
                ->or_like('u.keterangan', $search)
                ->or_like('c.nama', $search)
                ->group_end();
        }

        return $this->db;
    }

    private function filter_sql()
    {
        if ($filter = $this->session->filter) {
            $this->db->where('u.id_master', $filter);
        }

        return $this->db;
    }

    protected function status_dasar_sql()
    {
        $status_dasar = $this->session->status_dasar;
        if ($status_dasar == 1) {
            $this->db->where('c.status_dasar', 1);
        } elseif ($status_dasar == 2) {
            $this->db->where('c.status_dasar', null);
        }
    }

    private function penerima_bantuan_sql()
    {
        // Yg berikut hanya untuk menampilkan peserta bantuan
        $penerima_bantuan = $this->session->penerima_bantuan;
        if (! in_array($penerima_bantuan, [JUMLAH, BELUM_MENGISI, TOTAL])) {
            // Salin program_id
            $this->session->program_bantuan = $penerima_bantuan;
        }
        if ($penerima_bantuan && $penerima_bantuan != BELUM_MENGISI) {
            if ($penerima_bantuan != JUMLAH && $this->session->program_bantuan) {
                $this->db
                    ->join('program_peserta bt', 'bt.peserta = u.id')
                    ->join('program rcb', 'bt.program_id = rcb.id', 'left');
            }
        }
        // Untuk BUKAN PESERTA program bantuan tertentu
        if ($penerima_bantuan == BELUM_MENGISI) {
            if ($this->session->program_bantuan) {
                // Program bantuan tertentu
                $program_id = $this->session->program_bantuan;
                $this->db
                    ->join('program_peserta bt', "bt.peserta = u.id and bt.program_id = {$program_id}", 'left')
                    ->where('bt.id is null');
            } else {
                // Bukan penerima bantuan apa pun
                $this->db
                    ->join('program_peserta bt', 'bt.peserta = u.id', 'left')
                    ->where('bt.id is null');
            }
        } elseif ($penerima_bantuan == JUMLAH && ! $this->session->program_bantuan) {
            // Penerima bantuan mana pun
            $this->db
                ->where('u.id IN (select peserta from program_peserta)');
        }
    }

    private function list_data_sql()
    {
        $this->db->from("{$this->table} u")
            ->join('kelompok_master s', 'u.id_master = s.id', 'left')
            ->join('penduduk_hidup c', 'u.id_ketua = c.id', 'left')
            ->where('u.tipe', $this->tipe);

        if ($this->session->penerima_bantuan) {
            $this->penerima_bantuan_sql();
        }

        $this->search_sql();
        $this->filter_sql();
        $this->status_dasar_sql();

        $kolom_kode = [
            ['sex', 'c.sex'],
        ];

        foreach ($kolom_kode as $kolom) {
            $this->get_sql_kolom_kode($kolom[0], $kolom[1]);
        }

        return $this->db;
    }

    protected function get_sql_kolom_kode($session, $kolom)
    {
        if (! empty($ss = $this->session->{$session})) {
            if ($ss == JUMLAH) {
                $this->db->where("{$kolom} !=", null);
            } elseif ($ss == BELUM_MENGISI) {
                $this->db->where($kolom, null);
            } else {
                $this->db->where($kolom, $ss);
            }
        }
    }

    public function list_data($o = 0, $page = 0)
    {
        switch ($o) {
            case 1:
                $this->db->order_by('u.nama');
                break;

            case 2:
                $this->db->order_by('u.nama', 'desc');
                break;

            case 3:
                $this->db->order_by('c.nama');
                break;

            case 4:
                $this->db->order_by('c.nama desc');
                break;

            case 5:
                $this->db->order_by('master');
                break;

            case 6:
                $this->db->order_by('master desc');
                break;

            default:
                $this->db->order_by('u.nama');
                break;
        }

        $this->list_data_sql();

        if ($page > 0) {
            $jumlah_pilahan = $this->db->count_all_results('', false);
            $paging         = $this->paginasi($page, $jumlah_pilahan);
            $this->db->limit($paging->per_page, $paging->offset);
        }

        $data = $this->db
            ->select('u.*, s.kelompok AS master, c.nama AS ketua, (SELECT COUNT(id) FROM kelompok_anggota WHERE id_kelompok = u.id) AS jml_anggota')
            ->get()
            ->result_array();

        if ($page > 0) {
            return ['paging' => $paging, 'main' => $data];
        }

        return $data;
    }

    private function validasi($post = [], $id = null)
    {
        if ($post['id_ketua']) {
            $data['id_ketua'] = bilangan($post['id_ketua']);
        }

        $data['id_master']  = bilangan($post['id_master']);
        $data['nama']       = nama_terbatas($post['nama']);
        $data['slug']       = unique_slug($this->table, $data['nama'], $id);
        $data['keterangan'] = htmlentities($post['keterangan']);
        $data['kode']       = nomor_surat_keputusan($post['kode']);
        $data['tipe']       = $this->tipe;

        return $data;
    }

    public function insert()
    {
        $data = $this->validasi($this->input->post());

        if ($this->get_kelompok(null, $data['kode'])) {
            $this->session->success   = -1;
            $this->session->error_msg = "<br/>Kode ini {$data['kode']} tidak bisa digunakan. Silahkan gunakan kode yang lain!";

            return false;
        }

        $outpa     = $this->db->insert($this->table, $data);
        $insert_id = $this->db->insert_id();

        // TODO : Ubah cara penanganan penambahan ketua kelompok, simpan di bagian kelompok anggota
        $outpb = $this->db
            ->set('id_kelompok', $insert_id)
            ->set('id_penduduk', $data['id_ketua'])
            ->set('no_anggota', 1)
            ->set('jabatan', 1)
            ->set('keterangan', "Ketua {$this->tipe}") // keterangan default untuk Ketua Kelompok
            ->set('tipe', $this->tipe)
            ->insert('kelompok_anggota');

        status_sukses($outpa && $outpb);
    }

    private function validasi_anggota($post)
    {
        if ($post['id_penduduk']) {
            $data['id_penduduk'] = bilangan($post['id_penduduk']);
        }

        $data['no_anggota']    = bilangan($post['no_anggota']);
        $data['jabatan']       = alfanumerik_spasi($post['jabatan']);
        $data['no_sk_jabatan'] = nomor_surat_keputusan($post['no_sk_jabatan']);
        $data['keterangan']    = htmlentities($post['keterangan']);
        $data['tipe']          = $this->tipe;

        if ($this->tipe == 'lembaga') {
            $data['nmr_sk_pengangkatan']  = nomor_surat_keputusan($post['nmr_sk_pengangkatan']);
            $data['tgl_sk_pengangkatan']  = ! empty($post['tgl_sk_pengangkatan']) ? tgl_indo_in($post['tgl_sk_pengangkatan']) : null;
            $data['nmr_sk_pemberhentian'] = nomor_surat_keputusan($post['nmr_sk_pemberhentian']);
            $data['tgl_sk_pemberhentian'] = ! empty($post['tgl_sk_pemberhentian']) ? tgl_indo_in($post['tgl_sk_pemberhentian']) : null;
            $data['periode']              = htmlentities($post['periode']);
        }

        return $data;
    }

    public function insert_a($id = 0)
    {
        $data                = $this->validasi_anggota($this->input->post());
        $data['id_kelompok'] = $id;
        $this->ubah_jabatan($data['id_kelompok'], $data['id_penduduk'], $data['jabatan'], null);

        if ($data['id_kelompok']) {
            $validasi_anggota = $this->db
                ->select('id_penduduk, id_kelompok')
                ->from('kelompok_anggota')
                ->where('id_penduduk', $data['id_penduduk'])
                ->where('id_kelompok', $data['id_kelompok'])
                ->limit(1)->get()->row();
        }

        if ($validasi_anggota->id_penduduk == $data['id_penduduk']) {
            $this->session->success   = -1;
            $this->session->error_msg = 'Nama Anggota yang dipilih sudah masuk kelompok';
            redirect("kelompok/form_anggota/{$validasi_anggota->id_kelompok}");

            return false;
        }

        $outp    = $this->db->insert('kelompok_anggota', $data);
        $id_pend = $data['id_penduduk'];
        $nik     = $this->get_anggota($id, $id_pend);

        // Upload foto dilakukan setelah ada id, karena nama foto berisi nik
        if ($foto = upload_foto_penduduk()) {
            $this->db->where('id', $id_pend)->update('tweb_penduduk', ['foto' => $foto]);
        }

        status_sukses($outp); //Tampilkan Pesan
    }

    public function update($id = 0)
    {
        $data = $this->validasi($this->input->post(), $id);

        if ($this->get_kelompok($id, $data['kode'])) {
            $this->session->success   = -1;
            $this->session->error_msg = '<br/>Kode ' . $this->tipe . ' sudah digunakan';

            return false;
        }

        $this->db->where('id', $id);
        $outp = $this->db->update($this->table, $data);

        status_sukses($outp); //Tampilkan Pesan
    }

    public function update_a($id = 0, $id_a = 0)
    {
        $data = $this->validasi_anggota($this->input->post());
        $this->ubah_jabatan($id, $id_a, $data['jabatan'], $this->input->post('jabatan_lama'));

        $outp = $this->db
            ->where('id_penduduk', $id_a)
            ->update('kelompok_anggota', $data);

        $nik = $this->get_anggota($id, $id_a);

        // Upload foto dilakukan setelah ada id, karena nama foto berisi nik
        if ($foto = upload_foto_penduduk()) {
            $this->db->where('id', $id_a)->update('tweb_penduduk', ['foto' => $foto]);
        }

        status_sukses($outp); //Tampilkan Pesan
    }

    // Hapus kelompok dengan tipe 'kelompok' saja
    public function delete($id = '', $semua = false)
    {
        if (! $semua) {
            $this->session->success = 1;
        }

        $kelompok = $this->db
            ->where('id', $id)
            ->where('tipe', $this->tipe)
            ->get('kelompok')->num_rows();

        if ($kelompok) {
            $outp = $this->db->where('id', $id)->where('tipe', $this->tipe)->delete($this->table);
            // Hapus peserta program bantuan sasaran kelompok, kalau ada
            $outp = $outp && $this->program_bantuan_model->hapus_peserta_dari_sasaran($id, 4);
        } else {
            $outp = false;
        }

        status_sukses($outp, $gagal_saja = true); //Tampilkan Pesan
    }

    public function delete_all()
    {
        $this->session->success = 1;

        $id_cb = $_POST['id_cb'];

        foreach ($id_cb as $id) {
            $this->delete($id, $semua = true);
        }
    }

    public function delete_anggota($id = '', $semua = false)
    {
        if (! $semua) {
            $this->session->success = 1;
        }

        $outp = $this->db->where('id', $id)->where('tipe', $this->tipe)->delete('kelompok_anggota');

        status_sukses($outp, $gagal_saja = true); //Tampilkan Pesan
    }

    public function delete_anggota_all()
    {
        $this->session->success = 1;

        $id_cb = $_POST['id_cb'];

        foreach ($id_cb as $id) {
            $this->delete_anggota($id, $semua = true);
        }
    }

    public function get_kelompok($id = null, $kode = null)
    {
        if ($id && $kode) {
            $this->db->where('k.id !=', $id);
        }

        return $this->db
            ->select('k.*, km.kelompok AS kategori, tp.nama AS nama_ketua')
            ->from('kelompok k')
            ->join('kelompok_master km', 'k.id_master = km.id', 'left')
            ->join('tweb_penduduk tp', 'k.id_ketua = tp.id', 'left')
            ->group_start()
            ->where('k.id', $id)
            ->or_where('k.kode', $kode)
            ->group_end()
            ->get()
            ->row_array();
    }

    public function get_ketua_kelompok($id)
    {
        $this->load->model('penduduk_model');
        $sql = "SELECT u.id, u.nik, u.nama, k.id as id_kelompok, k.nama as nama_kelompok, u.tempatlahir, u.tanggallahir, s.nama as sex,
				DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(`tanggallahir`)), '%Y')+0 AS umur,
				d.nama as pendidikan, f.nama as warganegara, a.nama as agama,
				wil.rt, wil.rw, wil.dusun
			FROM kelompok k
			LEFT JOIN tweb_penduduk u ON u.id = k.id_ketua
			LEFT JOIN tweb_penduduk_pendidikan_kk d ON u.pendidikan_kk_id = d.id
			LEFT JOIN tweb_penduduk_warganegara f ON u.warganegara_id = f.id
			LEFT JOIN tweb_penduduk_agama a ON u.agama_id = a.id
			LEFT JOIN tweb_penduduk_sex s ON s.id = u.sex
			LEFT JOIN tweb_wil_clusterdesa wil ON wil.id = u.id_cluster
			WHERE k.id = {$id} LIMIT 1";
        $query                  = $this->db->query($sql);
        $data                   = $query->row_array();
        $data['alamat_wilayah'] = $this->penduduk_model->get_alamat_wilayah($data['id']);

        return $data;
    }

    public function get_anggota($id = 0, $id_a = 0)
    {
        return $this->db
            ->select('ka.*, tp.sex as id_sex, tp.foto, tp.nik')
            ->from('kelompok_anggota ka')
            ->join('tweb_penduduk tp', 'ka.id_penduduk = tp.id')
            ->where('id_kelompok', $id)
            ->where('id_penduduk', $id_a)
            ->get()
            ->row_array();
    }

    public function list_master()
    {
        return $this->db
            ->where('tipe', $this->tipe)
            ->get('kelompok_master')
            ->result_array();
    }

    private function in_list_anggota($kelompok, $id_pend)
    {
        if ($id_pend) {
            $this->db->where_not_in('p.id', $id_pend);
        }

        $anggota = $this->db
            ->select('p.id')
            ->from('kelompok_anggota k')
            ->join('penduduk_hidup p', 'k.id_penduduk = p.id', 'left')
            ->where('k.id_kelompok', $kelompok)
            ->where('k.tipe', $this->tipe)
            ->get()
            ->result_array();

        return sql_in_list(array_column($anggota, 'id'));
    }

    public function list_penduduk($ex_kelompok = 0, $id_pend = 0)
    {
        if ($ex_kelompok) {
            $anggota = $this->in_list_anggota($ex_kelompok, $id_pend);
            if ($anggota) {
                $this->db->where("p.id not in ({$anggota})");
            }
        }
        $sebutan_dusun = ucwords($this->setting->sebutan_dusun);
        $this->db
            ->select('p.id, nik, nama')
            ->select("(
				case when (p.id_kk IS NULL or p.id_kk = 0)
					then
						case when (cp.dusun = '-' or cp.dusun = '')
							then CONCAT(COALESCE(p.alamat_sekarang, ''), ' RT ', cp.rt, ' / RW ', cp.rw)
							else CONCAT(COALESCE(p.alamat_sekarang, ''), ' {$sebutan_dusun} ', cp.dusun, ' RT ', cp.rt, ' / RW ', cp.rw)
						end
					else
						case when (ck.dusun = '-' or ck.dusun = '')
							then CONCAT(COALESCE(k.alamat, ''), ' RT ', ck.rt, ' / RW ', ck.rw)
							else CONCAT(COALESCE(k.alamat, ''), ' {$sebutan_dusun} ', ck.dusun, ' RT ', ck.rt, ' / RW ', ck.rw)
						end
				end) AS alamat")
            ->from('penduduk_hidup p')
            ->join('tweb_wil_clusterdesa cp', 'p.id_cluster = cp.id', 'left')
            ->join('tweb_keluarga k', 'p.id_kk = k.id', 'left')
            ->join('tweb_wil_clusterdesa ck', 'k.id_cluster = ck.id', 'left');

        return $this->db->get()->result_array();
    }

    public function list_pengurus($id_kelompok)
    {
        $this->db->where('jabatan <>', 90);

        return $this->list_anggota(0, 0, 0, $id_kelompok, '');
    }

    public function list_anggota($o = 0, $offset = 0, $limit = 500, $id_kelompok = 0, $sub = '')
    {
        if ($limit) {
            $this->db->limit($limit, $offset);
        }

        $dusun = ucwords($this->setting->sebutan_dusun);
        if ($sub == 'anggota') {
            $this->db->where('jabatan', 90); // Hanya anggota saja, tidak termasuk pengurus
        }

        $data = $this->db
            ->select('ka.*, tp.nik, tp.nama, tp.tempatlahir, tp.tanggallahir, tp.sex AS id_sex, tpx.nama AS sex, tp.foto, tpp.nama as pendidikan, tpa.nama as agama')
            ->select("(SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(tanggallahir)), '%Y')+0 FROM tweb_penduduk WHERE id = tp.id) AS umur")
            ->select('a.dusun,a.rw,a.rt')
            ->select("CONCAT('{$dusun} ', a.dusun, ' RW ', a.rw, ' RT ', a.rt) AS alamat")
            ->from('kelompok_anggota ka')
            ->join('tweb_penduduk tp', 'ka.id_penduduk = tp.id', 'left')
            ->join('tweb_penduduk_sex tpx', 'tp.sex = tpx.id', 'left')
            ->join('tweb_penduduk_pendidikan_kk tpp', 'tp.pendidikan_kk_id = tpp.id', 'left')
            ->join('tweb_penduduk_agama tpa', 'tp.agama_id = tpa.id', 'left')
            ->join('tweb_wil_clusterdesa a', 'tp.id_cluster = a.id', 'left')
            ->where('ka.id_kelompok', $id_kelompok)
            ->where('ka.tipe', $this->tipe)
            ->order_by('CAST(jabatan AS UNSIGNED) + 30 - jabatan, CAST(no_anggota AS UNSIGNED)')
            ->get()
            ->result_array();

        foreach ($data as $key => $anggota) {
            if ($anggota['jabatan'] != 90) {
                $data[$key]['jabatan'] = $this->referensi_model->list_ref(JABATAN_KELOMPOK)[$anggota['jabatan']] ?: strtoupper($anggota['jabatan']);
            } else {
                $data[$key]['jabatan'] = $this->referensi_model->list_ref(JABATAN_KELOMPOK)[$anggota['jabatan']];
            }
        }

        return $data;
    }

    public function paging($p = 1, $id_kelompok = '')
    {
        $jml_data = count($this->list_anggota(0, 0, 0, $id_kelompok, ''));

        return $this->paginasi($p, $jml_data);
    }

    public function ubah_jabatan($id_kelompok, $id_penduduk, $jabatan, $jabatan_lama)
    {
        // jika ada orang lain yang sudah jabat KETUA ubah jabatan menjadi anggota
        // update id_ketua kelompok di tabel kelompok
        if ($jabatan == '1') { // Ketua
            $this->db
                ->set('jabatan', '90') // Anggota
                ->set('no_sk_jabatan', '')
                ->where('id_kelompok', $id_kelompok)
                ->where('jabatan', '1')
                ->update('kelompok_anggota');

            $this->db
                ->set('id_ketua', $id_penduduk)
                ->where('id', $id_kelompok)
                ->update($this->table);
        } elseif ($jabatan_lama == '1') { // Ketua
            // jika yang diubah adalah jabatan KETUA maka kosongkan id_ketua kelompok di tabel kelompok
            $this->db
                ->set('id_ketua', -9999) // kolom id_ketua di tabel kelompok tidak bisa NULL
                ->where('id', $id_kelompok)
                ->update($this->table);
        }
    }

    public function list_jabatan($id_kelompok = 0)
    {
        return $this->db
            ->distinct()
            ->select('UPPER(jabatan) as jabatan ')
            ->where("jabatan REGEXP '[a-zA-Z]+'")
            ->where('id_kelompok', $id_kelompok)
            ->where('tipe', $this->tipe)
            ->order_by('jabatan')
            ->get('kelompok_anggota')
            ->result_array();
    }

    public function get_judul_statistik($tipe = 0, $nomor = 0, $sex = 0)
    {
        if ($nomor == JUMLAH) {
            $judul = ['nama' => ' : JUMLAH'];
        } elseif ($nomor == BELUM_MENGISI) {
            $judul = ['nama' => ' : BELUM MENGISI'];
        } elseif ($nomor == TOTAL) {
            $judul = ['nama' => ' : TOTAL'];
        } else {
            switch ($tipe) {
                case 'penerima_bantuan':
                    $table = 'program';
                    break;

                default:
                    $table = 'kelompok';
                    break;
            }

            $judul = $this->db->get_where($table, ['id' => $nomor])->row_array();
        }

        if ($sex == 1) {
            $judul['nama'] .= ' - LAKI-LAKI';
        } elseif ($sex == 2) {
            $judul['nama'] .= ' - PEREMPUAN';
        }

        return $judul;
    }

    public function slug($slug = null)
    {
        return $this->db
            ->select('id')
            ->get_where($this->table, ['slug' => $slug])
            ->row()
            ->id;
    }
}
