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

use App\Enums\SatuanWaktuEnum;
use App\Models\Pembangunan;

defined('BASEPATH') || exit('No direct script access allowed');

class Bumindes_rencana_pembangunan extends Admin_Controller
{
    public $modul_ini     = 'buku-administrasi-desa';
    public $sub_modul_ini = 'administrasi-pembangunan';
    protected $tipe       = 'rencana';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
    }

    public function index()
    {
        $data['tipe']        = ucwords($this->tipe);
        $data['selectedNav'] = $this->tipe;
        $data['subtitle']    = 'Buku ' . ucwords($this->tipe) . ' Pembangunan';
        $data['tahun']       = Pembangunan::tipe($this->tipe)->distinct()->get('tahun_anggaran');
        $data['mainContent'] = 'admin.bumindes.pembangunan.' . $this->tipe . '.index';

        return view('admin.bumindes.pembangunan.index', $data);
    }

    public function datatables()
    {
        $tahun        = $this->input->get('tahun') ?? null;
        $satuan_waktu = SatuanWaktuEnum::all();

        if ($this->input->is_ajax_request()) {
            return datatables()->of(Pembangunan::tipe($this->tipe)->with(['wilayah'])->when($tahun, static fn ($q) => $q->where('tahun_anggaran', $tahun)))
                ->addIndexColumn()
                ->editColumn('alamat', static fn ($row) => $row->wilayah->dusun ?? 'Lokasi tidak diketahui')
                ->editColumn('proyek_baru', static fn ($row) => $row->sifat_proyek == 'BARU' ? '&#10004' : '-')
                ->editColumn('proyek_lanjutan', static fn ($row) => $row->sifat_proyek == 'LANJUTAN' ? '&#10004' : '-')
                ->editColumn('format_waktu', static fn ($row) => $row->waktu . ' ' . $satuan_waktu[$row->satuan_waktu])
                ->editColumn('anggaran', static fn ($row) => $row->perubahan_anggaran > 0 ? $row->perubahan_anggaran : $row->anggaran)
                ->rawColumns(['proyek_baru', 'proyek_lanjutan'])
                ->make();
        }

        return show_404();
    }

    public function dialog($aksi = 'cetak')
    {
        $data['tahun']      = Pembangunan::tipe($this->tipe)->distinct()->get('tahun_anggaran');
        $data['aksi']       = $aksi;
        $data['formAction'] = ci_route('bumindes_' . $this->tipe . '_pembangunan.cetak', $aksi);

        return view('admin.bumindes.pembangunan.dialog', $data);
    }

    public function cetak($aksi = '')
    {
        $tahun = $this->input->post('tahun');

        $data         = $this->modal_penandatangan();
        $data['aksi'] = $aksi;
        $data['main'] = Pembangunan::tipe($this->tipe)->with(['wilayah'])->when($tahun, static fn ($q) => $q->where('tahun_anggaran', $tahun))->get();
        if (empty($tahun)) {
            $tahun_pembangunan = Pembangunan::selectRaw('MIN(CAST(tahun_anggaran AS CHAR)) as awal, MAX(CAST(tahun_anggaran AS CHAR)) as akhir ')->first();
            $data['tahun']     = ($tahun_pembangunan->awal == $tahun_pembangunan->akhir) ? $tahun_pembangunan->awal : "{$tahun_pembangunan->awal} -  {$tahun_pembangunan->akhir}";
        }
        $data['satuan_waktu'] = SatuanWaktuEnum::all();
        $data['tgl_cetak']    = $this->input->post('tgl_cetak');
        $data['file']         = 'Buku ' . ucwords($this->tipe) . ' Kerja Pembangunan';
        $data['isi']          = 'admin.bumindes.pembangunan.' . $this->tipe . '.cetak';
        $data['letak_ttd']    = ['1', '1', '3'];

        return view('admin.layouts.components.format_cetak', $data);
    }

    // Lainnya
    public function lainnya($submenu)
    {
        $data['selectedNav'] = $submenu;
        $data['mainContent'] = 'admin.bumindes.pembangunan.rencana.index';

        return view('admin.bumindes.pembangunan.main', $data);
    }
}
