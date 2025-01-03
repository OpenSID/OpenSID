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

use App\Enums\SasaranEnum;
use App\Models\Bantuan;
use App\Models\BantuanPeserta;
use Illuminate\Support\Str;

class Peserta_bantuan extends Admin_Controller
{
    public $modul_ini        = 'bantuan';
    public $akses_modul      = 'peserta-bantuan';
    private array $_set_page = ['20', '50', '100'];

    public function __construct()
    {
        parent::__construct();
        isCan('b', 'peserta-bantuan');
        $this->load->model(['program_bantuan_model']);
    }

    public function detail($program_id = 0, $p = 1): void
    {
        $program = Bantuan::getProgramPeserta($program_id)['detail'];

        $data['detail']       = $program;
        $data['controller']   = $this->controller;
        $data['nama_excerpt'] = Str::limit($program['nama'], 25);

        $data['list_sasaran'] = SasaranEnum::all();
        $data['func']         = "detail/{$program_id}";

        view('admin.program_bantuan.peserta.index', $data);
    }

    public function datatables($program_id = 0)
    {
        if ($this->input->is_ajax_request()) {
            $program   = Bantuan::getProgramPeserta($program_id);
            $sasaran   = $program['detail']['sasaran'];
            $data      = $program['peserta'] ?? [];
            $canDelete = can('h');

            return datatables()->of($data)
                ->addColumn('ceklist', static function ($row) use ($canDelete) {
                    if ($canDelete) {
                        return '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>';
                    }
                })
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row): string {
                    $aksi = '';

                    if (can('u')) {
                        $aksi .= '<a href="' . site_url("peserta_bantuan/edit_peserta_form/{$row->id}/{$row->program_id}") . '" class="btn bg-orange btn-sm" title="Ubah" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Ubah Data Peserta"><i class="fa fa-edit"></i></a>';
                    }

                    if (can('h')) {
                        $aksi .= '<a href="#" data-href="' . site_url("peserta_bantuan/hapus_peserta/{$row->id}/{$row->program_id}") . '" class="btn bg-maroon btn-sm" title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>';
                    }

                    return $aksi;
                })
                ->editColumn('peserta_nama', static function ($row) use ($sasaran) {
                    $id_peserta = ($row->sasaran == 4) ? $row->peserta : $row->nik;

                    return '<a href="' . site_url("peserta_bantuan/peserta/{$sasaran}/{$id_peserta}") . '" title="Daftar program untuk peserta">' . $row->peserta_nama . '</a>';
                })
                ->editColumn('no_id_kartu', static fn ($row) => '<a href="' . site_url("peserta_bantuan/data_peserta/{$row->id}/{$row->program_id}") . '" title="Daftar peserta">' . $row->no_id_kartu . '</a>')
                ->editColumn('kartu_tanggal_lahir', static fn ($row): string => tgl_indo_out($row->kartu_tanggal_lahir))
                ->rawColumns(['aksi', 'peserta_nama', 'ceklist', 'no_id_kartu'])
                ->make();
        }

        return show_404();
    }

    public function form($program_id = 0): void
    {
        isCan('u', 'peserta-bantuan');
        $this->session->unset_userdata('cari');
        $data['program'] = Bantuan::getProgramPeserta($program_id);
        $data['detail']  = $data['program']['detail'];
        $sasaran         = $data['detail']['sasaran'];
        $nik             = $this->input->post('nik');

        if (isset($nik)) {
            $data['individu']            = Bantuan::getPeserta($nik, $sasaran);
            $data['individu']['program'] = BantuanPeserta::getPesertaProgram($sasaran, $data['individu']['id_peserta']);
        } else {
            $data['individu'] = null;
        }

        $data['form_action']  = site_url('peserta_bantuan/add_peserta/' . $program_id);
        $data['list_sasaran'] = SasaranEnum::all();

        view('admin.program_bantuan.peserta.form', $data);
    }

    // $id = program_peserta.id
    public function peserta($cat = 0, $id = 0): void
    {
        $data['profil'] = BantuanPeserta::getPesertaProgram($cat, $id)['profil'];
        $data['cat']    = $cat;
        $data['id']     = $id;
        view('admin.program_bantuan.peserta.detail', $data);
    }

    public function datatable_peserta()
    {
        if ($this->input->is_ajax_request()) {
            $cat  = $this->input->get('cat');
            $id   = $this->input->get('id');
            $data = BantuanPeserta::getPesertaProgram($cat, $id)['programkerja'];

            return datatables()->of($data)
                ->addIndexColumn()
                ->editColumn('nama', static fn ($row): string => '<a href="' . site_url("peserta_bantuan/detail/{$row->id}") . '">' . $row->nama . '</a>')
                ->editColumn('tanggal', static fn ($row): string => fTampilTgl($row->sdate, $row->edate))
                ->rawColumns(['tanggal', 'nama'])
                ->make();
        }

        return show_404();
    }

    // $id = program_peserta.id
    public function data_peserta($id = 0, $program_id = null): void
    {
        $program         = Bantuan::getProgramPeserta($program_id);
        $peserta         = collect($program['peserta'])->where('id', $id)->first();
        $data['peserta'] = collect($peserta)->toArray();

        switch ($program['detail']['sasaran']) {
            case '1':
            case '2':
                $peserta_id = $data['peserta']['kartu_id_pend'];
                break;

            case '3':
            case '4':
                $peserta_id = $data['peserta']['peserta'];
                break;
        }

        $data['individu']            = Bantuan::getPeserta($peserta_id, $program['detail']['sasaran']);
        $data['individu']['program'] = BantuanPeserta::getPesertaProgram($program['detail']['sasaran'], $data['peserta']['peserta']);
        $data['detail']              = $program['detail'];
        $data['list_sasaran']        = SasaranEnum::all();
        view('admin.program_bantuan.peserta.data_peserta', $data);
    }

    public function add_peserta($program_id = 0): void
    {
        isCan('u', 'peserta-bantuan');

        $cek = BantuanPeserta::where('program_id', $program_id)->where('kartu_id_pend', $this->input->post('kartu_id_pend'))->first();

        if ($cek) {
            redirect_with('error', 'Data peserta sudah ada', "peserta_bantuan/detail/{$program_id}");
        } else {
            $this->process($program_id);
        }

        $redirect = ($this->session->userdata('aksi') != 1) ? $_SERVER['HTTP_REFERER'] : "peserta_bantuan/detail/{$program_id}";

        $this->session->unset_userdata('aksi');

        redirect_with('success', 'Peserta berhasil ditambahkan', $redirect);
    }

    public function process($program_id, $id = null): void
    {
        $data               = $this->validasi_peserta($this->input->post());
        $data['program_id'] = $program_id;

        if ($id === null) {
            $data['peserta'] = $this->input->post('peserta');
        }

        if ($_FILES['file']['name']) {
            $data['kartu_peserta'] = unggah_file(['upload_path' => LOKASI_DOKUMEN, 'allowed_types' => 'jpg|jpeg|png']);
        }

        $hapus_gambar_lama = $this->input->post('gambar_hapus');
        if ($hapus_gambar_lama) {
            $foto = LOKASI_DOKUMEN . BantuanPeserta::find($id)->kartu_peserta;
            if (file_exists($foto)) {
                unlink($foto);
                $data['kartu_peserta'] = '';
            }
        }

        $outp = BantuanPeserta::updateOrCreate(['id' => $id], $data);
        status_sukses($outp, true);
    }

    public function validasi_peserta($post)
    {
        $data['config_id']           = identitas('id');
        $data['no_id_kartu']         = nama_terbatas($post['no_id_kartu']);
        $data['kartu_nik']           = bilangan($post['kartu_nik']);
        $data['kartu_nama']          = nama(htmlentities($post['kartu_nama']));
        $data['kartu_tempat_lahir']  = alamat(htmlentities($post['kartu_tempat_lahir']));
        $data['kartu_tanggal_lahir'] = date_is_empty($post['kartu_tanggal_lahir']) ? null : tgl_indo_in($post['kartu_tanggal_lahir']);
        $data['kartu_alamat']        = alamat(htmlentities($post['kartu_alamat']));

        if ($post['kartu_id_pend']) {
            $data['kartu_id_pend'] = bilangan($post['kartu_id_pend']);
        }

        return $data;
    }

    // $id = program_peserta.id
    public function edit_peserta($id = 0): void
    {
        isCan('u', 'peserta-bantuan');
        $program_id = $this->input->post('program_id');
        $this->process($program_id, $id);
        redirect("peserta_bantuan/detail/{$program_id}");
    }

    // $id = program_peserta.id
    public function edit_peserta_form($id = 0, $program_id = null): void
    {
        isCan('u', 'peserta-bantuan');

        $program                    = Bantuan::getProgramPeserta($program_id);
        $peserta                    = collect($program['peserta'])->where('id', $id)->first();
        $data                       = collect($peserta)->toArray();
        $data['judul_peserta_info'] = $program['detail']['judul_peserta_info'];
        $data['judul_peserta']      = $program['detail']['judul_peserta'];
        $data['form_action']        = site_url("peserta_bantuan/edit_peserta/{$id}");
        view('admin.program_bantuan.peserta.edit', $data);
    }

    public function aksi($aksi = '', $program_id = 0): void
    {
        isCan('u', 'peserta-bantuan');
        $this->session->set_userdata('aksi', $aksi);

        redirect("peserta_bantuan/form/{$program_id}");
    }

    public function hapus_peserta($peserta_id = 0, $program_id = ''): void
    {
        isCan('h', 'peserta-bantuan');

        if (BantuanPeserta::destroy($peserta_id)) {
            redirect_with('success', 'Berhasil Hapus Data', "peserta_bantuan/detail/{$program_id}");
        }

        redirect_with('error', 'Gagal Hapus Data', "peserta_bantuan/detail/{$program_id}");
    }

    public function delete_all($program_id): void
    {
        isCan('h', 'peserta-bantuan');

        if (BantuanPeserta::destroy($this->request['id_cb'])) {
            redirect_with('success', 'Berhasil Hapus Data', "peserta_bantuan/detail/{$program_id}");
        }

        redirect_with('error', 'Gagal Hapus Data', "peserta_bantuan/detail/{$program_id}");
    }

    // aksi cetak/unduh
    public function daftar($program_id = 0, $aksi = '')
    {
        if ($program_id > 0) {
            // $data                = $this->modal_penandatangan();
            $data['aksi'] = $aksi;
            $data['main'] = Bantuan::getProgramPeserta($program_id);
            $data['file'] = 'Peserta Bantuan';
            $data['isi']  = 'admin.program_bantuan.peserta.cetak';
            // $data['letak_ttd']   = ['2', '2', '9'];
            $data['sasaran'] = unserialize(SASARAN);

            return view('admin.layouts.components.format_cetak', $data);
        }
    }

    public function detail_clear($program_id): void
    {
        $this->session->per_page = $this->_set_page[0];
        $this->session->unset_userdata('cari');

        redirect("peserta_bantuan/detail/{$program_id}");
    }
}
