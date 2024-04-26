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

use App\Models\Ekspedisi as ModelsEkspedisi;
use App\Models\KlasifikasiSurat;
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class Ekspedisi extends Admin_Controller
{
    public $modul_ini           = 'buku-administrasi-desa';
    public $sub_modul_ini       = 'administrasi-umum';
    private array $uploadConfig = [];

    public function __construct()
    {
        parent::__construct();
        isCan('b');
        $this->load->helper('download');
        $this->load->model('pamong_model');
        $this->uploadConfig = [
            'upload_path'   => LOKASI_ARSIP,
            'allowed_types' => 'gif|jpg|jpeg|png|pdf',
            'max_size'      => max_upload() * 1024,
        ];
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            $data = ModelsEkspedisi::get();

            return datatables()->of($data)
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row): string {
                    $aksi = '';

                    if (can('u')) {
                        $aksi .= '<a href="' . route('buku-umum.ekspedisi.form', ['id' => $row->id]) . '" class="btn btn-warning btn-sm"  title="Ubah Data"><i class="fa fa-edit"></i></a> ';
                    }

                    if ($row->tanda_terima) {
                        $aksi .= '<a href="' . route('buku-umum.ekspedisi.unduh_tanda_terima', ['id' => $row->id]) . '" class="btn btn-purple btn-sm bg-purple" title="Unduh Tanda Terima" target="_blank"><i class="fa fa-download"></i></a> ';
                    }

                    return $aksi . ('<a href="' . route('buku-umum.ekspedisi.bukan_ekspedisi', ['id' => $row->id]) . '" class="btn bg-olive btn-sm" title="Keluarkan dari Buku Ekspedisi"><i class="fa fa-undo"></i></a>');
                })
                ->editColumn('tanggal_pengiriman', static fn ($row): string => tgl_indo($row->tanggal_pengiriman))
                ->rawColumns(['aksi'])
                ->make();
        }

        return show_404();
    }

    public function index(): void
    {
        $data['controller'] = $this->controller;
        $data['list_tahun'] = ModelsEkspedisi::GetTahun();

        $data['main_content'] = 'admin.dokumen.ekspedisi.table';
        $data['subtitle']     = 'Buku Ekspedisi';
        $data['selected_nav'] = 'ekspedisi';

        view('admin.bumindes.umum.main', $data);
    }

    public function form($id): void
    {
        isCan('u');
        $data['klasifikasi'] = KlasifikasiSurat::enabled()->get(['kode', 'nama'])->toArray();

        if ($id) {
            $data['surat_keluar'] = ModelsEkspedisi::findOrFail($id);
            $data['form_action']  = route('buku-umum.ekspedisi.update', ['id' => $id]);
        }

        // Buang unique id pada link nama file
        $berkas                               = explode('__sid__', $data['surat_keluar']['tanda_terima']);
        $namaFile                             = $berkas[0];
        $ekstensiFile                         = explode('.', end($berkas));
        $ekstensiFile                         = end($ekstensiFile);
        $data['surat_keluar']['tanda_terima'] = $namaFile . '.' . $ekstensiFile;

        view('admin.dokumen.ekspedisi.form', $data);
    }

    public function update($id): void
    {
        isCan('u');
        $this->updateProcess($id);
        redirect('ekspedisi/index');
    }

    public function updateProcess($id): void
    {
        // Ambil semua data dari var. global $_POST
        $post = $this->input->post();
        $data = $this->validasi($post);

        $error_msg = '';

        // Ambil nama berkas scan lama dari database
        $berkas_lama        = ModelsEkspedisi::GetTandaTerima($id)->tanda_terima;
        $uploadConfig       = $this->uploadConfig;
        $lokasi_berkas_lama = $uploadConfig['upload_path'] . $berkas_lama;
        $lokasi_berkas_lama = str_replace('/', DIRECTORY_SEPARATOR, FCPATH . $lokasi_berkas_lama);

        // Hapus lampiran lama?
        $hapus_lampiran_lama = $post['gambar_hapus'];

        $upload_data = null;

        // Adakah file baru yang akan diupload?
        $ada_berkas = ! empty($_FILES['tanda_terima']['name']);

        // penerapan transaction karena insert ke 2 tabel
        DB::beginTransaction();
        $ekspedisi = ModelsEkspedisi::findOrFail($id);

        // Ada lampiran file
        if ($ada_berkas) {
            // Tes tidak berisi script PHP
            if (isPHP($_FILES['tanda_terima']['tmp_name'], $_FILES['tanda_terima']['name'])) {
                $error_msg = ' -> Jenis file ini tidak diperbolehkan ';
                redirect_with('error', $error_msg);
            }
            // Cek nama berkas tidak boleh lebih dari 80 karakter (+20 untuk unique id) karena -
            // karakter maksimal yang bisa ditampung kolom surat_keluar.berkas_scan hanya 100 karakter
            if ((strlen($_FILES['tanda_terima']['name']) + 20) >= 100) {
                $this->session->success = -1;
                $error_msg              = ' -> Nama berkas yang coba Anda unggah terlalu panjang, ' .
                    'batas maksimal yang diijinkan adalah 80 karakter';
                redirect_with('error', $error_msg);
            }
            // Inisialisasi library 'upload'
            $this->upload->initialize($uploadConfig);
            // Upload sukses
            if ($this->upload->do_upload('tanda_terima')) {
                $upload_data = $this->upload->data();
                // Hapus berkas dari disk
                // Perhatian: operator 'or' di sini error menggantikan '||'
                $berkas_dihapus = empty($berkas_lama) || (file_exists($lokasi_berkas_lama) && unlink($lokasi_berkas_lama));
                if (! $berkas_dihapus) {
                    $error_msg .= ' -> Gagal menghapus berkas lama';
                }
                // Buat nama file unik untuk nama file upload
                $nama_file_unik = tambahSuffixUniqueKeNamaFile($upload_data['file_name']);
                // Ganti nama file asli dengan nama unik untuk mencegah akses langsung dari browser
                $berkas_direname = rename(
                    $uploadConfig['upload_path'] . $upload_data['file_name'],
                    $uploadConfig['upload_path'] . $nama_file_unik
                );
                $data['tanda_terima'] = $berkas_direname ? $nama_file_unik : $upload_data['file_name'];
                // Update database dengan `tanda_terima` berisi nama unik
                if (! $ekspedisi->update($data)) {
                    $error_msg .= ' -> Gagal memperbarui data di database';
                }
            }
            // Upload gagal
            else {
                $error_msg .= $this->upload->display_errors(null, null);
            }
        }
        // Tidak ada file upload
        else {
            if ($hapus_lampiran_lama) {
                $data['tanda_terima'] = null;
                $hasil                = file_exists($lokasi_berkas_lama) && unlink($lokasi_berkas_lama);
                if (! $hasil) {
                    $error_msg = ' -> Gagal menghapus berkas lama';
                    redirect_with('error', $error_msg);
                }
            }
            if (! $ekspedisi->update($data)) {
                $error_msg = ' -> Gagal memperbarui data di database';
                redirect_with('error', $error_msg);
            }
        }

        DB::commit();

        $this->session->success = null === $this->session->error_msg ? 1 : -1;
    }

    private function validasi($post)
    {
        $data['tanggal_pengiriman'] = tgl_indo_in($post['tanggal_pengiriman']);
        $data['keterangan']         = htmlentities($post['keterangan']);

        return $data;
    }

    // $aksi = cetak/unduh
    public function dialog_cetak($aksi = 'cetak')
    {
        $data['tahun_laporan'] = ModelsEkspedisi::GetTahun();
        $data['aksi']          = $aksi;
        $data['form_action']   = route('buku-umum.ekspedisi.daftar', ['aksi' => $aksi]);

        return view('admin.layouts.components.kades.dialog_cetak', $data);
    }

    public function daftar($aksi = '')
    {
        $data           = $this->data_cetak();
        $data['config'] = $this->header['desa'];
        $data['aksi']   = $aksi;

        //pengaturan data untuk format cetak/ unduh
        $data['isi']       = $data['template'];
        $data['letak_ttd'] = ['1', '1', '3'];

        return view('admin.layouts.components.format_cetak', $data);
    }

    private function data_cetak()
    {
        // Agar tidak terlalu banyak mengubah kode, karena menggunakan view global
        $ttd                    = $this->modal_penandatangan();
        $data['pamong_ttd']     = $this->pamong_model->get_data($ttd['pamong_ttd']->pamong_id);
        $data['pamong_ketahui'] = $this->pamong_model->get_data($ttd['pamong_ketahui']->pamong_id);

        $post          = $this->input->post();
        $data['input'] = $post;
        $data['tahun'] = $post['tahun'];
        $data['main']  = ModelsEkspedisi::when($post['tahun'], static function ($query) use ($post): void {
            $query->whereYear('tanggal_surat', $post['tahun']);
        })->get();
        $data['desa'] = $this->header['desa'];

        $data['file']     = 'Buku Ekspedisi';
        $data['template'] = 'admin.dokumen.ekspedisi.cetak';

        return $data;
    }

    /**
     * Unduh berkas tanda terima berdasarkan kolom surat_keluar.id
     *
     * @param int $id ID surat_keluar
     */
    public function unduh_tanda_terima($id): void
    {
        // Ambil nama berkas dari database
        $berkas = ModelsEkspedisi::GetTandaTerima($id)->tanda_terima;
        ambilBerkas($berkas, 'surat_keluar', '__sid__');
    }

    public function bukan_ekspedisi($id): void
    {
        ModelsEkspedisi::UntukEkspedisi($id, $masuk = 0);
        redirect_with('success', 'Data berhasil dikeluarkan dari Buku Ekspedisi');
    }
}
