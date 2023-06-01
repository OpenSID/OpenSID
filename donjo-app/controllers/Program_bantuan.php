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

use App\Enums\SasaranEnum;
use App\Models\Bantuan;
use App\Models\BantuanPeserta;
use App\Models\Kelompok;
use App\Models\Penduduk;
use Illuminate\Support\Str;
use OpenSpout\Common\Entity\Style\Color;
use OpenSpout\Reader\Common\Creator\ReaderEntityFactory;
use OpenSpout\Writer\Common\Creator\Style\StyleBuilder;
use OpenSpout\Writer\Common\Creator\WriterEntityFactory;

class Program_bantuan extends Admin_Controller
{
    private $_set_page;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['program_bantuan_model']);
        $this->modul_ini = 'bantuan';
        $this->_set_page = ['20', '50', '100'];
    }

    public function clear()
    {
        $this->session->per_page = $this->_set_page[0];
        $this->session->unset_userdata('sasaran');
        redirect('program_bantuan');
    }

    public function filter($filter)
    {
        $value = $this->input->post($filter);
        if ($value != '') {
            $this->session->{$filter} = $value;
        } else {
            $this->session->unset_userdata($filter);
        }
        redirect('program_bantuan');
    }

    public function index($p = 1)
    {
        $this->session->unset_userdata('cari');
        $per_page = $this->input->post('per_page');
        if (isset($per_page)) {
            $this->session->per_page = $per_page;
        }

        $data                 = $this->program_bantuan_model->get_program($p, false);
        $data['list_sasaran'] = SasaranEnum::all();
        $data['func']         = 'index';
        $data['per_page']     = $this->session->per_page;
        $data['set_page']     = $this->_set_page;
        $data['set_sasaran']  = $this->session->sasaran;
        $this->render('program_bantuan/program', $data);
    }

    public function form($program_id = 0)
    {
        // dd(Penduduk::whereHas('rtm')->get());
        $this->redirect_hak_akses('u');
        $this->session->unset_userdata('cari');
        $data['program'] = $this->program_bantuan_model->get_program(1, $program_id);
        $sasaran         = $data['program'][0]['sasaran'];
        $nik             = $this->input->post('nik');

        if (isset($nik)) {
            $data['individu']            = $this->program_bantuan_model->get_peserta($nik, $sasaran);
            $data['individu']['program'] = $this->program_bantuan_model->get_peserta_program($sasaran, $data['individu']['id_peserta']);
        } else {
            $data['individu'] = null;
        }

        $data['form_action']  = site_url('program_bantuan/add_peserta/' . $program_id);
        $data['list_sasaran'] = SasaranEnum::all();

        $this->render('program_bantuan/form', $data);
    }

    public function apipendudukbantuan()
    {
        if ($this->input->is_ajax_request()) {
            $cari    = $this->input->get('q');
            $bantuan = $this->input->get('bantuan');
            $sasaran = $this->input->get('sasaran');
            $peserta = BantuanPeserta::where('program_id', '=', $bantuan)->pluck('peserta');

            switch ($sasaran) {
                case 1:
                    $this->get_pilihan_penduduk($cari, $peserta);
                    break;

                case 2:
                    $this->get_pilihan_kk($cari, $peserta);
                    break;

                case 3:
                    $this->get_pilihan_rtm($cari, $peserta);
                    break;

                case 4:
                    $this->get_pilihan_kelompok($cari, $peserta);
                    break;

                default:
            }
        }

        return show_404();
    }

    private function get_pilihan_penduduk($cari, $peserta)
    {
        $penduduk = Penduduk::with('rtm')->whereHas('rtm')
            ->select(['id', 'nik', 'nama', 'id_cluster'])
            ->when($cari, static function ($query) use ($cari) {
                $query->orWhere('nik', 'like', "%{$cari}%")
                    ->orWhere('nama', 'like', "%{$cari}%");
            })
            ->whereNotIn('nik', $peserta)
            ->paginate(10);

        return json([
            'results' => collect($penduduk->items())
                ->map(static function ($item) {
                    return [
                        'id'   => $item->id,
                        'text' => 'NIK : ' . $item->nik . ' - ' . $item->nama . ' RT-' . $item->wilayah->rt . ', RW-' . $item->wilayah->rw . ', ' . strtoupper(setting('sebutan_dusun')) . ' ' . $item->wilayah->dusun,
                    ];
                }),
            'pagination' => [
                'more' => $penduduk->currentPage() < $penduduk->lastPage(),
            ],
        ]);
    }

    private function get_pilihan_kk($cari, $peserta)
    {
        $penduduk = Penduduk::with('pendudukHubungan')
            ->select(['tweb_penduduk.id', 'tweb_penduduk.nik', 'keluarga_aktif.no_kk', 'tweb_penduduk.kk_level', 'tweb_penduduk.nama', 'tweb_penduduk.id_cluster'])
            ->leftJoin('tweb_penduduk_hubungan', static function ($join) {
                $join->on('tweb_penduduk.kk_level', '=', 'tweb_penduduk_hubungan.id');
            })
            ->leftJoin('keluarga_aktif', static function ($join) {
                $join->on('tweb_penduduk.id_kk', '=', 'keluarga_aktif.id');
            })
            ->when($cari, static function ($query) use ($cari) {
                $query->orWhere('tweb_penduduk.nik', 'like', "%{$cari}%")
                    ->orWhere('keluarga_aktif.no_kk', 'like', "%{$cari}%")
                    ->orWhere('tweb_penduduk.nama', 'like', "%{$cari}%");
            })
            ->whereIn('tweb_penduduk.kk_level', ['1', '2', '3', '4'])
            ->whereNotIn('keluarga_aktif.no_kk', $peserta)
            ->orderBy('tweb_penduduk.id_kk')
            ->paginate(10);

        return json([
            'results' => collect($penduduk->items())
                ->map(static function ($item) {
                    return [
                        'id'   => $item->id,
                        'text' => 'No KK : ' . $item->no_kk . ' - ' . $item->pendudukHubungan->nama . '- NIK : ' . $item->nik . ' - ' . $item->nama . ' RT-' . $item->wilayah->rt . ', RW-' . $item->wilayah->rw . ', ' . strtoupper(setting('sebutan_dusun')) . ' ' . $item->wilayah->dusun,
                    ];
                }),
            'pagination' => [
                'more' => $penduduk->currentPage() < $penduduk->lastPage(),
            ],
        ]);
    }

    private function get_pilihan_rtm($cari, $peserta)
    {
        $penduduk = Penduduk::select(['id', 'id_rtm', 'nama', 'id_cluster'])
            ->when($cari, static function ($query) use ($cari) {
                $query->orWhere('nama', 'like', "%{$cari}%");
            })
            ->whereHas('rtm', static function ($query) use ($peserta) {
                $query->whereNotIn('no_kk', $peserta);
            })
            ->paginate(10);

        return json([
            'results' => collect($penduduk->items())
                ->map(static function ($item) {
                    return [
                        'id'   => $item->rtm->no_kk,
                        'text' => 'No KK : ' . $item->rtm->no_kk . ' - ' . $item->nama . ' RT-' . $item->wilayah->rt . ', RW-' . $item->wilayah->rw . ', ' . strtoupper(setting('sebutan_dusun')) . ' ' . $item->wilayah->dusun,
                    ];
                }),
            'pagination' => [
                'more' => $penduduk->currentPage() < $penduduk->lastPage(),
            ],
        ]);
    }

    private function get_pilihan_kelompok($cari, $peserta)
    {
        $penduduk = Kelompok::select(['kelompok.id', 'tweb_penduduk.nik', 'tweb_penduduk.nama as nama_penduduk', 'kelompok.nama as nama_kelompok', 'tweb_penduduk.id_cluster'])
            ->leftJoin('tweb_penduduk', static function ($join) {
                $join->on('kelompok.id_ketua', '=', 'tweb_penduduk.id');
            })
            ->when($cari, static function ($query) use ($cari) {
                $query->orWhere('kelompok.nama', 'like', "%{$cari}%")
                    ->orWhere('tweb_penduduk.nama', 'like', "%{$cari}%");
            })
            ->whereNotIn('kelompok.id', $peserta)
            ->paginate(10);

        return json([
            'results' => collect($penduduk->items())
                ->map(static function ($item) {
                    return [
                        'id'   => $item->id,
                        'text' => $item->nama_penduduk . ' [' . $item->nama_kelompok . ']' . ' RT-' . $item->wilayah->rt . ', RW-' . $item->wilayah->rw . ', ' . strtoupper(setting('sebutan_dusun')) . ' ' . $item->wilayah->dusun,
                    ];
                }),
            'pagination' => [
                'more' => $penduduk->currentPage() < $penduduk->lastPage(),
            ],
        ]);
    }

    public function panduan()
    {
        $this->render('program_bantuan/panduan');
    }

    public function detail_clear($program_id)
    {
        $this->session->per_page = $this->_set_page[0];
        $this->session->unset_userdata('cari');

        redirect("program_bantuan/detail/{$program_id}");
    }

    public function detail($program_id = 0, $p = 1)
    {
        $per_page = $this->input->post('per_page');
        if (isset($per_page)) {
            $this->session->per_page = $per_page;
        }

        $data['cari']         = $this->session->cari ?: '';
        $data['program']      = $this->program_bantuan_model->get_program($p, $program_id);
        $data['keyword']      = $this->program_bantuan_model->autocomplete($program_id, $this->input->post('cari'));
        $data['paging']       = $data['program'][0]['paging'];
        $data['list_sasaran'] = SasaranEnum::all();
        $data['p']            = $p;
        $data['func']         = "detail/{$program_id}";
        $data['per_page']     = $this->session->per_page;
        $data['set_page']     = $this->_set_page;
        $data['nama_excerpt'] = Str::limit($data['program'][0]['nama'], 25);

        $this->render('program_bantuan/detail', $data);
    }

    // $id = program_peserta.id
    public function peserta($cat = 0, $id = 0)
    {
        $data = $this->program_bantuan_model->get_peserta_program($cat, $id);

        $this->render('program_bantuan/peserta', $data);
    }

    // $id = program_peserta.id
    public function data_peserta($id = 0)
    {
        $data['peserta'] = $this->program_bantuan_model->get_program_peserta_by_id($id);

        switch ($data['peserta']['sasaran']) {
            case '1':
            case '2':
                $peserta_id = $data['peserta']['kartu_id_pend'];
                break;

            case '3':
            case '4':
                $peserta_id = $data['peserta']['peserta'];
                break;
        }
        $data['individu']            = $this->program_bantuan_model->get_peserta($peserta_id, $data['peserta']['sasaran']);
        $data['individu']['program'] = $this->program_bantuan_model->get_peserta_program($data['peserta']['sasaran'], $data['peserta']['peserta']);
        $data['detail']              = $this->program_bantuan_model->get_data_program($data['peserta']['program_id']);

        $this->render('program_bantuan/data_peserta', $data);
    }

    public function add_peserta($program_id = 0)
    {
        $this->redirect_hak_akses('u');

        $cek = BantuanPeserta::where('program_id', $program_id)->where('kartu_id_pend', $this->input->post('kartu_id_pend'))->first();

        if ($cek) {
            $this->session->success = -2;
        } else {
            $this->program_bantuan_model->add_peserta($program_id);
        }

        $redirect = ($this->session->userdata('aksi') != 1) ? $_SERVER['HTTP_REFERER'] : "program_bantuan/detail/{$program_id}";

        $this->session->unset_userdata('aksi');

        redirect($redirect);
    }

    public function aksi($aksi = '', $program_id = 0)
    {
        $this->redirect_hak_akses('u');
        $this->session->set_userdata('aksi', $aksi);

        redirect("program_bantuan/form/{$program_id}");
    }

    public function hapus_peserta($program_id = 0, $peserta_id = '')
    {
        $this->redirect_hak_akses('h');
        $this->program_bantuan_model->hapus_peserta($peserta_id);
        redirect("program_bantuan/detail/{$program_id}");
    }

    public function delete_all($program_id = 0)
    {
        $this->redirect_hak_akses('h');
        $this->program_bantuan_model->delete_all();
        redirect("program_bantuan/detail/{$program_id}");
    }

    // $id = program_peserta.id
    public function edit_peserta($id = 0)
    {
        $this->redirect_hak_akses('u');
        $this->program_bantuan_model->edit_peserta($id);
        $program_id = $this->input->post('program_id');
        redirect("program_bantuan/detail/{$program_id}");
    }

    // $id = program_peserta.id
    public function edit_peserta_form($id = 0)
    {
        $this->redirect_hak_akses('u');

        $data                = $this->program_bantuan_model->get_program_peserta_by_id($id) ?? show_404();
        $data['form_action'] = site_url("program_bantuan/edit_peserta/{$id}");
        $this->load->view('program_bantuan/edit_peserta', $data);
    }

    public function create()
    {
        $this->redirect_hak_akses('u');
        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('cid', 'Sasaran', 'required');
        $this->form_validation->set_rules('nama', 'Nama Program', 'required');
        $this->form_validation->set_rules('sdate', 'Tanggal awal', 'required');
        $this->form_validation->set_rules('edate', 'Tanggal akhir', 'required');
        $this->form_validation->set_rules('asaldana', 'Asal Dana', 'required');

        $data['asaldana'] = unserialize(ASALDANA);

        if ($this->form_validation->run() === false) {
            $this->render('program_bantuan/create', $data);
        } else {
            $this->program_bantuan_model->set_program();
            redirect('program_bantuan');
        }
    }

    // $id = program.id
    public function edit($id = 0)
    {
        $this->redirect_hak_akses('u');
        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('cid', 'Sasaran', 'required');
        $this->form_validation->set_rules('nama', 'Nama Program', 'required');
        $this->form_validation->set_rules('sdate', 'Tanggal awal', 'required');
        $this->form_validation->set_rules('edate', 'Tanggal akhir', 'required');
        $this->form_validation->set_rules('asaldana', 'Asal Dana', 'required');

        Bantuan::findOrFail($id);
        $data['asaldana']     = unserialize(ASALDANA);
        $data['program']      = $this->program_bantuan_model->get_program(1, $id) ?? show_404();
        $data['jml']          = $this->program_bantuan_model->jml_peserta_program($id);
        $data['nama_excerpt'] = Str::limit($data['program'][0]['nama'], 25);

        if ($this->form_validation->run() === false) {
            $this->render('program_bantuan/edit', $data);
        } else {
            $this->program_bantuan_model->update_program($id);
            redirect('program_bantuan');
        }
    }

    // $id = program.id
    public function update($id)
    {
        $this->redirect_hak_akses('u');
        $this->program_bantuan_model->update_program($id);
        redirect("program_bantuan/detail/{$id}");
    }

    // $id = program.id
    public function hapus($id)
    {
        $this->redirect_hak_akses('h');
        $this->program_bantuan_model->hapus_program($id);
        redirect('program_bantuan');
    }

    // $aksi = cetak/unduh
    public function daftar($program_id = 0, $aksi = '')
    {
        if ($program_id > 0) {
            $temp                    = $this->session->per_page;
            $this->session->per_page = 1000000000; // Angka besar supaya semua data terunduh
            $data['sasaran']         = unserialize(SASARAN);

            $data['config']          = $this->header['desa'];
            $data['peserta']         = $this->program_bantuan_model->get_program(1, $program_id);
            $data['aksi']            = $aksi;
            $this->session->per_page = $temp;

            $this->load->view("program_bantuan/{$aksi}", $data);
        }
    }

    public function search($program_id = 0)
    {
        $cari = $this->input->post('cari');

        if ($cari != '') {
            $this->session->cari = $cari;
        } else {
            $this->session->unset_userdata('cari');
        }

        redirect("program_bantuan/detail/{$program_id}");
    }

    // TODO: function ini terlalu panjang dan sebaiknya dipecah menjadi beberapa method
    public function impor()
    {
        $this->redirect_hak_akses('u');

        $this->load->library('MY_Upload', null, 'upload');
        $this->upload->initialize([
            'upload_path'   => sys_get_temp_dir(),
            'allowed_types' => 'xls|xlsx|xlsm',
            'file_name'     => namafile('Impor Peserta Program Bantuan'),
        ]);

        if ($this->upload->do_upload('userfile')) {
            $program_id = '';
            // Data Program Bantuan
            $temp                    = $this->session->per_page;
            $this->session->per_page = 1000000000;
            $ganti_program           = $this->input->post('ganti_program');
            $kosongkan_peserta       = $this->input->post('kosongkan_peserta');
            $ganti_peserta           = $this->input->post('ganti_peserta');
            $rand_kartu_peserta      = $this->input->post('rand_kartu_peserta');

            $upload = $this->upload->data();
            $reader = ReaderEntityFactory::createXLSXReader();
            $reader->open($upload['full_path']);

            $data_program = [];
            $data_peserta = [];
            $data_diubah  = '';

            foreach ($reader->getSheetIterator() as $sheet) {
                $no_baris  = 0;
                $no_gagal  = 0;
                $no_sukses = 0;

                // Sheet Program
                if ($sheet->getName() == 'Program') {
                    $pesan_program  = '';
                    $daftar_program = Bantuan::pluck('id')->toArray();
                    $field          = ['id', 'nama', 'sasaran', 'ndesc', 'asaldana', 'sdate', 'edate'];

                    foreach ($sheet->getRowIterator() as $row) {
                        $cells = $row->getCells();
                        $title = (string) $cells[0];
                        $value = $this->cek_is_date($cells[1]);

                        // Data terakhir
                        if ($title == '###') {
                            break;
                        }

                        if (in_array($no_baris, [5, 6]) && ! validate_date($value, 'Y-m-d')) {
                            session_error(', Data program baris <b> Ke-' . ($no_baris) . '</b> berisi tanggal yang salah. Cek kembali data ' . $title . ' = ' . $value);

                            redirect($this->controller);
                        }

                        switch (true) {
                            /**
                             * baris 1 == id
                             * id bernilai NULL/Kosong( )/Strip(-)/tdk valid, buat program baru dan tampilkan notifkasi tambah program
                             * id bernilai id dan valid, update data program dan tampilkan notifkasi update program
                             */
                            case $no_baris == 0 && (in_array((int) $value, $daftar_program)):
                                $program_id = $value;
                                if (null === $ganti_program) {
                                    $pesan_program .= 'Data program dengan <b> id = ' . ($value) . '</b> ditemukan, data lama tetap digunakan <br>';
                                } else {
                                    $pesan_program .= 'Data program dengan <b> id = ' . ($value) . '</b> ditemukan, data lama diganti dengan data baru <br>';
                                }
                                break;

                            case $no_baris == 0 && ! in_array((int) $value, $daftar_program):
                                $program_id = null;
                                $pesan_program .= 'Data program dengan <b> id = ' . ($value) . '</b> tidak ditemukan, program baru ditambahkan secara otomatis) <br>';
                                break;

                            default:
                                $data_program = array_merge($data_program, [$field[$no_baris] => $value]);
                                break;
                        }
                        $no_baris++;
                    }

                    // Proses impor program
                    $program_id = $this->program_bantuan_model->impor_program($program_id, $data_program, $ganti_program);
                }

                // Sheet Peserta
                else {
                    $pesan_peserta = '';
                    $ambil_peserta = Bantuan::select('id', 'sasaran')->with(['peserta' => static function ($query) {
                        $query->select('program_id', 'peserta');
                    }])->find($program_id);
                    $sasaran           = (int) $ambil_peserta->sasaran;
                    $terdaftar_peserta = $ambil_peserta->peserta->pluck('peserta')->toArray();

                    if ($kosongkan_peserta == 1) {
                        $pesan_peserta .= '- Data peserta ' . ($ambil_peserta[0]['nama']) . ' sukses dikosongkan<br>';
                        $terdaftar_peserta = null;
                    }

                    foreach ($sheet->getRowIterator() as $row) {
                        $no_baris++;
                        $cells   = $row->getCells();
                        $peserta = (string) $cells[0];
                        $nik     = (string) $cells[2];

                        // Data terakhir
                        if ($peserta == '###') {
                            break;
                        }

                        // Abaikan baris pertama / judul
                        if ($no_baris <= 1) {
                            continue;
                        }

                        // Cek valid data peserta sesuai sasaran
                        $cek_peserta = $this->program_bantuan_model->cek_peserta($peserta, $sasaran);
                        if (! in_array($nik, $cek_peserta['valid'])) {
                            $no_gagal++;
                            $pesan_peserta .= '- Data peserta baris <b> Ke-' . ($no_baris) . ' / ' . $cek_peserta['sasaran_peserta'] . ' = ' . $peserta . '</b> tidak ditemukan <br>';

                            continue;
                        }

                        // Cek valid data penduduk sesuai nik
                        $cek_penduduk = $this->penduduk_model->get_penduduk_by_nik($nik);
                        if (! $cek_penduduk['id']) {
                            $no_gagal++;
                            $pesan_peserta .= '- Data peserta baris <b> Ke-' . ($no_baris) . ' / NIK = ' . $nik . '</b> yang terdaftar tidak ditemukan <br>';

                            continue;
                        }

                        // Cek data peserta yg akan dimpor dan yg sudah ada
                        if (in_array($peserta, $terdaftar_peserta) && $ganti_peserta != 1) {
                            $no_gagal++;
                            $pesan_peserta .= '- Data peserta baris <b> Ke-' . ($no_baris) . '</b> sudah ada <br>';

                            continue;
                        }

                        if (in_array($peserta, $terdaftar_peserta) && $ganti_peserta == 1) {
                            $data_diubah   .= ', ' . $peserta;
                            $pesan_peserta .= '- Data peserta baris <b> Ke-' . ($no_baris) . '</b> ditambahkan menggantikan data lama <br>';
                        }

                        // Jika kosong ambil data dari database
                        $no_id_kartu         = (string) $cells[1];
                        $kartu_nama          = (string) $cells[3];
                        $kartu_tempat_lahir  = (string) $cells[4];
                        $kartu_tanggal_lahir = $cells[5];
                        $kartu_tanggal_lahir = $this->cek_is_date($kartu_tanggal_lahir);
                        $kartu_alamat        = (string) $cells[6];
                        if (empty($kartu_tanggal_lahir)) {
                            $kartu_tanggal_lahir = $cek_penduduk['tanggallahir'];
                        } else {
                            if (! validate_date($kartu_tanggal_lahir, 'Y-m-d')) {
                                $no_gagal++;
                                $pesan_peserta .= '- Data peserta baris <b> Ke-' . ($no_baris) . '</b> berisi tanggal yang salah<br>';

                                continue;
                            }
                        }

                        // Random no. kartu peserta
                        if ($rand_kartu_peserta == 1) {
                            $no_id_kartu = 'acak_' . random_int(1, 1000);
                        }

                        // Ubaha data peserta menjadi id (untuk saat ini masih data kelompok yg menggunakan id)
                        // Berkaitan dgn issue #3417
                        if ($sasaran == 4) {
                            $peserta = $cek_peserta['id'];
                        }

                        // Simpan data peserta yg diimpor dalam bentuk array
                        $simpan = [
                            'config_id'           => identitas('id'),
                            'peserta'             => $peserta,
                            'program_id'          => $program_id,
                            'no_id_kartu'         => $no_id_kartu,
                            'kartu_nik'           => $nik,
                            'kartu_nama'          => $kartu_nama ?: $cek_penduduk['nama'],
                            'kartu_tempat_lahir'  => $kartu_tempat_lahir ?: $cek_penduduk['tempatlahir'],
                            'kartu_tanggal_lahir' => $kartu_tanggal_lahir,
                            'kartu_alamat'        => $kartu_alamat ?: $cek_penduduk['alamat_wilayah'],
                            'kartu_id_pend'       => $cek_penduduk['id'],
                        ];

                        $data_peserta[] = $simpan;
                        $no_sukses++;
                    }

                    // Proses impor peserta
                    if ($no_baris <= 0) {
                        $pesan_peserta .= '- Data peserta tidak tersedia<br>';
                    } else {
                        $this->program_bantuan_model->impor_peserta($program_id, $data_peserta, $kosongkan_peserta, $data_diubah);
                    }
                }
            }
            $reader->close();

            $notif = [
                'program' => $pesan_program,
                'gagal'   => $no_gagal,
                'sukses'  => $no_sukses,
                'peserta' => $pesan_peserta,
            ];

            $this->session->set_flashdata('notif', $notif);
            $this->session->per_page = $temp;

            redirect("{$this->controller}/detail/{$program_id}");
        }

        session_error($this->upload->display_errors());
        redirect($this->controller);
    }

    // TODO: function ini terlalu panjang dan sebaiknya dipecah menjadi beberapa method
    public function expor($program_id = '')
    {
        if ($this->program_bantuan_model->jml_peserta_program($program_id) == 0) {
            $this->session->success = -1;
            redirect($this->controller);
        }

        // Data Program Bantuan
        $temp                    = $this->session->per_page;
        $this->session->per_page = 1000000000;
        $data                    = $this->program_bantuan_model->get_program(1, $program_id);
        $tbl_program             = $data[0];
        $tbl_peserta             = $data[1];

        //Nama File
        $writer   = WriterEntityFactory::createXLSXWriter();
        $fileName = namafile('program_bantuan_' . $tbl_program['nama']) . '.xlsx';
        $writer->openToBrowser($fileName);

        // Sheet Program
        $writer->getCurrentSheet()->setName('Program');
        $data_program = [
            ['id', $tbl_program['id']],
            ['config_id', identitas('id')],
            ['Nama Program', $tbl_program['nama']],
            ['Sasaran Program', $tbl_program['sasaran']],
            ['Keterangan', $tbl_program['ndesc']],
            ['Asal Dana', $tbl_program['asaldana']],
            ['Rentang Waktu (Awal)', $tbl_program['sdate']],
            ['Rentang Waktu (Akhir)', $tbl_program['edate']],
        ];

        foreach ($data_program as $row) {
            $expor_program = [$row[0], $row[1]];
            $rowFromValues = WriterEntityFactory::createRowFromArray($expor_program);
            $writer->addRow($rowFromValues);
        }

        // Sheet Peserta
        $writer->addNewSheetAndMakeItCurrent()->setName('Peserta');
        $judul_peserta = ['Peserta', 'No. Peserta', 'NIK', 'Nama', 'Tempat Lahir', 'Tanggal Lahir', 'Alamat'];
        $style         = (new StyleBuilder())
            ->setFontBold()
            ->setFontSize(12)
            ->setBackgroundColor(Color::YELLOW)
            ->build();
        $header = WriterEntityFactory::createRowFromArray($judul_peserta, $style);
        $writer->addRow($header);

        //Isi Tabel
        foreach ($tbl_peserta as $row) {
            $peserta = $row['peserta'];
            // Ubah id menjadi kode untuk data kelompok
            // Berkaitan dgn issue #3417
            // Cari data kelompok berdasarkan id
            if ($tbl_program['sasaran'] == 4) {
                $this->load->model('kelompok_model');
                $kelompok = $this->kelompok_model->get_kelompok($peserta);
                $peserta  = $kelompok['kode'];
            }

            $data_peserta = [
                $peserta,
                $row['no_id_kartu'],
                $row['kartu_nik'],
                $row['kartu_nama'],
                $row['kartu_tempat_lahir'],
                $row['kartu_tanggal_lahir'],
                $row['kartu_alamat'],
            ];
            $rowFromValues = WriterEntityFactory::createRowFromArray($data_peserta);
            $writer->addRow($rowFromValues);
        }
        $writer->close();

        $this->session->per_page = $temp;
    }

    /**
     * Unduh kartu peserta berdasarkan kolom program_peserta.kartu_peserta
     *
     * @param int $id_peserta Id peserta program bantuan
     *
     * @return void
     */
    public function unduh_kartu_peserta($id_peserta = 0)
    {
        // Ambil nama berkas dari database
        $kartu_peserta = $this->db
            ->select('kartu_peserta')
            ->where('id', $id_peserta)
            ->where('config_id', identitas('id'))
            ->get('program_peserta')
            ->row()
            ->kartu_peserta;
        ambilBerkas($kartu_peserta, $this->controller . '/detail/' . $id_peserta, null, LOKASI_DOKUMEN);
    }

    // Hapus peserta bantuan yg sudah dihapus
    // TODO: ubah peserta menggunakan id untuk semua sasaran dan gunakan relasi database delete cascade
    public function bersihkan_data()
    {
        $invalid      = [];
        $list_sasaran = array_keys($this->referensi_model->list_ref(SASARAN));

        foreach ($list_sasaran as $sasaran) {
            $invalid = array_merge($invalid, $this->program_bantuan_model->peserta_tidak_valid($sasaran));
        }

        $duplikat     = [];
        $list_program = $this->program_bantuan_model->list_program();

        foreach ($list_program as $program) {
            $duplikat = array_merge($duplikat, $this->program_bantuan_model->peserta_duplikat($program));
        }

        $data['ref_sasaran'] = $this->referensi_model->list_ref(SASARAN);
        $data['invalid']     = $invalid;
        $data['duplikat']    = $duplikat;
        $this->render('program_bantuan/hasil_pembersihan', $data);
    }

    public function bersihkan_data_peserta()
    {
        $this->db
            ->where('config_id', identitas('id'))
            ->where_in('id', $this->input->post('id_cb'))
            ->delete('program_peserta');

        $this->session->success = 1;

        redirect('program_bantuan/bersihkan_data');
    }

    protected function cek_is_date($cells)
    {
        if ($cells->isDate()) {
            $value = $cells->getValue()->format('Y-m-d');
        } else {
            $value = (string) $cells;
        }

        return $value;
    }
}
