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

use App\Enums\StatusEnum;
use App\Enums\TampilanArtikelEnum;
use App\Models\Agenda;
use App\Models\Artikel;
use App\Models\Kategori;
use App\Models\Menu;
use App\Models\SettingAplikasi;
use App\Models\UserGrup;

defined('BASEPATH') || exit('No direct script access allowed');

class Web extends Admin_Controller
{
    public $modul_ini     = 'admin-web';
    public $sub_modul_ini = 'artikel';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
        // Jika offline_mode dalam level yang menyembunyikan website,
        // tidak perlu menampilkan halaman website
        if ($this->setting->offline_mode >= 2) {
            redirect('beranda');

            exit;
        }
    }

    public function index($cat = null): void
    {
        if ($cat === null) {
            $cat = -1;
        }
        $data['status']        = [StatusEnum::YA => 'Aktif', StatusEnum::TIDAK => 'Non Aktif'];
        $data['cat']           = $cat;
        $data['list_kategori'] = Kategori::with(['children' => static fn ($q) => $q->orderBy('urut')])->whereParrent(0)->get()->toArray();
        $data['kategori']      = (int) $cat > 0 ? Kategori::select(['kategori'])->find($cat)->kategori : '';

        view('admin.web.artikel.index', $data);
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            $status    = $this->input->get('status') ?? null;
            $cat       = $this->input->get('cat') ?? '-1';
            $canUpdate = can('u');
            $canDelete = can('h');

            return datatables()->of(Artikel::without(['comments', 'author', 'category'])->when($status != null, static fn ($q) => $q->whereEnabled($status))
                ->when($cat !== null, static function ($q) use ($cat) {
                    switch(($cat)) {
                        case '-1':
                            return $q->dinamis();

                        case '0':
                            return $q->whereNull('id_kategori')->dinamis();

                        case in_array($cat, Artikel::TIPE_NOT_IN_ARTIKEL):
                            return $q->where('tipe', $cat);

                        default:
                            return $q->where('id_kategori', $cat)->dinamis();
                    }
                }))
                ->addColumn('ceklist', static function ($row) use ($canDelete) {
                    if ($canDelete) {
                        return '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>';
                    }
                })
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row) use ($canDelete, $canUpdate): string {
                    $aksi = '';
                    if ($canUpdate && $row->bolehUbah()) {
                        $aksi .= '<a href="' . ci_route('web.form.' . $row->kategori, encrypt($row->id)) . '" class="btn bg-orange btn-sm" title="Ubah Data"><i class="fa fa-edit"></i></a> ';
                            if ($canDelete) {
                                $aksi .= '<a href="#" data-href="' . ci_route('web.delete.' . $row->kategori, encrypt($row->id)) . '" class="btn bg-maroon btn-sm" title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a> ';
                            }
                            $aksi .= '<a href="' . ci_route('web.ubah_kategori_form', encrypt($row->id)) . '" class="btn bg-purple btn-sm" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Ubah Kategori" title="Ubah Kategori"><i class="fa fa-folder-open"></i></a> ';
                            if ($row->boleh_komentar == 1) {
                                $aksi .= '<a href="' . ci_route('web.lock.' . $row->kategori . '.boleh_komentar', encrypt($row->id)) . '" class="btn bg-info btn-sm" title="Tutup Komentar Artikel"><i class="fa fa-comment-o"></i></a> ';
                            } else {
                                $aksi .= '<a href="' . ci_route('web.lock.' . $row->kategori . '.boleh_komentar', encrypt($row->id)) . '" class="btn bg-info btn-sm" title="Buka Komentar Artikel"><i class="fa fa-comment"></i></a> ';
                            }
                            if ($row->enabled == '1') {
                                $aksi .= '<a href="' . ci_route('web.lock.' . $row->kategori . '.enabled', encrypt($row->id)) . '" class="btn bg-navy btn-sm" title="Non Aktifkan Artikel"><i class="fa fa-unlock"></i></a> ';
                                $aksi .= '<a href="' . ci_route('web.lock.' . $row->kategori . '.headline', encrypt($row->id)) . '" class="btn bg-teal btn-sm" title="Jadikan Berita Utama">
                                    <i class="' . ($row->headline == 1 ? 'fa fa-star' : 'fa fa-star-o') . '"></i>
                                </a> ';
                                $aksi .= '<a href="' . ci_route('web.lock.' . $row->kategori . '.slider', encrypt($row->id)) . '" class="btn bg-gray btn-sm" title="' . (($row->slider == 1) ? 'Keluarkan dari slide' : 'Masukkan ke dalam slide') . '">
                                    <i class="' . ($row->slider == 1 ? 'fa fa-pause' : 'fa fa-play') . '"></i>
                                </a> ';
                            } else {
                                $aksi .= '<a href="' . ci_route('web.lock.' . $row->kategori . '.enabled', encrypt($row->id)) . '" class="btn bg-navy btn-sm" title="Aktifkan Artikel"><i class="fa fa-lock"></i></a> ';
                            }
                    }

                    return $aksi . ('<a href="' . $row->url_slug . '" target="_blank" class="btn bg-green btn-sm" title="Lihat Artikel"><i class="fa fa-eye"></i></a>');
                })
                ->editColumn('hit', static fn ($row): string => hit($row->hit))
                ->editColumn('tgl_upload', static fn ($row) => tgl_indo2($row->tgl_upload))
                ->rawColumns(['aksi', 'ceklist'])
                ->make();
        }

        return show_404();
    }

    public function form($cat = null, $id = null): void
    {
        isCan('u');

        $this->set_hak_akses_rfm();

        if (null !== $id) {
            $id        = decrypt($id);
            $relations = in_array($cat, Artikel::TIPE_NOT_IN_ARTIKEL) ? ['agenda'] : ['category'];
            $artikel   = Artikel::withOnly($relations)->findOrFail($id);

            if (! $artikel->bolehUbah()) {
                redirect_with('error', 'Pengguna tidak diijinkan mengubah artikel ini');
            }

            $data['artikel']     = $artikel->toArray();
            $data['form_action'] = ci_route('web.update.' . $cat, $id);
            $data['id']          = $id;
            $data['kategori']    = is_numeric($cat) && $cat > 0 ? $artikel->category->toArray() : ['kategori' => ''];
        } else {
            if ($cat === null) {
                redirect_with('error', 'Kategori tidak ditemukan');
            }
            $data['artikel']     = null;
            $data['form_action'] = ci_route('web.insert', $cat);
            $data['kategori']    = ['kategori' => ''];
        }

        $data['cat']           = $cat;
        $data['list_tampilan'] = TampilanArtikelEnum::all();

        view('admin.web.artikel.form', $data);
    }

    public function insert($cat): void
    {
        $data = $this->input->post();
        if (empty($data['judul']) || empty($data['isi'])) {
            redirect_with('error', 'Judul atau isi harus diisi', ci_route('web', $cat));
        }

        // Batasi judul menggunakan teks polos
        $data['judul']    = judul($data['judul']);
        $data['tampilan'] = (int) $data['tampilan'];

        $fp          = time();
        $list_gambar = ['gambar', 'gambar1', 'gambar2', 'gambar3'];

        foreach ($list_gambar as $gambar) {
            $lokasi_file = $_FILES[$gambar]['tmp_name'];
            $nama_file   = $fp . '_' . $_FILES[$gambar]['name'];
            $nama_file   = trim(str_replace(' ', '_', $nama_file));
            if (! empty($lokasi_file)) {
                $tipe_file = TipeFile($_FILES[$gambar]);
                $hasil     = UploadArtikel($nama_file, $gambar);
                if ($hasil) {
                    $data[$gambar] = $nama_file;
                } else {
                    redirect_with('error', 'Upload gambar gagal', ci_route('web', $cat));
                }
            }
        }
        $data['id_kategori'] = in_array($cat, Artikel::TIPE_NOT_IN_ARTIKEL) ? null : $cat;
        $data['tipe']        = in_array($cat, Artikel::TIPE_NOT_IN_ARTIKEL) ? $cat : 'dinamis';
        $data['id_user']     = auth()->id;
        // set null id_kategori, artikel tanpa kategori
        if ($data['id_kategori'] == -1) {
            $data['id_kategori'] = null;
        }

        // Kontributor tidak dapat mengaktifkan artikel
        if (auth()->id_grup == 4) {
            $data['enabled'] = StatusEnum::TIDAK;
        }

        // Upload dokumen lampiran
        // TODO: Sederhanakan cara unggah ini
        $lokasi_file = $_FILES['dokumen']['tmp_name'];
        $tipe_file   = TipeFile($_FILES['dokumen']);
        $nama_file   = $_FILES['dokumen']['name'];
        $ext         = get_extension($nama_file);
        $nama_file   = time() . random_int(10000, 999999) . $ext;

        if ($nama_file && ! empty($lokasi_file)) {
            if (! in_array($tipe_file, unserialize(MIME_TYPE_DOKUMEN), true) || ! in_array($ext, unserialize(EXT_DOKUMEN))) {
                unset($data['link_dokumen']);
                redirect_with('error', 'Jenis file salah: ' . $tipe_file);
            } else {
                $data['dokumen'] = $nama_file;
                if ($data['link_dokumen'] == '') {
                    $data['link_dokumen'] = $data['judul'];
                }
                UploadDocument2($nama_file);
            }
        }

        foreach ($list_gambar as $gambar) {
            unset($data['old_' . $gambar]);
        }
        if ($data['tgl_upload'] == '') {
            $data['tgl_upload'] = date('Y-m-d H:i:s');
        } else {
            $tempTgl            = date_create_from_format('d-m-Y H:i:s', $data['tgl_upload']);
            $data['tgl_upload'] = $tempTgl->format('Y-m-d H:i:s');
        }
        if ($data['tgl_agenda'] == '') {
            unset($data['tgl_agenda']);
        } else {
            $tempTgl            = date_create_from_format('d-m-Y H:i:s', $data['tgl_agenda']);
            $data['tgl_agenda'] = $tempTgl->format('Y-m-d H:i:s');
        }

        $data['slug'] = unique_slug('artikel', $data['judul']);

        try {
            $artikel = Artikel::create($data);
            if ($cat == AGENDA) {
                $agenda               = $this->ambil_data_agenda($data);
                $agenda['id_artikel'] = $artikel->id;
                Agenda::create($agenda);
            }
            redirect_with('success', 'Artikel berhasil ditambahkan', ci_route('web', $cat));
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Artikel gagal ditambahkan', ci_route('web', $cat));
        }

    }

    private function ambil_data_agenda(&$data)
    {
        $agenda               = [];
        $agenda['tgl_agenda'] = $data['tgl_agenda'];
        unset($data['tgl_agenda']);
        $agenda['koordinator_kegiatan'] = $data['koordinator_kegiatan'];
        unset($data['koordinator_kegiatan']);
        $agenda['lokasi_kegiatan'] = $data['lokasi_kegiatan'];
        unset($data['lokasi_kegiatan']);

        return $agenda;
    }

    public function update($cat, $id = 0): void
    {
        $artikel = Artikel::findOrFail($id);
        if (! $artikel->bolehUbah()) {
            redirect_with('error', 'Pengguna tidak diijinkan mengubah artikel ini', ci_route('web', $cat));
        }
        if (! in_array(auth()->id_grup, (new UserGrup())->getGrupSistem()) && $artikel->id_user != auth()->id) {
            redirect_with('error', 'Anda tidak memiliki hak akses untuk mengubah artikel ini', ci_route('web', $cat));
        }
        $data           = $_POST;
        $hapus_lampiran = $data['hapus_lampiran'];
        unset($data['hapus_lampiran']);

        if (empty($data['judul']) || empty($data['isi'])) {
            redirect_with('error', 'Judul atau isi harus diisi', ci_route('web', $cat));
        }

        // Batasi judul menggunakan teks polos
        $data['judul']    = judul($data['judul']);
        $data['tampilan'] = (int) $data['tampilan'];

        $fp          = time();
        $list_gambar = ['gambar', 'gambar1', 'gambar2', 'gambar3'];

        foreach ($list_gambar as $gambar) {
            $lokasi_file = $_FILES[$gambar]['tmp_name'];
            $nama_file   = $fp . '_' . $_FILES[$gambar]['name'];
            $nama_file   = trim(str_replace(' ', '_', $nama_file));

            if (! empty($lokasi_file)) {
                $tipe_file = TipeFile($_FILES[$gambar]);
                $hasil     = UploadArtikel($nama_file, $gambar);
                if ($hasil) {
                    $data[$gambar] = $nama_file;
                    HapusArtikel($data['old_' . $gambar]);
                } else {
                    unset($data[$gambar]);
                }
            } else {
                unset($data[$gambar]);
            }
        }

        foreach ($list_gambar as $gambar) {
            if (isset($data[$gambar . '_hapus'])) {
                HapusArtikel($data[$gambar . '_hapus']);
                $data[$gambar] = '';
                unset($data[$gambar . '_hapus']);
            }
        }

        // Upload dokumen lampiran
        // TODO: Sederhanakan cara unggah ini
        $lokasi_file = $_FILES['dokumen']['tmp_name'];
        $tipe_file   = TipeFile($_FILES['dokumen']);
        $nama_file   = $_FILES['dokumen']['name'];
        $ext         = get_extension($nama_file);
        $nama_file   = time() . random_int(10000, 999999) . $ext;

        if ($nama_file && ! empty($lokasi_file)) {
            if (! in_array($tipe_file, unserialize(MIME_TYPE_DOKUMEN)) || ! in_array($ext, unserialize(EXT_DOKUMEN))) {
                unset($data['link_dokumen']);
                $_SESSION['error_msg'] .= ' -> Jenis file salah: ' . $tipe_file;
                $_SESSION['success'] = -1;
            } else {
                $data['dokumen'] = $nama_file;
                if ($data['link_dokumen'] == '') {
                    $data['link_dokumen'] = $data['judul'];
                }
                UploadDocument2($nama_file);
            }
        }

        foreach ($list_gambar as $gambar) {
            unset($data['old_' . $gambar]);
        }
        if ($data['tgl_upload'] == '') {
            $data['tgl_upload'] = date('Y-m-d H:i:s');
        } else {
            $tempTgl            = date_create_from_format('d-m-Y H:i:s', $data['tgl_upload']);
            $data['tgl_upload'] = $tempTgl->format('Y-m-d H:i:s');
        }
        if ($data['tgl_agenda'] == '') {
            unset($data['tgl_agenda']);
        } else {
            $tempTgl            = date_create_from_format('d-m-Y H:i:s', $data['tgl_agenda']);
            $data['tgl_agenda'] = $tempTgl->format('Y-m-d H:i:s');
        }

        $data['slug'] = unique_slug('artikel', $data['judul'], $id);

        if ($hapus_lampiran == 'true') {
            $data['dokumen']      = null;
            $data['link_dokumen'] = '';
        }

        try {
            $artikel->update($data);
            if ($cat == AGENDA) {
                $agenda = $this->ambil_data_agenda($data);
                $id     = $data['id_agenda'];
                unset($data['id_agenda']);
                $agendaObj = Agenda::whereIdArtikel($id)->first();
                if ($agendaObj) {
                    $agendaObj->update($agenda);
                } else {
                    $agenda['id_artikel'] = $id;
                    $agendaObj->create($agenda);
                }
            }
            redirect_with('success', 'Artikel berhasil disimpan', ci_route('web', $cat));
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Artikel gagal disimpan', ci_route('web', $cat));
        }

    }

    public function delete($cat, $id = 0): void
    {
        isCan('h');
        Artikel::destroy($this->request['id_cb'] ?? decrypt($id));
        redirect_with('success', 'Artikel berhasil dihapus', ci_route('web', $cat));
    }

    // hapus artikel dalam kategori
    public function hapus($cat): void
    {
        isCan('h');
        Artikel::where('id_kategori', $cat)->delete();
        redirect_with('success', 'Artikel berhasil dihapus', ci_route('web', $cat));
    }

    public function ubah_kategori_form($id = 0): void
    {
        $id = decrypt($id);
        isCan('u');
        $artikel = Artikel::findOrFail($id);
        if (! $artikel->bolehUbah()) {
            redirect_with('error', 'Pengguna tidak diijinkan mengubah artikel ini');
        }

        $data['list_kategori']     = Kategori::with(['children' => static fn ($q) => $q->orderBy('urut')])->whereParrent(0)->get()->toArray();
        $data['form_action']       = ci_route('web.update_kategori', $id);
        $data['kategori_sekarang'] = $artikel->id_kategori;
        view('admin.web.artikel.ajax_ubah_kategori_form', $data);
    }

    public function update_kategori($id = 0): void
    {
        isCan('u');
        $artikel = Artikel::findOrFail($id);
        if (! $artikel->bolehUbah()) {
            redirect_with('error', 'Pengguna tidak diijinkan mengubah artikel ini', ci_route('web', $artikel->id_kategori));
        }

        $cat                  = $this->input->post('kategori');
        $artikel->id_kategori = $cat;
        $artikel->save();
        redirect_with('sukses', 'Kategori artikel berhasil dirubah', ci_route('web', $cat));
    }

    public function lock($cat, $column, $id = 0): void
    {
        isCan('u');
        $pesan   = 'Status Artikel';
        $onlyOne = false;

        switch ($column) {
            case 'enabled':
                $pesan = 'Status Artikel';
                break;

            case 'boleh_komentar':
                $pesan = 'Status Komentar';
                break;

            case 'headline':
                $pesan   = 'Status Berita Utama';
                $onlyOne = true;
                break;

            case 'slider':
                $pesan = 'Status Slide';
                break;
        }
        if (Artikel::gantiStatus(decrypt($id), $column, $onlyOne)) {
            redirect_with('success', 'Berhasil Ubah ' . $pesan, ci_route('web', $cat));
        }

        redirect_with('error', 'Gagal Ubah ' . $pesan, ci_route('web', $cat));
    }

    public function slider(): void
    {
        $this->sub_modul_ini = 'slider';

        view('admin.web.slider.index');
    }

    public function update_slider(): void
    {
        // Kontributor tidak boleh melakukan ini
        isCan('u');

        SettingAplikasi::where('key', 'sumber_gambar_slider')->update(['value' => $this->input->post('pilihan_sumber')]);
        SettingAplikasi::where('key', 'jumlah_gambar_slider')->update(['value' => $this->input->post('jumlah_gambar_slider')]);
        cache()->forget('setting_aplikasi');
        redirect('web/slider');
    }

    public function reset($cat): void
    {
        isCan('u');
        if ($cat == 'statis') {
            $persen      = $this->input->post('hit');
            $menuArtikel = Menu::active()->artikel()->get();
            if ($menuArtikel) {
                foreach ($menuArtikel as $item) {
                    $id      = str_replace('artikel/', '', $item->link);
                    $artikel = Artikel::find($id);
                    if ($artikel) {
                        $artikel->hit *= $persen / 100;
                        $artikel->save();
                    }
                }
            }
        }

        redirect_with('success', 'Hit telah direset', ci_route('web', $cat));
    }
}
