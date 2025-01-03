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

use App\Models\Area;
use App\Models\Cdesa as CdesaModel;
use App\Models\MutasiCdesa;
use App\Models\Persil;
use App\Models\RefPersilKelas;
use App\Models\RefPersilMutasi;
use App\Models\Wilayah;

defined('BASEPATH') || exit('No direct script access allowed');

class Cdesa_mutasi extends Admin_Controller
{
    public $modul_ini     = 'pertanahan';
    public $sub_modul_ini = 'c-desa';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function index($id_cdesa, $id_persil = null)
    {
        $data['cdesa']  = CdesaModel::with(['penduduk'])->findOrFail($id_cdesa);
        $data['persil'] = Persil::with('refKelas', 'wilayah')->find($id_persil);

        return view('admin.pertanahan.cdesa.mutasi.index', $data);
    }

    public function delete($id_cdesa, $id_persil, $id_mutasi): void
    {
        isCan('h');

        if (MutasiCdesa::findOrFail($id_mutasi)->delete()) {
            redirect_with('success', 'Berhasil Hapus Data', route('cdesa.mutasi', ['id_cdesa' => $id_cdesa, 'id_persil' => $id_persil]));
        }

        redirect_with('error', 'Gagal Hapus Data');
    }

    public function datatables($id_cdesa, $id_persil = null)
    {
        if ($this->input->is_ajax_request()) {
            $query = MutasiCdesa::getList($id_cdesa, $id_persil);

            return datatables()->of($query)
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row): string {
                    $aksi = '';
                    if (can('u')) {
                        $aksi .= '<a href="' . route('cdesa.create_mutasi', ['id_cdesa' => $row->id_cdesa_masuk, 'id_persil' => $row->id_persil, 'id_mutasi' => $row->id]) . '" class="btn bg-orange btn-sm" title="Ubah"><i class="fa fa-edit"></i></a>';
                        $aksi .= '<a href="#" data-path="' . $row->path . '" class="btn bg-olive btn-sm area-map" title="Lihat Map" data-toggle="modal" data-target="#map-modal"><i class="fa fa-map"></i></a>';
                        if ($row->jenis_mutasi != '9') {
                            if (can('h')) {
                                $aksi .= '<a href="#" data-href="' . route('cdesa.hapus_mutasi', ['id_cdesa' => $row->id_cdesa_masuk, 'id_persil' => $row->id_persil, 'id_mutasi' => $row->id]) . '" class="btn bg-maroon btn-sm" title="Hapus" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>';
                            }
                        } else {
                            $aksi .= '<a href="#" data-href="' . ci_route('cdesa.awal_persil', [$row->id_cdesa_masuk, $row->id_persil, 1]) . '" class="btn bg-maroon btn-sm" title="Bukan pemilik awal" data-toggle="modal" data-target="#confirm-delete"><i class="fa fa-trash-o"></i></a>';
                        }
                    }

                    return $aksi;
                })
                ->editColumn('nomor', static fn ($row) => sprintf('%04s', $row->nomor))
                ->editColumn('tanggal_mutasi', static fn ($row) => tgl_indo_out($row->tanggal_mutasi))
                ->editColumn('luas_masuk', static function ($row) {
                    $txt = $row->luas_masuk;
                    if ($row->cdesa_keluar && $row->id_cdesa_masuk == $row->id_cdesa) {
                        $txt .= 'dari ' . '<a href="' . ci_route('cdesa.mutasi', $row->cdesa_keluar) . '/' . $row->id_persil . '">C-Desa ini</a>';
                    }

                    return $txt;
                })
                ->editColumn('luas_keluar', static function ($row) {
                    $txt = $row->luas_keluar;
                    if ($row->cdesa_keluar && $row->id_cdesa_masuk != $row->id_cdesa) {
                        $txt .= 'ke ' . '<a href="' . ci_route('cdesa.mutasi', $row->cdesa_keluar) . '/' . $row->id_persil . '">C-Desa ini</a>';
                    }

                    return $txt;
                })
                ->rawColumns(['ceklist', 'aksi', 'luas_masuk', 'luas_keluar'])
                ->make();
        }

        return show_404();
    }

    public function form($id_cdesa, $id_persil = '', $id_mutasi = '')
    {
        isCan('u');

        $data['persil'] = Persil::with('refKelas', 'wilayah')->find($id_persil);

        if ($id_mutasi) {
            $data['mutasi'] = MutasiCdesa::findOrFail($id_mutasi);
        }

        $data['cdesa'] = CdesaModel::with(['penduduk'])->findOrFail($id_cdesa);

        $data['list_cdesa'] = CdesaModel::listCdesa([$id_cdesa]);

        $data['list_persil'] = Persil::list();

        $data['persil_kelas']        = RefPersilKelas::get()->toArray();
        $data['persil_sebab_mutasi'] = RefPersilMutasi::get()->toArray();

        $data['persil_lokasi'] = Wilayah::get();
        $data['peta']          = Area::areaMap();

        return view('admin.pertanahan.cdesa.mutasi.form', $data);
    }

    public function simpan($idCdesa, $idMutasi = ''): void
    {
        isCan('u');

        $data = $this->validate($idCdesa);
        if ($idMutasi) {
            $mutasi = MutasiCdesa::findOrFail($idMutasi)->update($data);
        } else {
            $mutasi = MutasiCdesa::create($data);
        }

        if ($mutasi) {
            if ($data['id_persil']) {
                $url = ci_route('cdesa.mutasi', $idCdesa) . '/' . $data['id_persil'];
                redirect_with('success', 'Data Persil telah DISIMPAN', $url);
            }
            redirect_with('success', 'Data Persil telah DISIMPAN', ci_route('cdesa.rincian', $idCdesa));
        }

        redirect_with('error', 'Gagal Tambah Data');
    }

    protected function validate($idCdesa)
    {
        $post = $this->input->post();

        $data['id_persil']        = $post['id_persil'];
        $data['id_cdesa_masuk']   = $idCdesa;
        $data['no_bidang_persil'] = bilangan($post['no_bidang_persil']) ?: null;
        $data['no_objek_pajak']   = strip_tags($post['no_objek_pajak']);
        $data['tanggal_mutasi']   = $post['tanggal_mutasi'] ? tgl_indo_in($post['tanggal_mutasi']) : null;
        $data['jenis_mutasi']     = $post['jenis_mutasi'] ?: null;
        $data['luas']             = bilangan_titik($post['luas']) ?: null;
        $data['cdesa_keluar']     = bilangan($post['cdesa_keluar']) ?: null;
        $data['keterangan']       = strip_tags($post['keterangan']) ?: null;
        $data['path']             = $post['path'];
        $data['id_peta']          = ($post['area_tanah'] == 1 || $post['area_tanah'] == null) ? $post['id_peta'] : null;
        $data['id_peta']          = $data['id_peta'] ?: null;

        return $data;
    }
}
