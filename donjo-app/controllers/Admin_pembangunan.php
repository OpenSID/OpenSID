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

use App\Enums\SatuanWaktuEnum;
use App\Enums\StatusEnum;
use App\Models\Area;
use App\Models\Garis;
use App\Models\Lokasi;
use App\Models\Pembangunan;
use App\Models\Wilayah;

defined('BASEPATH') || exit('No direct script access allowed');

class Admin_pembangunan extends Admin_Controller
{
    public $modul_ini = 'pembangunan';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function index()
    {
        $data['tahun'] = Pembangunan::distinct()->get('tahun_anggaran');

        return view('admin.pembangunan.index', $data);
    }

    public function datatables()
    {
        $tahun = $this->input->get('tahun') ?? null;

        if ($this->input->is_ajax_request()) {
            return datatables()->of(Pembangunan::with(['pembangunanDokumentasi', 'wilayah'])->when($tahun, static fn ($q) => $q->where('tahun_anggaran', $tahun)))
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row): string {
                    $aksi = '';

                    if (can('u')) {
                        $aksi .= '<a href="' . ci_route('admin_pembangunan.form', $row->id) . '" class="btn btn-warning btn-sm"  title="Ubah Data"><i class="fa fa-edit"></i></a> ';
                        $aksi .= '<a href="' . ci_route('admin_pembangunan.maps') . '/' . $row->id . '" class="btn bg-olive btn-sm" title="Lokasi Pembangunan"><i class="fa fa-map"></i></a> ';
                    }

                    $aksi .= '<a href="' . ci_route('pembangunan_dokumentasi.dokumentasi') . '/' . $row->id . '" class="btn bg-purple btn-sm" title="Rincian Dokumentasi Kegiatan"><i class="fa fa-list-ol"></i></a> ';

                    if (can('u')) {
                        if ($row->status == StatusEnum::YA) {
                            $aksi .= '<a href="' . ci_route('admin_pembangunan.lock') . '/' . $row->id . '" class="btn bg-navy btn-sm" title="Nonaktifkan"><i class="fa fa-unlock"></i></a> ';
                        } else {
                            $aksi .= '<a href="' . ci_route('admin_pembangunan.lock') . '/' . $row->id . '" class="btn bg-navy btn-sm" title="Aktifkan"><i class="fa fa-lock"></i></a> ';
                        }
                    }

                    if (can('h')) {
                        $aksi .= '<a href="#" data-href="' . ci_route('admin_pembangunan.delete', $row->id) . '" class="btn bg-maroon btn-sm"  title="Hapus Data" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash"></i></a> ';
                    }

                    return $aksi . ('<a href="' . ci_route('pembangunan') . '/' . $row->slug . '" target="_blank" class="btn bg-blue btn-sm" title="Lihat Summary"><i class="fa fa-eye"></i></a> ');
                })
                ->editColumn('foto', static function ($row): string {
                    if ($row->foto) {
                        $row->url_foto = to_base64(LOKASI_GALERI . $row->foto);

                        return '<img class="penduduk_kecil" src="' . $row->url_foto . '" class="penduduk_kecil text-center" alt="Gambar Dokumentasi">';
                    }

                    return '';
                })
                ->editColumn('persentase', static fn ($row) => $row->max_persentase)
                ->editColumn('alamat', static fn ($row) => $row->wilayah->dusun ?? 'Lokasi tidak diketahui')
                ->editColumn('anggaran', static fn ($row) => $row->perubahan_anggaran > 0 ? $row->perubahan_anggaran : $row->anggaran)
                ->rawColumns(['ceklist', 'aksi', 'foto'])
                ->make();
        }

        return show_404();
    }

    public function form($id = '')
    {
        isCan('u');

        if ($id) {
            $data['action']      = 'Ubah';
            $data['form_action'] = ci_route('admin_pembangunan.update', $id);
            $data['main']        = Pembangunan::findOrFail($id);
        } else {
            $data['action']      = 'Tambah';
            $data['form_action'] = ci_route('admin_pembangunan.create');
            $data['main']        = null;
        }

        $data['list_lokasi']  = Wilayah::rt()->orderBy('dusun')->get()->toArray();
        $data['sumber_dana']  = $this->referensi_model->list_ref(SUMBER_DANA);
        $data['satuan_waktu'] = SatuanWaktuEnum::all();

        return view('admin.pembangunan.form', $data);
    }

    public function create(): void
    {
        isCan('u');
        $post               = $this->input->post();
        $data               = $this->validasi($post);
        $data['created_at'] = date('Y-m-d H:i:s');

        if (Pembangunan::create($data)) {
            redirect_with('success', 'Berhasil Tambah Data');
        }

        redirect_with('error', 'Gagal Tambah Data');
    }

    public function update($id = ''): void
    {
        isCan('u');

        $update = Pembangunan::findOrFail($id);
        $post   = $this->input->post();
        $data   = $this->validasi($post, $id, $update->foto);

        if ($update->update($data)) {
            redirect_with('success', 'Berhasil Ubah Data');
        }

        redirect_with('error', 'Gagal Ubah Data');
    }

    public function delete($id): void
    {
        isCan('h');

        if (Pembangunan::destroy($id)) {
            redirect_with('success', 'Berhasil Hapus Data');
        }

        redirect_with('error', 'Gagal Hapus Data');
    }

    private function validasi($post, $id = null, $old_foto = null)
    {
        return [
            'sumber_dana'             => bersihkan_xss($post['sumber_dana']),
            'judul'                   => judul($post['judul']),
            'slug'                    => unique_slug('pembangunan', $post['judul'], $id),
            'volume'                  => bersihkan_xss($post['volume']),
            'waktu'                   => bilangan($post['waktu']),
            'satuan_waktu'            => bilangan($post['satuan_waktu']),
            'tahun_anggaran'          => bilangan($post['tahun_anggaran']),
            'pelaksana_kegiatan'      => bersihkan_xss($post['pelaksana_kegiatan']),
            'id_lokasi'               => $post['lokasi'] ? null : bilangan($post['id_lokasi']),
            'lokasi'                  => $post['id_lokasi'] ? null : $this->security->xss_clean(bersihkan_xss($post['lokasi'])),
            'keterangan'              => $this->security->xss_clean(bersihkan_xss($post['keterangan'])),
            'foto'                    => $this->upload_gambar_pembangunan('foto', $old_foto),
            'anggaran'                => bilangan($post['anggaran']),
            'sumber_biaya_pemerintah' => bilangan($post['sumber_biaya_pemerintah']),
            'sumber_biaya_provinsi'   => bilangan($post['sumber_biaya_provinsi']),
            'sumber_biaya_kab_kota'   => bilangan($post['sumber_biaya_kab_kota']),
            'sumber_biaya_swadaya'    => bilangan($post['sumber_biaya_swadaya']),
            'sumber_biaya_jumlah'     => bilangan($post['sumber_biaya_pemerintah']) + bilangan($post['sumber_biaya_provinsi']) + bilangan($post['sumber_biaya_kab_kota']) + bilangan($post['sumber_biaya_swadaya']),
            'manfaat'                 => $this->security->xss_clean(bersihkan_xss($post['manfaat'])),
            'sifat_proyek'            => bersihkan_xss($post['sifat_proyek']),
            'updated_at'              => date('Y-m-d H:i:s'),
        ];
    }

    private function upload_gambar_pembangunan(string $jenis, $old_foto = '')
    {
        // Inisialisasi library 'upload'
        $this->load->library('MY_Upload', null, 'upload');
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
            redirect_with('error', $this->upload->display_errors(null, null));
        }

        return (empty($uploadData)) ? null : $uploadData['file_name'];
    }

    public function maps($id): void
    {
        isCan('u');

        $data['lokasi']                 = Pembangunan::findOrFail($id)->toArray();
        $data['desa']                   = $this->header['desa'];
        $data['wil_atas']               = $this->header['desa'];
        $data['dusun_gis']              = Wilayah::dusun()->get()->toArray();
        $data['rw_gis']                 = Wilayah::rw()->get()->toArray();
        $data['rt_gis']                 = Wilayah::rt()->get()->toArray();
        $data['all_lokasi']             = Lokasi::activeLocationMap();
        $data['all_garis']              = Garis::activeGarisMap();
        $data['all_area']               = Area::activeAreaMap();
        $data['all_lokasi_pembangunan'] = Pembangunan::activePembangunanMap();

        $data['form_action'] = ci_route('admin_pembangunan.update-maps', $id);

        view('admin.pembangunan.maps', $data);
    }

    public function updateMaps($id): void
    {
        isCan('u');

        try {
            $data = $this->input->post();
            if (! empty($data['lat']) && ! empty($data['lng'])) {
                Pembangunan::whereId($id)->update($data);
                redirect_with('success', 'Lokasi berhasil disimpan');
            } else {
                redirect_with('error', 'Titik koordinat lokasi harus diisi');
            }
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Lokasi gagal disimpan');
        }
    }

    public function lock($id = 0): void
    {
        isCan('u');

        if ($this->session->error_msg) {
            redirect_with('error', $this->session->error_msg);
        }

        if (Pembangunan::gantiStatus($id, 'status')) {
            redirect_with('success', 'Berhasil Ubah Status');
        }

        redirect_with('error', 'Gagal Ubah Status');
    }
}
