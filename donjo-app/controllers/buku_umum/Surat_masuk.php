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

use App\Models\DisposisiSuratmasuk;
use App\Models\KlasifikasiSurat;
use App\Models\Pamong;
use App\Models\RefJabatan;
use App\Models\SuratMasuk;

defined('BASEPATH') || exit('No direct script access allowed');

class Surat_masuk extends Admin_Controller
{
    public $modul_ini     = 'buku-administrasi-desa';
    public $sub_modul_ini = 'administrasi-umum';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
        // Untuk bisa menggunakan helper force_download()
        $this->load->helper('download');
        $this->load->model('penomoran_surat_model');
        $this->uploadConfig = [
            'upload_path'   => LOKASI_ARSIP,
            'allowed_types' => 'gif|jpg|jpeg|png|pdf',
            'max_size'      => max_upload() * 1024,
        ];
        $this->tab_ini = 2;
    }

    public function index()
    {
        $data['selected_nav'] = 'agenda_masuk';
        $data['subtitle']     = 'Buku Agenda - Surat Masuk';
        $data['main_content'] = 'admin.surat_masuk.index';
        $data['tahun']        = SuratMasuk::tahun()->pluck('tahun');

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
                        $aksi .= '<a href="' . ci_route('surat_masuk.form', $row->id) . '" class="btn btn-warning btn-sm"  title="Ubah Data"><i class="fa fa-edit"></i></a> ';
                    }

                    if (can('u')) {
                        $aksi .= '<a href="' . ci_route('surat_masuk.dialog_disposisi', $row->id) . '" class="btn bg-navy btn-sm" title="Cetak Lembar Disposisi Surat" data-remote="false" data-toggle="modal" data-target="#modalBox" data-title="Cetak Lembar Disposisi Surat"><i class="fa fa-file-archive-o"></i></a> ';
                    }

                    if ($row->berkas_scan) {
                        $aksi .= '<a href="' . ci_route("surat_masuk.berkas.{$row->id}.0") . '" class="btn bg-purple btn-sm" title="Unduh Berkas Surat" target="_blank"><i class="fa fa-download"></i></a> ';
                    }

                    if (can('h')) {
                        $aksi .= '<a href="#" data-href="' . ci_route('surat_masuk.delete', $row->id) . '" class="btn bg-maroon btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a> ';
                    }

                    return $aksi . ('<a href="' . ci_route("surat_masuk.berkas.{$row->id}.1") . '" target="_blank" class="btn btn-info btn-sm"  title="Lihat Berkas Surat"><i class="fa fa-eye"></i></a> ');
                })
                ->editColumn('tanggal_penerimaan', static fn ($row) => tgl_indo_out($row->tanggal_penerimaan))
                ->editColumn('tanggal_surat', static fn ($row) => tgl_indo_out($row->tanggal_surat))
                ->rawColumns(['ceklist', 'aksi'])
                ->make();
        }

        return show_404();
    }

    private function sumberData()
    {
        $tahun = $this->input->get('tahun') ?? null;

        return SuratMasuk::when($tahun, static fn ($q) => $q->whereYear('tanggal_surat', $tahun));
    }

    public function form($id = '')
    {
        isCan('u');
        $data['pengirim']    = SuratMasuk::autocomplete();
        $data['klasifikasi'] = KlasifikasiSurat::select(['kode', 'nama'])->get();

        if ($id) {
            $data['action']                = 'Ubah';
            $data['surat_masuk']           = SuratMasuk::findOrFail($id);
            $data['form_action']           = site_url("surat_masuk/update/{$id}");
            $data['disposisi_surat_masuk'] = DisposisiSuratmasuk::where('id_surat_masuk', $id)->pluck('disposisi_ke')->toArray();
        } else {
            $data['action']                    = 'Tambah';
            $last_surat                        = $this->penomoran_surat_model->get_surat_terakhir('surat_masuk');
            $data['surat_masuk']['nomor_urut'] = $last_surat['no_surat'] + 1;
            $data['form_action']               = site_url('surat_masuk/insert');
            $data['disposisi_surat_masuk']     = null;
        }

        $data['ref_disposisi'] = $this->ref_disposisi();

        // Buang unique id pada link nama file
        $berkas                             = explode('__sid__', (string) $data['surat_masuk']['berkas_scan']);
        $namaFile                           = $berkas[0];
        $ekstensiFile                       = explode('.', end($berkas));
        $ekstensiFile                       = end($ekstensiFile);
        $data['surat_masuk']['berkas_scan'] = $namaFile . '.' . $ekstensiFile;

        return view('admin.surat_masuk.form', $data);
    }

    private function ref_disposisi()
    {
        $non_aktif = RefJabatan::nonAktif()->pluck('id', 'id');

        return RefJabatan::with('pamongs')->urut()->latest()->pluck('nama', 'id')->except(kades()->id)->except($non_aktif)->toArray();
    }

    public function insert(): void
    {
        isCan('u');

        // Ambil semua data dari var. global $_POST
        $data = $this->input->post(null);

        unset($data['url_remote'], $data['nomor_urut_lama']);

        // ambil disposisi ke variabel lain karena
        // tidak lagi digunakan pada tabel surat masuk
        $jabatan = $data['disposisi_kepada'];

        // hapus data disposisi dari post
        // surat masuk
        unset($data['disposisi_kepada']);
        $this->validasi($data);

        // Adakah lampiran yang disertakan?
        $adaLampiran = ! empty($_FILES['satuan']['name']);

        // Cek nama berkas user boleh lebih dari 80 karakter (+20 untuk unique id) karena -
        // karakter maksimal yang bisa ditampung kolom surat_keluar.berkas_scan hanya 100 karakter
        if ($adaLampiran && ((strlen((string) $_FILES['satuan']['name']) + 20) >= 100)) {
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

        try {
            $surat = SuratMasuk::create($data);
            if ($jabatan) {
                $this->disposisi_surat_masuk($surat->id, $jabatan);
            }
            redirect_with('success', 'Berhasil Tambah Data');
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Gagal Tambah Data');
        }
    }

    public function disposisi_surat_masuk($id_surat_masuk, array $jabatan): void
    {
        DisposisiSuratmasuk::destroy($id_surat_masuk);

        foreach ($jabatan as $value) {
            DisposisiSuratmasuk::create([
                'id_surat_masuk' => $id_surat_masuk,
                'id_desa_pamong' => Pamong::where('jabatan_id', $value)->first()->pamong_id,
                'disposisi_ke'   => $value,
            ]);
        }
    }

    public function update($idSuratMasuk): void
    {
        isCan('u');
        // Ambil semua data dari var. global $_POST
        $data = $this->input->post(null);
        unset($data['url_remote'], $data['nomor_urut_lama']);

        // ambil disposisi ke variabel lain karena
        // tidak lagi digunakan pada tabel surat masuk
        $jabatan = $data['disposisi_kepada'];
        // hapus data disposisi dari post
        // surat masuk
        unset($data['disposisi_kepada']);

        $this->validasi($data);

        // Ambil nama berkas scan lama dari database
        $berkasLama = SuratMasuk::findOrFail($idSuratMasuk)->berkas_scan;

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
            if ((strlen((string) $_FILES['satuan']['name']) + 20) >= 100) {
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

                $update = SuratMasuk::findOrFail($idSuratMasuk);

                if ($jabatan) {
                    $this->disposisi_surat_masuk($idSuratMasuk, $jabatan);
                }

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

            $update = SuratMasuk::findOrFail($idSuratMasuk);

            if ($jabatan) {
                $this->disposisi_surat_masuk($idSuratMasuk, $jabatan);
            }

            if ($update->update($data)) {
                redirect_with('success', 'Berhasil Ubah Data');
            }

            redirect_with('error', 'Gagal Ubah Data');
        }
    }

    private function validasi(array &$data): void
    {
        // Normalkan tanggal
        $data['tanggal_penerimaan'] = tgl_indo_in($data['tanggal_penerimaan']);
        $data['tanggal_surat']      = tgl_indo_in($data['tanggal_surat']);
        // Bersihkan data
        $data['nomor_surat']   = strip_tags((string) $data['nomor_surat']);
        $data['pengirim']      = alfanumerik_spasi($data['pengirim']);
        $data['isi_singkat']   = strip_tags((string) $data['isi_singkat']);
        $data['isi_disposisi'] = strip_tags((string) $data['isi_disposisi']);
    }

    public function delete($id = ''): void
    {
        isCan('h');

        if (SuratMasuk::destroy($id)) {
            redirect_with('success', 'Berhasil Hapus Data');
        }

        redirect_with('error', 'Gagal Hapus Data');
    }

    public function delete_all(): void
    {
        isCan('h');

        if (SuratMasuk::destroy($this->request['id_cb'])) {
            redirect_with('success', 'Berhasil Hapus Data');
        }

        redirect_with('error', 'Gagal Hapus Data');
    }

    public function dialog_disposisi($id)
    {
        $data                = $this->modal_penandatangan();
        $data['aksi']        = 'cetak';
        $data['form_action'] = site_url("surat_masuk/disposisi/{$id}");

        return view('admin.layouts.components.ttd_pamong', $data);
    }

    public function disposisi($id)
    {
        $disposisi = [];
        collect($this->ref_disposisi())->each(static function ($item, $key) use (&$disposisi): void {
            $disposisi[] = ['id' => $key, 'nama' => $item];
        })->toArray();
        $data['input']                 = $_POST;
        $data['pamong_ttd']            = Pamong::selectData()->where(['pamong_id' => $this->input->post('pamong_ttd')])->first()->toArray();
        $data['pamong_ketahui']        = Pamong::selectData()->where(['pamong_id' => $this->input->post('pamong_ketahui')])->first()->toArray();
        $data['ref_disposisi']         = $disposisi;
        $data['disposisi_surat_masuk'] = DisposisiSuratmasuk::where('id_surat_masuk', $id)->pluck('disposisi_ke')->toArray();
        $data['surat']                 = SuratMasuk::findOrFail($id)->toArray();

        return view('admin.surat_masuk.disposisi', $data);
    }

    public function dialog($aksi = 'cetak')
    {
        $data['aksi']       = $aksi;
        $data['tahun']      = SuratMasuk::tahun()->pluck('tahun');
        $data['formAction'] = ci_route('surat_masuk.cetak', $aksi);

        return view('admin.surat_masuk.dialog', $data);
    }

    public function cetak($aksi = '')
    {
        $query         = $this->sumberData();
        $data          = $this->modal_penandatangan();
        $data['aksi']  = $aksi;
        $data['main']  = $query->get()->toArray();
        $data['tahun'] = $this->input->post('tahun');
        if ($data['tahun']) {
            $data['main'] = $query->whereYear('tanggal_surat', $data['tahun'])->get()->toArray();
        }
        $data['file']      = 'Surat Masuk';
        $data['isi']       = 'admin.surat_masuk.cetak';
        $data['letak_ttd'] = ['1', '1', '2'];

        return view('admin.layouts.components.format_cetak', $data);
    }

    /**
     * Unduh berkas scan berdasarkan kolom surat_masuk.id
     *
     * @param int $idSuratMasuk Id berkas scan pada koloam surat_masuk.id
     * @param int $tipe
     */
    public function berkas($idSuratMasuk = 0, $tipe = 0): void
    {
        // Ambil nama berkas dari database
        $berkas = SuratMasuk::find($idSuratMasuk)->berkas_scan;
        ambilBerkas($berkas, 'surat_masuk', '__sid__', LOKASI_ARSIP, $tipe == 1);
    }

    public function nomor_surat_duplikat(): void
    {
        if ($_POST['nomor_urut'] == $_POST['nomor_urut_lama']) {
            $hasil = false;
        } else {
            $hasil = $this->penomoran_surat_model->nomor_surat_duplikat('surat_masuk', $_POST['nomor_urut']);
        }
        echo $hasil ? 'false' : 'true';
    }
}
