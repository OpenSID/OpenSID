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

defined('BASEPATH') || exit('No direct script access allowed');

class Lapak_model extends MY_Model
{
    public const ORDER_ABLE_PRODUK = [
        2 => 'pelapak',
        3 => 'nama',
        4 => 'kategori',
        5 => 'harga',
        6 => 'satuan',
        7 => 'potongan',
        8 => 'deskripsi',
    ];
    public const ORDER_ABLE_PELAPAK = [
        2 => 'pelapak',
        3 => 'telepon',
    ];
    public const ORDER_ABLE_KATEGORI = [
        2 => 'kategori',
    ];

    /**
     * @var array
     */
    protected $list_satuan = ['lusin', 'gross', 'rim', 'lembar', 'pcs', 'gram', 'kg', 'paket'];

    // PRODUK
    public function get_produk(string $search = '', $status = null, $id_pend = 0, $id_produk_kategori = 0)
    {
        $this->produk();

        if ($search !== '' && $search !== '0') {
            $this->db
                ->group_start()
                ->like('p.nama', $search)
                ->or_like('pr.nama', $search)
                ->or_like('pk.kategori', $search)
                ->or_like('pr.harga', $search)
                ->or_like('pr.satuan', $search)
                ->or_like('pr.potongan', $search)
                ->or_like('pr.deskripsi', $search)
                ->group_end();
        }

        if ($status) {
            $this->db->where('pr.status', $status);
        }
        if ($id_pend) {
            $this->db->where('p.id', $id_pend);
        }
        if ($id_produk_kategori) {
            $this->db->where('pk.id', $id_produk_kategori);
        }

        return $this->db;
    }

    protected function produk()
    {
        $kantor = identitas();

        $default_nama    = 'Admin';
        $default_telepon = $this->config_id()->get_where('media_sosial', ['id' => 6, 'tipe' => 1, 'enabled' => 1])->row()->link;

        $this->config_id('pr')
            ->select('pr.*, pk.kategori, p.nik, lp.zoom')
            ->select("(case when p.nama is null then '{$default_nama}' else p.nama end) as pelapak")
            ->select("(case when p.nama is null then '{$default_telepon}' else lp.telepon end) as telepon")
            ->select("if(lp.lat is null or lp.lat = ' ', if(m.lat is null or m.lat = ' ', '{$kantor->lat}', m.lat), lp.lat) as lat ")
            ->select("if(lp.lng is null or lp.lng = ' ', if(m.lng is null or m.lng = ' ', '{$kantor->lng}', m.lng), lp.lng) as lng ")
            ->from('produk pr')
            ->join('produk_kategori pk', 'pr.id_produk_kategori = pk.id', 'LEFT')
            ->join('pelapak lp', 'pr.id_pelapak = lp.id', 'LEFT')
            ->join('penduduk_hidup p', 'lp.id_pend = p.id', 'LEFT')
            ->join('tweb_penduduk_map m', 'p.id = m.id', 'LEFT')
            ->where('lp.status', 1)
            ->where('pk.status', 1);
    }

    public function paging_produk($p = 1, $keyword = '', $id_kategori = '')
    {
        $paging = new Paging();

        if ($keyword) {
            $jml           = $this->get_produk($keyword, 1);
            $cfg['suffix'] = "?id_kategori={$id_kategori}&keyword={$keyword}";
        } else {
            $jml = $this->get_produk('', 1);
        }

        if ($id_kategori != '') {
            $jml           = $jml->where('id_produk_kategori', $id_kategori);
            $cfg['suffix'] = "?id_kategori={$id_kategori}&keyword={$keyword}";
        }

        $cfg['page']     = $p;
        $cfg['per_page'] = setting('jumlah_produk_perhalaman');
        $cfg['num_rows'] = $jml->count_all_results();
        $paging->init($cfg);

        return $paging;
    }

    public function produk_insert(): void
    {
        $data              = $this->produk_validasi();
        $data['config_id'] = $this->config_id;
        $outp              = $this->db->insert('produk', $data);

        status_sukses($outp);
    }

    public function produk_update($id = 0): void
    {
        $data               = $this->produk_validasi();
        $data['updated_at'] = date('Y-m-d H:i:s');

        $outp = $this->config_id()->where('id', $id)->update('produk', $data);

        status_sukses($outp);
    }

