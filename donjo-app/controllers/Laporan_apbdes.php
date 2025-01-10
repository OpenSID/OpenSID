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

use App\Models\LaporanSinkronisasi;
use App\Traits\Upload;

defined('BASEPATH') || exit('No direct script access allowed');

class Laporan_apbdes extends Admin_Controller
{
    use Upload;

    public $modul_ini     = 'keuangan';
    public $sub_modul_ini = 'laporan-apbdes';
    protected $tipe       = 'laporan_apbdes';
    protected $routePath  = 'laporan_apbdes';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function index(): void
    {
        view('admin.opendk.index', [
            'judul'     => ($this->tipe == 'laporan_apbdes') ? 'Laporan APBDes' : 'Laporan Penduduk',
            'kolom'     => ($this->tipe == 'laporan_apbdes') ? 'Semester' : 'Bulan',
            'tahun'     => $this->getTahun(),
            'routePath' => $this->routePath,
        ]);
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            $routePath = $this->routePath;

            return datatables()->of(LaporanSinkronisasi::whereTipe($this->tipe))
                ->addColumn('ceklist', static function ($row) {
                    if (can('h')) {
                        return '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>';
                    }
                })
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row) use ($routePath): string {
                    $aksi = '';

                    if (can('u')) {
                        $aksi .= '<a href="' . ci_route($routePath . '.form', $row->id) . '" class="btn btn-warning btn-sm"  title="Ubah Data" data-target="#modalBox" data-remote="false" data-toggle="modal" data-backdrop="false" data-keyboard="false" data-title="Ubah Laporan Penduduk" ><i class="fa fa-edit"></i></a> ';
                    }

                    return $aksi . ('<a href="' . ci_route($routePath . '.unduh', $row->id) . '" class="btn bg-purple btn-sm"  title="Unduh"><i class="fa fa-download"></i></a>');
                })
                ->editColumn('updated_at', static fn ($q) => $q->updated_at->format('Y-m-d H:i:s'))
                ->rawColumns(['ceklist', 'aksi'])
                ->make();
        }

        return show_404();
    }

    public function form(?int $id = 0): void
    {
        isCan('u');

        if ($id) {
            $data['main']        = LaporanSinkronisasi::find($id) ?? show_404();
            $data['form_action'] = ci_route($this->routePath . '.update', $id);
        } else {
            $data['main']        = null;
            $data['form_action'] = ci_route($this->routePath . '.insert');
        }

        $data['tahun'] = $this->getTahun();
        $this->tipe == 'laporan_apbdes' ? view('admin.opendk.form_laporan_apbdes', $data) : view('admin.opendk.form_laporan_penduduk', $data);
    }

    public function insert(): void
    {
        isCan('u');
        $data         = $this->request;
        $data['tipe'] = $this->tipe;
        if (isset($_FILES['nama_file']) && $_FILES['nama_file']['error'] == UPLOAD_ERR_OK) {
            $data['nama_file'] = $this->uploadFile($data['judul']);
        }
        if (LaporanSinkronisasi::create($data)) {
            redirect_with('success', 'Berhasil Tambah Data', $this->routePath);
        }

        redirect_with('error', 'Gagal Tambah Data', $this->routePath);
    }

    public function update($id = null): void
    {
        isCan('u');

        $obj        = LaporanSinkronisasi::findOrFail($id);
        $updateData = $this->request;
        if (isset($_FILES['nama_file']) && $_FILES['nama_file']['error'] == UPLOAD_ERR_OK) {
            $updateData['nama_file'] = $this->uploadFile($updateData['judul']);
        }

        if ($obj->update($updateData)) {
            redirect_with('success', 'Berhasil Ubah Data', $this->routePath);
        }

        redirect_with('error', 'Gagal Ubah Data', $this->routePath);
    }

    public function delete($id = null): void
    {
        isCan('h');
        if (LaporanSinkronisasi::destroy($id ?? $this->request['id_cb']) !== 0) {
            redirect_with('success', 'Berhasil Hapus Data');
        }
        redirect_with('error', 'Gagal Hapus Data');
    }

    public function unduh(int $id = 0): void
    {
        $nama_file = LaporanSinkronisasi::find($id)->nama_file;
        ambilBerkas($nama_file, $this->controller, null, LOKASI_DOKUMEN);
    }

    public function kirim(): void
    {
        isCan('u');

        foreach (glob(LOKASI_DOKUMEN . '*_opendk.zip') as $file) {
            if (file_exists($file)) {
                unlink($file);
                break;
            }
        }

        $desa_id = kode_wilayah($this->header['desa']['kode_desa']);
        $id      = $this->input->post('id_cb');

        //Tambah/Ubah Data
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL            => setting('api_opendk_server') . '/api/v1/' . str_replace('_', '-', $this->tipe),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_POSTFIELDS     => json_encode(['desa_id' => $desa_id, $this->tipe => $this->opendk($id)], JSON_THROW_ON_ERROR),
            CURLOPT_HTTPHEADER     => [
                'Accept: application/json',
                'Content-Type: application/json',
                'Authorization: Bearer ' . setting('api_opendk_key'),
            ],
        ]);

        $response  = json_decode(curl_exec($curl), null);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if (! curl_errno($curl) && $http_code !== 422) {
            // Ubah tgl kirim
            LaporanSinkronisasi::where(['id' => $id])->update(['kirim' => date('Y-m-d H:i:s')]);
        }

        curl_close($curl);
        $this->session->unset_userdata(['success', 'error_msg']);
        $this->session->set_flashdata('notif', $response);

        redirect($this->controller);
    }

    private function getTahun()
    {
        return LaporanSinkronisasi::select(['tahun'])->whereTipe($this->tipe)->get();
    }

    private function uploadFile($namaFile)
    {
        $config['upload_path']   = LOKASI_DOKUMEN;
        $config['allowed_types'] = 'pdf';
        $config['max_size']      = max_upload() * 1024;
        $config['file_name']     = namafile($namaFile);
        $config['overwrite']     = true;

        return $this->upload('nama_file', $config);
    }

    private function opendk($id)
    {
        $kirim = [];

        foreach (LaporanSinkronisasi::whereIn('id', $id)->get()->toArray() as $key => $data) {
            $kirim[$key]['id']    = $data['id'];
            $kirim[$key]['judul'] = $data['judul'];
            if ($this->tipe == 'laporan_apbdes') {
                $kirim[$key]['semester'] = $data['semester'];
            } else {
                $kirim[$key]['bulan'] = $data['semester'];
            }
            $kirim[$key]['nama_file']  = $data['nama_file'];
            $kirim[$key]['tahun']      = $data['tahun'];
            $kirim[$key]['created_at'] = $data['created_at'];
            $kirim[$key]['updated_at'] = $data['updated_at'];
            $kirim[$key]['file']       = $this->file($data['nama_file']);
        }

        return $kirim;
    }

    private function file($nama_file)
    {
        return base64_encode(file_get_contents(LOKASI_DOKUMEN . $nama_file));
    }
}
