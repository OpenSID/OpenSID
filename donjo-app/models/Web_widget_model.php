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

use App\Models\JamKerja;

defined('BASEPATH') || exit('No direct script access allowed');

class Web_widget_model extends MY_Model
{
    private string $tabel = 'widget';
    private Urut_Model $urut_model;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('first_gallery_m');
        $this->load->model('laporan_penduduk_model');
        $this->load->model('pamong_model');
        $this->load->model('keuangan_grafik_model');
        $this->load->model('theme_model');
        require_once APPPATH . '/models/Urut_model.php';
        $this->urut_model = new Urut_Model($this->tabel);
        $this->cekFileWidget();
    }

    public function autocomplete()
    {
        return $this->autocomplete_str('judul', $this->tabel);
    }

    public function get_widget($id = '')
    {
        $data          = $this->config_id()->where('id', $id)->get($this->tabel)->row_array();
        $data['judul'] = e($data['judul']);
        $data['isi']   = htmlentities($data['isi']);

        return $data;
    }

    // Bersihkan html supaya semua tag menjadi lengkap
    // Untuk menghindari widget merusak tampilan
    // PERHATIAN: extension PHP tidy perlu diaktifkan di php.ini
    private function bersihkan_html($isi)
    {
        // Konfigurasi tidy
        $config = [
            'indent'         => true,
            'output-xhtml'   => true,
            'show-body-only' => true,
            'clean'          => true,
            'coerce-endtags' => true,
        ];
        $tidy = new tidy();
        $tidy->parseString($isi, $config, 'utf8');
        $tidy->cleanRepair();

        return tidy_get_output($tidy);
    }

    public function get_widget_aktif()
    {
        if ($this->setting->layanan_mandiri == 0) {
            $this->db->where('isi !=', 'layanan_mandiri.php');
        }

        $widget = $this->config_id()
            ->where('enabled', 1)
            ->order_by('urut')
            ->get($this->tabel)
            ->result_array();

        return collect($widget)->map(static function (array $item): array {
            if ($item['jenis_widget'] == 3) {
                $item['isi'] = bersihkan_xss($item['isi']);
            }
            $item['judul'] = SebutanDesa($item['judul']);

            return $item;
        })->toArray();
    }

    private function search_sql(): void
    {
        if ($search = $this->session->cari) {
            $this->db->like('judul', $search)->or_like('isi', $search);
        }
    }

    private function filter_sql(): void
    {
        if ($filter = $this->session->cari) {
            $this->db->where('enabled', $filter);
        }
    }

    public function paging($p = 1, $o = 0)
    {
        $this->db->select('COUNT(*) as jml');
        $this->list_data_sql();
        $row      = $this->db->get('widget')->row_array();
        $jml_data = $row['jml'];

        $this->load->library('paging');
        $cfg['page']     = $p;
        $cfg['per_page'] = $_SESSION['per_page'];
        $cfg['num_rows'] = $jml_data;
        $this->paging->init($cfg);

        return $this->paging;
    }

    private function list_data_sql(): void
    {
        $this->config_id();
        $this->search_sql();
        $this->filter_sql();
    }

    public function list_data($o = 0, $offset = 0, $limit = 500)
    {
        $this->list_data_sql();
        $data = $this->db
            ->limit($limit, $offset)
            ->order_by('urut')
            ->get('widget')
            ->result_array();

        $j       = $offset;
        $counter = count($data);

        for ($i = 0; $i < $counter; $i++) {
            $data[$i]['no']    = $j + 1;
            $data[$i]['judul'] = SebutanDesa($data[$i]['judul']);

            if ($data[$i]['enabled'] == 1) {
                $data[$i]['aktif'] = 'Ya';
            } else {
                $data[$i]['aktif']   = 'Tidak';
                $data[$i]['enabled'] = 2;
            }
            $teks            = $data[$i]['isi'];
            $abstrak         = strlen($teks) > 150 ? substr($teks, 0, 150) . '...' : $teks;
            $data[$i]['isi'] = $abstrak;

            $j++;
        }

        return $this->security->xss_clean($data);
    }

    /**
     * @param $id   Id widget
     * @param $arah Arah untuk menukar dengan widget: 1) bawah, 2) atas
     *
     * @return int Nomer urut widget lain yang ditukar
     */
    public function urut($id, $arah)
    {
        return $this->urut_model->urut($id, $arah);
    }

    /**
     * @param string $id
     * @param int    $val (1) Ya, (2) Tidak
     */
    public function lock($id = '', $val = 2): void
    {
        $outp = $this->config_id()->where('id', $id)->update($this->tabel, ['enabled' => $val]);
        status_sukses($outp);
    }

    public function insert(): void
    {
        $data              = $this->validasi($this->input->post());
        $data['enabled']   = 2;
        $data['config_id'] = identitas('id');
        // Widget diberi urutan terakhir
        $data['urut'] = $this->urut_model->urut_max() + 1;

        if (empty($data['foto'])) {
            unset($data['foto']);
        }

        unset($data['file_foto'], $data['old_foto']);

        $outp = $this->db->insert($this->tabel, $data);

        status_sukses($outp);
    }

    private function validasi($post)
    {
        $data['judul']        = judul($post['judul']);
        $data['jenis_widget'] = (int) $post['jenis_widget'];
        $data['foto']         = $this->upload_gambar('foto');
        if ($data['jenis_widget'] == 2) {
            $data['isi'] = bersihkan_xss($post['isi-statis']);
        } elseif ($data['jenis_widget'] == 3) {
            $data['isi'] = $post['isi-dinamis'];
            $data['isi'] = $this->bersihkan_html(bersihkan_xss($data['isi']));
        }

        return $data;
    }

    private function upload_gambar(string $jenis)
    {
        // Inisialisasi library 'upload'
        $this->load->library('MY_Upload', null, 'upload');
        $uploadConfig = [
            'upload_path'   => LOKASI_GAMBAR_WIDGET,
            'allowed_types' => 'jpg|jpeg|png',
            'max_size'      => 1024, // 1 MB
        ];
        $this->upload->initialize($uploadConfig);

        $uploadData = null;
        // Adakah berkas yang disertakan?
        $adaBerkas = ! empty($_FILES[$jenis]['name']);
        if (! $adaBerkas) {
            // Jika hapus (ceklis)
            if (isset($_POST['hapus_foto'])) {
                unlink(LOKASI_GAMBAR_WIDGET . $this->input->post('old_foto'));

                return null;
            }

            return $this->input->post('old_foto');
        }

        // Upload sukses
        if ($this->upload->do_upload($jenis)) {
            $uploadData = $this->upload->data();
            // Buat nama file unik agar url file susah ditebak dari browser
            $namaFileUnik = tambahSuffixUniqueKeNamaFile($uploadData['file_name']);
            // Ganti nama file asli dengan nama unik untuk mencegah akses langsung dari browser
            $fileRenamed = rename(
                $uploadConfig['upload_path'] . $uploadData['file_name'],
                $uploadConfig['upload_path'] . $namaFileUnik
            );
            // Ganti nama di array upload jika file berhasil di-rename --
            // jika rename gagal, fallback ke nama asli
            $uploadData['file_name'] = $fileRenamed ? $namaFileUnik : $uploadData['file_name'];

            // Hapus file lama
            unlink(LOKASI_GAMBAR_WIDGET . $this->input->post('old_foto'));
        }
        // Upload gagal
        else {
            session_error($this->upload->display_errors(null, null));

            return redirect('web_widget');
        }

        return (empty($uploadData)) ? null : $uploadData['file_name'];
    }

    public function update($id = 0): void
    {
        $data = $this->validasi($this->input->post());
        $outp = $this->config_id()->where('id', $id)->update($this->tabel, $data);

        status_sukses($outp);
    }

    public function get_setting($widget, $opsi = '')
    {
        // Data di kolom setting dalam format json
        $setting = $this->config_id()
            ->select('setting')
            ->where('isi', $widget . '.php')
            ->get($this->tabel)
            ->row_array();
        $setting = json_decode($setting['setting'], true);

        return empty($opsi) ? $setting : $setting[$opsi];
    }

    protected function filter_setting($k)
    {
        $berisi = false;

        foreach ($k as $kolom) {
            if ($kolom) {
                $berisi = true;
                break;
            }
        }

        return $berisi;
    }

    private function sort_sinergi_program($a, $b)
    {
        $keya = str_pad($a['baris'], 2, '0', STR_PAD_LEFT) . $a['kolom'];
        $keyb = str_pad($b['baris'], 2, '0', STR_PAD_LEFT) . $b['kolom'];

        return $keya > $keyb;
    }

    private function upload_gambar_sinergi_program(&$setting): void
    {
        foreach ($setting as $key => $value) {
            $_FILES['file']['name']     = $_FILES['setting']['name'][$key]['gambar'];
            $_FILES['file']['type']     = $_FILES['setting']['type'][$key]['gambar'];
            $_FILES['file']['tmp_name'] = $_FILES['setting']['tmp_name'][$key]['gambar'];
            $_FILES['file']['error']    = $_FILES['setting']['error'][$key]['gambar'];
            $_FILES['file']['size']     = $_FILES['setting']['size'][$key]['gambar'];

            $old_gambar              = $value['old_gambar'];
            $setting[$key]['gambar'] = $old_gambar;

            if (! empty($_FILES['file']['tmp_name'])) {
                $this->load->library('MY_Upload', null, 'upload');
                $this->upload->initialize([
                    'upload_path'   => LOKASI_GAMBAR_WIDGET,
                    'allowed_types' => 'jpg|png|jpeg',
                    'max_size'      => 1024, // 1 MB
                ]);

                if ($this->upload->do_upload('file')) {
                    $setting[$key]['gambar'] = $this->upload->data('file_name');

                    if ($old_gambar) {
                        unlink(LOKASI_GAMBAR_WIDGET . $old_gambar);
                    }
                } else {
                    session_error($this->upload->display_errors(null, null));

                    redirect('web_widget/admin/sinergi_program');
                }
            }
        }
    }

    public function update_setting($widget, $setting): void
    {
        $_SESSION['success'] = 1;

        switch ($widget) {
            case 'sinergi_program':
                // Upload semua gambar setting
                $this->upload_gambar_sinergi_program($setting);
                // Hapus setting kosong menggunakan callback
                $setting = array_filter($setting, fn ($k) => $this->filter_setting($k));
                // Sort setting berdasarkan [baris][kolom]
                usort($setting, fn ($a, $b) => $this->sort_sinergi_program($a, $b));
                break;

            default:
                break;
        }
        // Simpan semua setting di kolom setting sebagai json
        $setting = json_encode($setting, JSON_THROW_ON_ERROR);
        $data    = ['setting' => $setting];
        $outp    = $this->config_id()->where('isi', $widget . '.php')->update($this->tabel, $data);

        status_sukses($outp);
    }

    public function delete($id = '', $semua = false): void
    {
        if (! $semua) {
            $this->session->success = 1;
        }

        $outp = $this->config_id()->where('id', $id)->where('jenis_widget <>', 1)->delete($this->tabel);

        status_sukses($outp, true); //Tampilkan Pesan
    }

    public function delete_all(): void
    {
        $this->session->success = 1;

        $id_cb = $this->input->post('id_cb');

        foreach ($id_cb as $id) {
            $this->delete($id, true);
        }
    }

    // pengambilan data yang akan ditampilkan di widget
    public function get_widget_data(&$data): void
    {
        $data['w_gal']           = $this->first_gallery_m->gallery_widget();
        $data['hari_ini']        = $this->first_artikel_m->agenda_show('hari_ini');
        $data['yad']             = $this->first_artikel_m->agenda_show('yad');
        $data['lama']            = $this->first_artikel_m->agenda_show('lama');
        $data['komen']           = $this->first_artikel_m->komentar_show();
        $data['sosmed']          = media_sosial();
        $data['arsip_terkini']   = $this->first_artikel_m->arsip_show('terkini');
        $data['arsip_populer']   = $this->first_artikel_m->arsip_show('populer');
        $data['arsip_acak']      = $this->first_artikel_m->arsip_show('acak');
        $data['aparatur_desa']   = $this->pamong_model->list_aparatur_desa();
        $data['stat_widget']     = $this->laporan_penduduk_model->list_data(4);
        $data['sinergi_program'] = $this->get_setting('sinergi_program');
        $data['widget_keuangan'] = $this->keuangan_grafik_model->widget_keuangan();
        $data['jam_kerja']       = JamKerja::get();
    }

    // widget statis di ambil dari folder desa/widget, vendor/themes/nama_tema/widgets dan desa/themes/nama_tema/widgets
    public function list_widget_baru()
    {
        $tema_desa   = $this->theme_model->list_all();
        $list_widget = [];
        $widget_desa = $this->widget(LOKASI_WIDGET . '*.php');
        $list_widget = array_merge($list_widget, $widget_desa);

        foreach ($tema_desa as $tema) {
            if (preg_match('/desa/i', $tema)) {
                $tema = str_replace('desa/', '', $tema);
                $tema = 'desa/themes/' . $tema;
            } else {
                $tema = 'vendor/themes/' . $tema;
            }

            $list = $this->widget($tema . '/widgets/*.php');

            $list_widget = array_merge($list_widget, $list);
        }

        return $list_widget;
    }

    public function widget($lokasi)
    {
        $this->list_widget_statis();
        $list_widget = glob($lokasi);
        $l_widget    = [];

        foreach ($list_widget as $widget) {
            $l_widget[] = $widget;
        }

        return $l_widget;
    }

    private function list_widget_statis()
    {
        $data = $this->config_id()
            ->select('isi')
            ->where('jenis_widget', 2)
            ->get($this->tabel)
            ->result_array();

        return array_column($data, 'isi');
    }

    public function cekFileWidget(): void
    {
        $data = $this->config_id()
            ->where('jenis_widget <>', 3)
            ->where('enabled', 1)
            ->get($this->tabel)
            ->result_array();

        if ($data) {
            foreach ($data as $widget) {
                if ($widget['jenis_widget'] == 1) {
                    $widget['isi'] = "{$this->theme_model->folder}/{$this->theme_model->tema}/widgets/{$widget['isi']}";
                }

                if (! file_exists($widget['isi'])) {
                    $this->lock($widget['id'], 2);
                    $this->session->success   = 'error';
                    $this->session->error_msg = "File widget {$widget['judul']} tidak ditemukan sehingga otomatis terkunci";
                }
            }
        }
    }
}