    private function produk_validasi()
    {
        $post = $this->input->post();

        $foto = [];

        for ($i = 0; $i < setting('banyak_foto_tiap_produk'); $i++) {
            $value = $this->upload_foto_produk($i + 1);
            if ($value == null) {
                continue;
            }
            $foto[] = $value;
        }

        $data = [
            'id_pelapak'         => bilangan($post['id_pelapak']),
            'nama'               => judul($post['nama']),
            'id_produk_kategori' => alfanumerik_spasi($post['id_produk_kategori']),
            'harga'              => bilangan($post['harga']),
            'satuan'             => alfanumerik_spasi($post['satuan']),
            'tipe_potongan'      => bilangan($post['tipe_potongan']),
            'deskripsi'          => $this->security->xss_clean($post['deskripsi']),
            'foto'               => ($foto == []) ? null : json_encode($foto, JSON_THROW_ON_ERROR),
            'potongan'           => ($post['potongan'] == null) ? '0' : $post['potongan'],
        ];

        if ($post['tipe_potongan'] == 1 && ! empty($post['persen'])) {
            $data['potongan'] = bilangan($post['persen']);
        }

        if ($post['tipe_potongan'] == 2 && ! empty($post['nominal'])) {
            $data['potongan'] = bilangan($post['nominal']);
        }

        return $data;
    }

    private function upload_foto_produk(int $key = 1)
    {
        $this->load->library('upload');
        // Adakah berkas yang disertakan?
        if (empty($_FILES["foto_{$key}"]['name'])) {
            // Jika hapus (ceklis)
            if (isset($_POST["hapus_foto_{$key}"])) {
                unlink(LOKASI_PRODUK . $this->input->post("old_foto_{$key}"));

                return null;
            }

            return $this->input->post("old_foto_{$key}");
        }

        $uploadData = null;
        // Inisialisasi library 'upload'
        $this->upload->initialize([
            'upload_path'   => LOKASI_PRODUK,
            'allowed_types' => 'gif|jpg|jpeg|png',
            'max_size'      => 1024, // 1 MB
        ]);
        // Upload gagal
        if (! $this->upload->do_upload("foto_{$key}")) {
            session_error($this->upload->display_errors());
            redirect('lapak_admin/produk');
        }
        // Upload sukses
        else {
            unlink(LOKASI_PRODUK . $this->input->post("old_foto_{$key}"));
            $uploadData = $this->upload->data()['file_name'];
        }

        return $uploadData;
    }

    public function produk_delete($id = 0): void
    {
        $this->hapus_foto_produk('id', $id);

        $outp = $this->config_id()->where('id', $id)->delete('produk');

        status_sukses($outp);
    }

    public function produk_delete_all(): void
    {
        $id_cb = $_POST['id_cb'];

        foreach ($id_cb as $id) {
            $this->produk_delete($id);
        }
    }

    protected function hapus_foto_produk($where = 'id', $value = 0)
    {
        // Hapus semua foto produk jika produk/kategori/pelapak dihapus agar tidak meninggalkan sampah
        $list_data = $this->config_id()->select('foto')->get_where('produk', [$where => $value])->result();

        if (! $list_data) {
            return;
        }

        foreach ($list_data as $data) {
            $foto    = json_decode($data->foto, null);
            $counter = count($foto);

            for ($i = 0; $i < $counter; $i++) {
                unlink(LOKASI_PRODUK . $foto[$i]);
            }
        }
    }

    public function produk_detail($id = 0)
    {
        $this->produk();

        return $this->db->where('pr.id', $id)->get()->row();
    }

    // PELAPAK
    public function get_pelapak(string $search = '', $status = null)
    {
        $this->pelapak();

        if ($search !== '' && $search !== '0') {
            $this->db
                ->group_start()
                ->like('p.nama', $search)
                ->or_like('lp.telepon', $search)
                ->group_end();
        }

        if ($status) {
            $this->db->like('lp.status', $status);
        }

        return $this->db;
    }

    protected function pelapak()
    {
        $this->config_id('lp')
            ->select('lp.*, p.nama AS pelapak, p.nik')
            ->select('(SELECT COUNT(pr.id) FROM produk pr WHERE pr.id_pelapak = lp.id) AS jumlah')
            ->from('pelapak lp')
            ->join('penduduk_hidup p', 'lp.id_pend = p.id', 'LEFT');
    }

