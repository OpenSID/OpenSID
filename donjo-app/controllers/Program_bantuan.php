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

use App\Models\BantuanPeserta;
use Box\Spout\Common\Entity\Style\Color;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;

class Program_bantuan extends Admin_Controller
{
    private $_set_page;

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['program_bantuan_model']);
        $this->modul_ini = 6;
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

        $data                           = $this->program_bantuan_model->get_program($p, false);
        $data['penjelasan_pembersihan'] = '
			<p>Fitur ini akan mancari sasaran (penduduk, keluarga, rumah tangga, kelompok) peserta yang sudah terhapus dari database.</p>
			<p>Supaya konsisten, fitur ini akan menghapus peserta tersebut dari program bantuannya.</p>
			<p>Fitur ini juga akan menghapus peserta duplikat dari program bantuannya.</p>';
        $data['list_sasaran'] = unserialize(SASARAN);
        $data['func']         = 'index';
        $data['per_page']     = $this->session->per_page;
        $data['set_page']     = $this->_set_page;
        $data['set_sasaran']  = $this->session->sasaran;
        $this->render('program_bantuan/program', $data);
    }

    public function form($program_id = 0)
    {
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

        $data['form_action'] = site_url('program_bantuan/add_peserta/' . $program_id);

        $this->render('program_bantuan/form', $data);
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

        $data['cari']     = $this->session->cari ?: '';
        $data['program']  = $this->program_bantuan_model->get_program($p, $program_id);
        $data['keyword']  = $this->program_bantuan_model->autocomplete($program_id, $this->input->post('cari'));
        $data['paging']   = $data['program'][0]['paging'];
        $data['p']        = $p;
        $data['func']     = "detail/{$program_id}";
        $data['per_page'] = $this->session->per_page;
        $data['set_page'] = $this->_set_page;

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
        $data                = $this->program_bantuan_model->get_program_peserta_by_id($id);
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
        $this->form_validation->set_rules('status', 'Status', 'required');

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
        $this->form_validation->set_rules('status', 'Status', 'required');

        $data['asaldana'] = unserialize(ASALDANA);
        $data['program']  = $this->program_bantuan_model->get_program(1, $id);
        $data['jml']      = $this->program_bantuan_model->jml_peserta_program($id);

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

            $data['config']          = $this->config_model->get_data();
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

        $this->load->library('upload');

        $config['upload_path']   = LOKASI_DOKUMEN;
        $config['allowed_types'] = 'xls|xlsx|xlsm';
        //$config['max_size']				= max_upload() * 1024;
        $config['file_name'] = namafile('Impor Peserta Program Bantuan');

        $this->upload->initialize($config);

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
            $file   = LOKASI_DOKUMEN . $upload['file_name'];

            $reader = ReaderEntityFactory::createXLSXReader();
            $reader->open($file);

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
                    $ambil_program  = $this->program_bantuan_model->get_program(1, false);
                    $daftar_program = array_column($ambil_program['program'], 'id');

                    $field = ['id', 'nama', 'sasaran', 'ndesc', 'asaldana', 'sdate', 'edate'];

                    foreach ($sheet->getRowIterator() as $row) {
                        $cells = $row->getCells();
                        $title = (string) $cells[0];
                        $value = $this->cek_is_date($cells[1]);

                        // Data terakhir
                        if ($title == '###') {
                            break;
                        }

                        switch (true) {
                            /**
                             * baris 1 == id
                             * id bernilai NULL/Kosong( )/Strip(-)/tdk valid, buat program baru dan tampilkan notifkasi tambah program
                             * id bernilai id dan valid, update data program dan tampilkan notifkasi update program
                             */
                            case $no_baris == 0 && (in_array($value, $daftar_program) && $ganti_program == 1):
                                $program_id = $value;
                                $pesan_program .= 'Data program dengan <b> id = ' . ($value) . '</b> ditemukan, data lama diganti dengan data baru <br>';
                                break;

                            case $no_baris == 0 && (in_array($value, $daftar_program) && $ganti_program != 1):
                                $program_id = $value;
                                $pesan_program .= 'Data program dengan <b> id = ' . ($value) . '</b> ditemukan, data lama tetap digunakan <br>';
                                break;

                            case $no_baris == 0 && ! in_array($value, $daftar_program):
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
                    $pesan_peserta     = '';
                    $ambil_peserta     = $this->program_bantuan_model->get_program(1, $program_id);
                    $sasaran           = $ambil_peserta[0]['sasaran'];
                    $terdaftar_peserta = array_column($ambil_peserta[1], 'peserta');

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
                            'peserta'             => $peserta,
                            'program_id'          => $program_id,
                            'no_id_kartu'         => ((string) $cells[1]) ? $cells[1] : $no_id_kartu,
                            'kartu_nik'           => $nik,
                            'kartu_nama'          => ((string) $cells[3]) ? $cells[3] : $cek_penduduk['nama'],
                            'kartu_tempat_lahir'  => ((string) $cells[4]) ? $cells[4] : $cek_penduduk['tempatlahir'],
                            'kartu_tanggal_lahir' => ($cells[5]) ? $this->cek_is_date($cells[5]) : $cek_penduduk['tanggallahir'],
                            'kartu_alamat'        => ((string) $cells[6]) ? $cells[6] : $cek_penduduk['alamat_wilayah'],
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
            unlink($file);

            $notif = [
                'program' => $pesan_program,
                'gagal'   => $no_gagal,
                'sukses'  => $no_sukses,
                'peserta' => $pesan_peserta,
            ];

            $this->session->set_flashdata('notif', $notif);
            $this->session->per_page = $temp;

            redirect("{$this->controller}/detail/{$program_id}");
        } else {
            $this->session->error_msg = $this->upload->display_errors();
            $this->session->success   = -1;
        }
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
            ->get('program_peserta')->row()->kartu_peserta;
        ambilBerkas($kartu_peserta, $this->controller . '/detail/' . $id_peserta, null, LOKASI_DOKUMEN);
    }

    // Hapus peserta bantuan yg sudah dihapus
    // TODO: ubah peserta menggunakan id untuk semua sasaran dan gunakan relasi database delete cascade
    public function bersihkan_data()
    {
        $this->session->success = 1;
        $invalid                = [];
        $list_sasaran           = array_keys($this->referensi_model->list_ref(SASARAN));

        foreach ($list_sasaran as $sasaran) {
            $invalid = array_merge($invalid, $this->bersihkan_peserta($sasaran));
        }
        if ($this->session->success == -1) {
            redirect('program_bantuan');
        }
        $duplikat     = [];
        $list_program = $this->program_bantuan_model->list_program();

        foreach ($list_program as $program) {
            $duplikat = array_merge($duplikat, $this->bersihkan_duplikat($program));
        }

        if ($this->session->success == -1) {
            redirect('program_bantuan');
        }

        $data['ref_sasaran'] = $this->referensi_model->list_ref(SASARAN);
        $data['invalid']     = $invalid;
        $data['duplikat']    = $duplikat;
        $this->render('program_bantuan/hasil_pembersihan', $data);
    }

    private function bersihkan_duplikat($program)
    {
        $duplikat = $this->db
            ->select('pp.peserta, COUNT(peserta) as jumlah, MAX(pp.id) as id, MAX(p.nama) as nama, MAX(p.sasaran) as sasaran, MAX(pp.kartu_nama) as kartu_nama')
            ->from('program_peserta pp')
            ->join('program p', 'pp.program_id = p.id')
            ->where('pp.program_id', $program['id'])
            ->group_by('pp.peserta')
            ->having('COUNT(peserta) > 1')
            ->get()->result_array();
        if (empty($duplikat)) {
            return [];
        }

        $hasil = $this->db
            ->where_in('peserta', array_column($duplikat, 'peserta'))
            ->where_not_in('id', array_column($duplikat, 'id'))
            ->delete('program_peserta');
        status_sukses($hasil, $gagal_saja = true);

        return $duplikat;
    }

    private function bersihkan_peserta($sasaran)
    {
        switch ($sasaran) {
            case '1':
                $this->db->join('tweb_penduduk s', 's.nik = pp.peserta', 'left');
                break;

            case '2':
                $this->db->join('tweb_keluarga s', 's.no_kk = pp.peserta', 'left');
                break;

            case '3':
                $this->db->join('tweb_rtm s', 's.no_kk = pp.peserta', 'left');
                break;

            case '4':
                $this->db->join('kelompok s', 's.kode = pp.peserta', 'left');
                break;

            default:
                break;
        }
        $invalid = $this->db->select('pp.id, p.nama, p.sasaran, pp.peserta, pp.kartu_nama')
            ->from('program_peserta pp')
            ->join('program p', 'p.id = pp.program_id')
            ->where('p.sasaran', $sasaran)
            ->where('s.id is NULL')
            ->order_by('p.sasaran', 'pp.peserta')
            ->get()->result_array();
        if (empty($id_invalid = array_column($invalid, 'id'))) {
            return [];
        }

        $hasil = $this->db
            ->where_in('id', $id_invalid)
            ->delete('program_peserta');
        status_sukses($hasil, $gagal_saja = true);

        return $invalid;
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
