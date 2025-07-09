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

defined('BASEPATH') || exit('No direct script access allowed');

use App\Models\Pamong;
use App\Models\Pembangunan;
use App\Models\PembangunanDokumentasi;

class Pembangunan_dokumentasi extends Admin_Controller
{
    public $modul_ini           = 'pembangunan';
    public $aliasController     = 'admin_pembangunan';
    public $kategori_pengaturan = 'Pembangunan';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function dokumentasi($id = null)
    {
        $data['pembangunan'] = Pembangunan::with('wilayah')->find($id) ?? show_404();

        return view('admin.pembangunan.dokumentasi.index', $data);
    }

    public function datatablesDokumentasi($id)
    {
        if ($this->input->is_ajax_request()) {
            return datatables()->of(PembangunanDokumentasi::where('id_pembangunan', $id))
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row): string {
                    $aksi = '';

                    if (can('u')) {
                        $aksi .= '<a href="' . ci_route('pembangunan_dokumentasi.form-dokumentasi', "{$row->id_pembangunan}/{$row->id}") . '" class="btn btn-warning btn-sm"  title="Ubah Data"><i class="fa fa-edit"></i></a> ';
                    }

                    if (can('h')) {
                        $aksi .= '<a href="#" data-href="' . ci_route('pembangunan_dokumentasi.delete-dokumentasi', "{$row->id_pembangunan}/{$row->id}") . '" class="btn bg-maroon btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a> ';
                    }

                    return $aksi;
                })
                ->editColumn('gambar', static function ($row): string {
                    if ($row->gambar) {
                        $row->url_gambar = to_base64(LOKASI_GALERI . $row->gambar);

                        return '<img class="penduduk_kecil" src="' . $row->url_gambar . '" class="penduduk_kecil" alt="Gambar Dokumentasi">';
                    }

                    return '';
                })
                ->editColumn('persentase', static fn ($row): string => $row->persentase . '%')
                ->orderColumn('persentase', static function ($query, $order): void {
                    $query->orderByRaw("CONVERT(persentase, SIGNED) {$order}");
                })
                ->editColumn('created_at', static fn ($row) => $row->created_at)
                ->rawColumns(['ceklist', 'aksi', 'gambar'])
                ->make();
        }

        return show_404();
    }

    public function formDokumentasi($id_suplemen, $id = '')
    {
        isCan('u');

        $data['pembangunan'] = Pembangunan::findOrFail($id_suplemen);
        $data['persentase']  = $this->referensi_model->list_ref(STATUS_PEMBANGUNAN);

        if ($id) {
            $data['action']      = 'Ubah';
            $data['form_action'] = ci_route('pembangunan_dokumentasi.update-dokumentasi', $id);
            $data['main']        = PembangunanDokumentasi::findOrFail($id);
        } else {
            $data['action']      = 'Tambah';
            $data['form_action'] = ci_route('pembangunan_dokumentasi.create-dokumentasi');
            $data['main']        = null;
        }

        return view('admin.pembangunan.dokumentasi.form', $data);
    }

    public function createDokumentasi(): void
    {
        isCan('u');

        $post                   = $this->input->post();
        $data['id_pembangunan'] = $post['id_pembangunan'];
        $data['gambar']         = $this->upload_gambar_pembangunan('gambar', $post['id_pembangunan']);
        $data['persentase']     = $post['persentase'] ?: $post['id_persentase'];
        $data['keterangan']     = $post['keterangan'];
        $data['created_at']     = date('Y-m-d H:i:s');
        $data['updated_at']     = date('Y-m-d H:i:s');

        if (empty($data['gambar'])) {
            unset($data['gambar']);
        }

        unset($data['file_gambar'], $data['old_gambar']);

        if (PembangunanDokumentasi::create($data)) {
            $this->perubahan_anggaran($data['id_pembangunan'], $data['persentase'], bilangan($this->input->post('perubahan_anggaran')));
            redirect_with('success', 'Berhasil Tambah Data', ci_route('pembangunan_dokumentasi.dokumentasi', $post['id_pembangunan']));
        }

        redirect_with('error', 'Gagal Tambah Data', ci_route('pembangunan_dokumentasi.dokumentasi', $post['id_pembangunan']));
    }

    public function updateDokumentasi($id = ''): void
    {
        isCan('u');

        $post                   = $this->input->post();
        $update                 = PembangunanDokumentasi::findOrFail($id);
        $data['id_pembangunan'] = $post['id_pembangunan'];
        $data['gambar']         = $this->upload_gambar_pembangunan('gambar', $post['id_pembangunan'], $update->gambar);
        $data['persentase']     = $post['persentase'] ?: $post['id_persentase'];
        $data['keterangan']     = $post['keterangan'];
        $data['updated_at']     = date('Y-m-d H:i:s');

        if ($update->update($data)) {
            $this->perubahan_anggaran($data['id_pembangunan'], $data['persentase'], bilangan($this->input->post('perubahan_anggaran')));
            redirect_with('success', 'Berhasil Ubah Data', ci_route('pembangunan_dokumentasi.dokumentasi', $post['id_pembangunan']));
        }

        redirect_with('error', 'Gagal Ubah Data', ci_route('pembangunan_dokumentasi.dokumentasi', $post['id_pembangunan']));
    }

    public function deleteDokumentasi($id_pembangunan, $id): void
    {
        isCan('h');

        if (PembangunanDokumentasi::destroy($id)) {
            redirect_with('success', 'Berhasil Hapus Data', ci_route('pembangunan_dokumentasi.dokumentasi', $id_pembangunan));
        }

        redirect_with('error', 'Gagal Hapus Data', ci_route('pembangunan_dokumentasi.dokumentasi', $id_pembangunan));
    }

    public function dialog($id = 0, $aksi = '')
    {
        $data                = $this->modal_penandatangan();
        $data['aksi']        = $aksi;
        $data['form_action'] = site_url("{$this->controller}/daftar/{$id}/{$aksi}");

        return view('admin.layouts.components.ttd_pamong', $data);
    }

    // $aksi = cetak/unduh
    public function daftar($id, $aksi = 'cetak'): void
    {
        $data['pamong_ttd']     = Pamong::selectData()->where(['pamong_id' => $this->input->post('pamong_ttd')])->first()->toArray();
        $data['pamong_ketahui'] = Pamong::selectData()->where(['pamong_id' => $this->input->post('pamong_ketahui')])->first()->toArray();
        $data['pembangunan']    = Pembangunan::with('wilayah')->find($id) ?? show_404();
        $data['dokumentasi']    = PembangunanDokumentasi::where('id_pembangunan', $id)->get();

        if ($aksi == 'unduh') {
            header('Content-type: application/octet-stream');
            header('Content-Disposition: attachment; filename=wilayah_' . date('Y-m-d') . '.doc');
            header('Pragma: no-cache');
            header('Expires: 0');
        }
        view('admin.pembangunan.dokumentasi.cetak', $data);
    }

    private function upload_gambar_pembangunan(string $jenis, $id = null, $old_foto = null)
    {
        // Inisialisasi library 'upload'
        $this->load->library('upload');
        $this->uploadConfig = [
            'upload_path'   => LOKASI_GALERI,
            'allowed_types' => 'jpg|jpeg|png',
            'max_size'      => 1024, // 1 MB
        ];
        $this->upload->initialize($this->uploadConfig);

        $uploadData = null;
        // Adakah berkas yang disertakan?
        $adaBerkas = ! empty($_FILES[$jenis]['name']);
        if (! $adaBerkas) {
            // Jika hapus (ceklis)
            if (isset($_POST['hapus_foto'])) {
                unlink(LOKASI_GALERI . $old_foto);

                return null;
            }

            return $old_foto;
        }

        // Upload sukses
        if ($this->upload->do_upload($jenis)) {
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
        // Upload gagal
        else {
            redirect_with('error', $this->upload->display_errors(null, null), ci_route('pembangunan_dokumentasi.dokumentasi', $id));
        }

        return (empty($uploadData)) ? null : $uploadData['file_name'];
    }

    private function perubahan_anggaran($id_pembangunan = 0, $persentase = 0, $perubahan_anggaran = 0): bool
    {
        if (in_array($persentase, ['100', '100%'])) {
            $update = Pembangunan::findOrFail($id_pembangunan);
            $update->update(['perubahan_anggaran' => $perubahan_anggaran]);
        }

        return true;
    }
}
