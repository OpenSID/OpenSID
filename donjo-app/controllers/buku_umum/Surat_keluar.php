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

use App\Models\KlasifikasiSurat;
use App\Models\SuratKeluar;

defined('BASEPATH') || exit('No direct script access allowed');

class Surat_keluar extends Admin_Controller
{
    public $modul_ini     = 'buku-administrasi-desa';
    public $sub_modul_ini = 'administrasi-umum';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
        // Untuk bisa menggunakan helper force_download()
        $this->load->helper('download');
        $this->load->model(['penomoran_surat_model']);
        $this->uploadConfig = [
            'upload_path'   => LOKASI_ARSIP,
            'allowed_types' => 'gif|jpg|jpeg|png|pdf',
            'max_size'      => max_upload() * 1024,
        ];
    }

    public function index()
    {
        $data['selected_nav'] = 'agenda_keluar';
        $data['subtitle']     = 'Buku Agenda - Surat Keluar';
        $data['main_content'] = 'admin.surat_keluar.index';
        $data['tahun']        = SuratKeluar::tahun()->pluck('tahun');

        return view('admin.bumindes.umum.main', $data);
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            return datatables()->of($this->sumberData())
                ->addIndexColumn()
                ->addColumn('ceklist', static function ($row) {
                    if (can('h')) {
                        return '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>';
                    }
                })
                ->addColumn('aksi', static function ($row): string {
                    $aksi = '';

                    if (can('u')) {
                        $aksi .= '<a href="' . ci_route('surat_keluar.form', $row->id) . '" class="btn btn-warning btn-sm"  title="Ubah Data"><i class="fa fa-edit"></i></a> ';
                    }

                    if ($row->berkas_scan) {
                        $aksi .= '<a href="' . ci_route("surat_keluar.berkas.{$row->id}.0") . '" class="btn bg-purple btn-sm" title="Unduh Berkas Surat" target="_blank"><i class="fa fa-download"></i></a> ';
                    }

                    if (can('u')) {
                        if ($row->ekspedisi) {
                            $aksi .= '<a href="' . ci_route('ekspedisi') . '" class="btn bg-info btn-sm" title="Buku Ekspedisi"><i class="fa fa-envelope-open"></i></a> ';
                        } else {
                            $aksi .= '<a href="' . ci_route('surat_keluar.untuk_ekspedisi', $row->id) . '" class="btn bg-blue btn-sm" title="Tambahkan ke Buku Ekspedisi"><i class="fa fa-envelope-open"></i></a> ';
                        }
                    }

                    if (can('h')) {
                        $aksi .= '<a href="#" data-href="' . ci_route('surat_keluar.delete', $row->id) . '" class="btn bg-maroon btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a> ';
                    }

                    return $aksi . ('<a href="' . ci_route("surat_keluar.berkas.{$row->id}.1") . '" target="_blank" class="btn btn-info btn-sm"  title="Lihat Berkas Surat"><i class="fa fa-eye"></i></a> ');
                })
                ->editColumn('tanggal_surat', static fn ($row) => tgl_indo_out($row->tanggal_surat))
                ->rawColumns(['ceklist', 'aksi'])
                ->make();
        }

        return show_404();
    }

    private function sumberData()
    {
        $tahun = $this->input->get('tahun') ?? null;

        return SuratKeluar::when($tahun, static fn ($q) => $q->whereYear('tanggal_surat', $tahun));
    }

    public function form($id = '')
    {
        isCan('u');
        $data['tujuan']      = SuratKeluar::autocomplete();
        $data['klasifikasi'] = KlasifikasiSurat::select(['kode', 'nama'])->get();

        if ($id) {
            $data['action']       = 'Ubah';
            $data['surat_keluar'] = SuratKeluar::findOrFail($id);
            $data['form_action']  = site_url("surat_keluar/update/{$id}");
        } else {
            $data['action']                     = 'Tambah';
            $last_surat                         = $this->penomoran_surat_model->get_surat_terakhir('surat_keluar');
            $data['surat_keluar']['nomor_urut'] = $last_surat['no_surat'] + 1;
            $data['form_action']                = site_url('surat_keluar/insert');
        }

        // Buang unique id pada link nama file
        $berkas                              = explode('__sid__', $data['surat_keluar']['berkas_scan']);
        $namaFile                            = $berkas[0];
        $ekstensiFile                        = explode('.', end($berkas));
        $ekstensiFile                        = end($ekstensiFile);
        $data['surat_keluar']['berkas_scan'] = $namaFile . '.' . $ekstensiFile;

        return view('admin.surat_keluar.form', $data);
    }

    public function insert(): void
    {
        isCan('u');

        // Ambil semua data dari var. global $_POST
        $data = $this->input->post(null);

        unset($data['url_remote'], $data['nomor_urut_lama']);

        $this->validasi($data);

        // Adakah lampiran yang disertakan?
        $adaLampiran = ! empty($_FILES['satuan']['name']);

        // Cek nama berkas user boleh lebih dari 80 karakter (+20 untuk unique id) karena -
        // karakter maksimal yang bisa ditampung kolom surat_keluar.berkas_scan hanya 100 karakter
        if ($adaLampiran && ((strlen($_FILES['satuan']['name']) + 20) >= 100)) {
            redirect_with('error', ' -> Nama berkas yang coba Anda unggah terlalu panjang, batas maksimal yang diijinkan adalah 80 karakter');
        }

        $uploadData = null;
        // Ada lampiran file
        if ($adaLampiran) {
            // Tes tidak berisi script PHP
            if (isPHP($_FILES['satuan']['tmp_name'], $_FILES['satuan']['name'])) {
                redirect_with('error', ' -> Jenis file ini tidak diperbolehkan');
            }
            // Inisialisasi library 'upload'
            $this->upload->initialize($this->uploadConfig);
            // Upload sukses
            if ($this->upload->do_upload('satuan')) {
                $uploadData = $this->upload->data();
                // Buat nama file unik agar url file susah ditebak dari browser
                $namaFileUnik = tambahSuffixUniqueKeNamaFile($uploadData['file_name']);
                // Ganti nama file asli dengan nama unik untuk mencegah akses langsung dari browser
                $fileRenamed = rename(
                    $this->uploadConfig['upload_path'] . $uploadData['file_name'],
                    $this->uploadConfig['upload_path'] . $namaFileUnik
                );
                // Ganti nama di array upload jika file berhasil di-rename --
                // jika rename gagal, fallback ke nama asli
                $uploadData['file_name'] = $fileRenamed ? $namaFileUnik : $uploadData['file_name'];
            }
        }
        // Berkas lampiran
        $data['berkas_scan'] = $adaLampiran && null !== $uploadData ? $uploadData['file_name'] : null;

        if (SuratKeluar::create($data)) {
            redirect_with('success', 'Berhasil Tambah Data');
        }

        redirect_with('error', 'Gagal Tambah Data');
    }

    public function update($idSuratMasuk): void
    {
        isCan('u');
        // Ambil semua data dari var. global $_POST
        $data = $this->input->post(null);
        unset($data['url_remote'], $data['nomor_urut_lama']);

        $this->validasi($data);

        // Ambil nama berkas scan lama dari database
        $berkasLama = SuratKeluar::findOrFail($idSuratMasuk)->berkas_scan;

        // Lokasi berkas scan lama (absolut)
        $lokasiBerkasLama = $this->uploadConfig['upload_path'] . $berkasLama;
        $lokasiBerkasLama = str_replace('/', DIRECTORY_SEPARATOR, FCPATH . $lokasiBerkasLama);

        // Hapus lampiran lama?
        $hapusLampiranLama = $data['gambar_hapus'];
        unset($data['gambar_hapus']);

        $uploadData = null;

        // Adakah file baru yang akan diupload?
        $adaLampiran = ! empty($_FILES['satuan']['name']);

        // Ada lampiran file
        if ($adaLampiran) {
            // Tes tidak berisi script PHP
            if (isPHP($_FILES['satuan']['tmp_name'], $_FILES['satuan']['name'])) {
                redirect_with('error', ' -> Jenis file ini tidak diperbolehkan ');
            }
            // Cek nama berkas tidak boleh lebih dari 80 karakter (+20 untuk unique id) karena -
            // karakter maksimal yang bisa ditampung kolom surat_keluar.berkas_scan hanya 100 karakter
            if ((strlen($_FILES['satuan']['name']) + 20) >= 100) {
                redirect_with('error', ' -> Nama berkas yang coba Anda unggah terlalu panjang, batas maksimal yang diijinkan adalah 80 karakter');
            }
            // Inisialisasi library 'upload'
            $this->upload->initialize($this->uploadConfig);
            // Upload sukses
            if ($this->upload->do_upload('satuan')) {
                $uploadData = $this->upload->data();
                // Buat nama file unik untuk nama file upload
                $namaFileUnik = tambahSuffixUniqueKeNamaFile($uploadData['file_name']);
                // Ganti nama file asli dengan nama unik untuk mencegah akses langsung dari browser
                $uploadedFileRenamed = rename(
                    $this->uploadConfig['upload_path'] . $uploadData['file_name'],
                    $this->uploadConfig['upload_path'] . $namaFileUnik
                );

                $uploadData['file_name'] = ($uploadedFileRenamed === false) ?: $namaFileUnik;

                $data['berkas_scan'] = $uploadData['file_name'];
                // Update database dengan `berkas_scan` berisi nama unik

                $update = SuratKeluar::findOrFail($idSuratMasuk);

                if ($update->update($data)) {
                    redirect_with('success', 'Berhasil Ubah Data');
                }

                redirect_with('error', 'Gagal Ubah Data');
            }
        }
        // Tidak ada file upload
        else {
            unset($data['berkas_scan']);
            if ($hapusLampiranLama) {
                $data['berkas_scan'] = null;
                $adaBerkasLamaDiDisk = file_exists($lokasiBerkasLama);
                $oldFileRemoved      = $adaBerkasLamaDiDisk && unlink($lokasiBerkasLama);
                ($oldFileRemoved) ? null : redirect_with('error', ' -> Gagal menghapus berkas lama');
            }

            $update = SuratKeluar::findOrFail($idSuratMasuk);

            if ($update->update($data)) {
                redirect_with('success', 'Berhasil Ubah Data');
            }

            redirect_with('error', 'Gagal Ubah Data');
        }
    }

    private function validasi(array &$data): void
    {
        // Normalkan tanggal
        $data['tanggal_surat'] = tgl_indo_in($data['tanggal_surat']);
        // Bersihkan data
        $data['nomor_surat'] = nomor_surat_keputusan(strip_tags($data['nomor_surat']));
        $data['tujuan']      = strip_tags($data['tujuan']);
        $data['isi_singkat'] = strip_tags($data['isi_singkat']);
    }

    public function delete($id): void
    {
        isCan('h');

        if (SuratKeluar::destroy($id)) {
            redirect_with('success', 'Berhasil Hapus Data');
        }

        redirect_with('error', 'Gagal Hapus Data');
    }

    public function delete_all(): void
    {
        isCan('h');

        if (SuratKeluar::destroy($this->request['id_cb'])) {
            redirect_with('success', 'Berhasil Hapus Data');
        }

        redirect_with('error', 'Gagal Hapus Data');
    }

    public function dialog($aksi = 'cetak')
    {
        $data['aksi']       = $aksi;
        $data['tahun']      = SuratKeluar::tahun()->pluck('tahun');
        $data['formAction'] = ci_route('surat_keluar.cetak', $aksi);

        return view('admin.surat_keluar.dialog', $data);
    }

    public function cetak($aksi = '')
    {
        $query          = $this->sumberData();
        $data           = $this->modal_penandatangan();
        $data['aksi']   = $aksi;
        $data['main']   = $query->get()->toArray();
        $data['config'] = $this->header['desa'];
        $data['tahun']  = $this->input->post('tahun');
        if ($data['tahun']) {
            $data['main'] = $query->whereYear('tanggal_surat', $data['tahun'])->get()->toArray();
        }
        $data['isi']       = 'admin.surat_keluar.cetak';
        $data['letak_ttd'] = ['1', '1', '23'];

        return view('admin.layouts.components.format_cetak', $data);
    }

    /**
     * Unduh berkas scan berdasarkan kolom surat_keluar.id
     *
     * @param int $idSuratKeluar Id berkas scan pada koloam surat_keluar.id
     * @param int $tipe
     */
    public function berkas($idSuratKeluar = 0, $tipe = 0): void
    {
        $berkas = SuratKeluar::find($idSuratKeluar)->berkas_scan;
        ambilBerkas($berkas, 'surat_keluar', '__sid__', LOKASI_ARSIP, $tipe == 1);
    }

    public function nomor_surat_duplikat(): void
    {
        if ($this->input->post('nomor_urut') == $this->input->post('nomor_urut_lama')) {
            $hasil = false;
        } else {
            $hasil = $this->penomoran_surat_model->nomor_surat_duplikat('surat_keluar', $this->input->post('nomor_urut'));
        }
        echo $hasil ? 'false' : 'true';
    }

    public function untuk_ekspedisi($id): void
    {
        SuratKeluar::find($id)->update(['ekspedisi' => 1]);

        redirect_with('success', 'Berhasil Masuk ke Ekspedisi');
    }
}