    public function list_penduduk($id_pend = 0)
    {
        return $this->config_id('p')
            ->select('p.id, p.nik, p.nama, p.telepon')
            ->where('p.nik <>', '')
            ->where('p.nik <>', 0)
            ->where("p.id NOT IN (SELECT id_pend FROM pelapak WHERE id_pend != {$id_pend} AND config_id = p.config_id)")
            ->get('penduduk_hidup p')
            ->result();
    }

    public function pelapak_insert(): void
    {
        $data              = $this->pelapak_validasi();
        $data['config_id'] = $this->config_id;

        $outp = $this->db->insert('pelapak', $data);

        // Tambahkan no telpon ke tweb_penduduk jika kosong
        $this->config_id()
            ->where('id', $data['id_pend'])
            ->update('tweb_penduduk', ['telepon' => $data['telepon']]);

        status_sukses($outp);
    }

    public function pelapak_update($id = 0): void
    {
        $data = $this->pelapak_validasi();
        $outp = $this->config_id()->where('id', $id)->update('pelapak', $data);

        status_sukses($outp);
    }

    public function pelapak_update_maps($id = 0): void
    {
        $post = $this->input->post();

        $data = [
            'lat'  => $post['lat'],
            'lng'  => $post['lng'],
            'zoom' => $post['zoom'],
        ];
        $outp = $this->config_id()->where('id', $id)->update('pelapak', $data);

        status_sukses($outp);
    }

    private function pelapak_validasi()
    {
        $post = $this->input->post();

        return [
            'id_pend' => bilangan($post['id_pend']),
            'telepon' => bilangan($post['telepon']),
        ];
    }

    public function pelapak_delete($id = 0): void
    {
        $this->hapus_foto_produk('id_pelapak', $id);

        $outp = $this->config_id()->where('id', $id)->delete('pelapak');

        status_sukses($outp);
    }

    public function pelapak_delete_all(): void
    {
        $id_cb = $_POST['id_cb'];

        foreach ($id_cb as $id) {
            $this->pelapak_delete($id);
        }
    }

    public function pelapak_detail($id = 0)
    {
        $this->pelapak();

        return $this->db->where('lp.id', $id)->get()->row();
    }

    // KATEGORI / SATUAN
    public function get_satuan()
    {
        $data_array = $this->config_id()
            ->distinct()
            ->select('satuan')
            ->get('produk')
            ->result();

        foreach ($data_array as $value) {
            if (! in_array($value->satuan, $this->list_satuan)) {
                $this->list_satuan[] = $value->satuan;
            }
        }
        usort($this->list_satuan, 'strnatcasecmp');

        return $this->list_satuan;
    }

    public function kategori_detail($id = 0)
    {
        $this->kategori();

        return $this->db->where('pk.id', $id)->get()->row();
    }

    public function get_kategori(string $search = '', $status = null)
    {
        $this->kategori();

        if ($search !== '' && $search !== '0') {
            $this->db->like('pk.kategori', $search);
        }

        if ($status) {
            $this->db->like('pk.status', $status);
        }

        return $this->db;
    }

    protected function kategori()
    {
        $this->config_id('pk')
            ->select('pk.*')
            ->select('(SELECT COUNT(pr.id) FROM produk pr WHERE pr.id_produk_kategori = pk.id) AS jumlah')
            ->from('produk_kategori pk');
    }

    public function kategori_insert(): void
    {
        $data              = $this->kategori_validasi();
        $data['config_id'] = $this->config_id;
        $outp              = $this->db->insert('produk_kategori', $data);

        status_sukses($outp);
    }

    public function kategori_update($id = 0): void
    {
        $data = $this->kategori_validasi();
        $outp = $this->config_id()->where('id', $id)->update('produk_kategori', $data);

        status_sukses($outp);
    }

    public function kategori_delete($id = 0): void
    {
        $this->hapus_foto_produk('id_produk_kategori', $id);

        $outp = $this->config_id()->where('id', $id)->delete('produk_kategori');

        status_sukses($outp);
    }

    public function kategori_delete_all(): void
    {
        $id_cb = $_POST['id_cb'];

        foreach ($id_cb as $id) {
            $this->kategori_delete($id);
        }
    }

    private function kategori_validasi()
    {
        $post = $this->input->post();

        return [
            'kategori' => alfanumerik_spasi($post['kategori']),
            'slug'     => url_title($post['kategori'], 'dash', true),
        ];
    }

    public function status($table, $id, $status = 1): void
    {
        $outp = $this->config_id()
            ->where('id', $id)
            ->update($table, ['status' => $status]);

        status_sukses($outp);
    }
}
