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

use App\Models\Cdesa;
use App\Models\Cdesa as CdesaModel;
use App\Models\Persil;

defined('BASEPATH') || exit('No direct script access allowed');

class Cdesa_rincian extends Admin_Controller
{
    public $modul_ini     = 'pertanahan';
    public $sub_modul_ini = 'c-desa';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function index($rincian)
    {
        $data['rincian'] = Cdesa::with(['penduduk'])->findOrFail($rincian);

        return view('admin.pertanahan.cdesa.rincian.index', $data);
    }

    public function datatables($rincian = 0)
    {
        if ($this->input->is_ajax_request()) {
            $query = Persil::with(['refKelas', 'wilayah'])->withCount(['mutasi as jml_mutasi'])->filterCdesa($rincian);

            return datatables()->of($query)
                ->addIndexColumn()
                ->addColumn('aksi', static function ($row) use ($rincian) {

                    $aksi = '<a href="' . ci_route('cdesa.mutasi', [$rincian, $row->id]) . '" class="btn bg-maroon btn-sm" style="margin-right: 3px;"  title="Daftar Mutasi"><i class="fa fa-exchange"></i></a>';

                    $aksi .= '<a href="#" data-path="' . $row->path . '" class="btn bg-olive btn-sm area-map" title="Lihat Map" data-toggle="modal" style="margin-right: 3px;" data-target="#map-modal" ><i class="fa fa-map"></i></a>';

                    return $aksi;
                })
                ->addColumn('kelas_tanah', static fn ($q) => $q->kelasTanah)
                ->editColumn('nomor_persil', static function ($row) use ($rincian) {
                    $pemilik = $row->cdesa_awal == $rincian ? '<code>( Pemilik awal )</code>' : '';

                    return '<a href="' . ci_route('data_persil.rincian', $row->id) . '">' . $row->nomor . ' : ' . $row->nomor_urut_bidang . $pemilik . '</a>';
                })
                ->editColumn('lokasi', static fn ($row) => $row->wilayah ? $row->wilayah->alamat : ($row->lokasi ?? 'Lokasi Tidak Ditemukan'))
                ->rawColumns(['ceklist', 'aksi', 'nomor_persil'])
                ->make();
        }

        return show_404();
    }

    public function form($rincian, $id = '')
    {
        isCan('u');

        if ($id) {
            $action      = 'Ubah';
            $form_action = ci_route('cdesa.update', $id);

            $ref_syarat_surat = CdesaModel::findOrFail($id);
        } else {
            $action           = 'Tambah';
            $form_action      = ci_route('cdesa.insert');
            $ref_syarat_surat = null;
        }

        return view('admin.pertanahan.cdesa.rincian.form', ['action' => $action, 'form_action' => $form_action, 'ref_syarat_surat' => $ref_syarat_surat]);
    }

    public function insert($rincian): void
    {
        isCan('u');

        if (CdesaModel::create(static::validate())) {
            redirect_with('success', 'Berhasil Tambah Data');
        }

        redirect_with('error', 'Gagal Tambah Data');
    }

    public function update($rincian, $id = ''): void
    {
        isCan('u');

        $data = CdesaModel::findOrFail($id);

        if ($data->update(static::validate())) {
            redirect_with('success', 'Berhasil Ubah Data');
        }
        redirect_with('error', 'Gagal Ubah Data');
    }

    public function delete($rincian, $id = ''): void
    {
        isCan('h');

        if (CdesaModel::findOrFail($id)->delete()) {
            redirect_with('success', 'Berhasil Hapus Data');
        }
        redirect_with('error', 'Gagal Hapus Data');
    }

    public function deleteAll($rincian): void
    {
        isCan('h');

        foreach ($this->request['id_cb'] as $id) {
            $this->delete($id);
        }

        redirect_with('success', 'Berhasil Hapus Data');
    }

    protected function validate()
    {
        return $this->validated(request(), [
            'ref_syarat_nama' => 'required|min:3|max:255',
        ]);
    }
}
