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
 * Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright Hak Cipta 2016 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license   http://www.gnu.org/licenses/gpl.html GPL V3
 * @link      https://github.com/OpenSID/OpenSID
 *
 */

use App\Services\ArsipFisikSurat;
use Illuminate\Support\Facades\View;

defined('BASEPATH') || exit('No direct script access allowed');

class Bumindes_arsip extends Admin_Controller
{
    public $modul_ini     = 'buku-administrasi-desa';
    public $sub_modul_ini = 'arsip-desa';

    /**
     * @var ArsipFisikSurat
     */
    private $arsipFisik;

    public function __construct()
    {
        parent::__construct();
        isCan('b');
        $this->arsipFisik = new ArsipFisikSurat();
    }

    public function index()
    {
        $data = [];

        foreach (['dokumen_desa', 'surat_masuk', 'surat_keluar', 'kependudukan', 'layanan_surat'] as $kategori) {
            $data[$kategori] = [
                'title' => ucwords(str_replace('_', ' ', $kategori)),
                'total' => $this->arsipFisik->totalData($kategori),
                'uri'   => $kategori,
            ];
        }

        $filter = $this->arsipFisik->semuaFilter();

        $data['list_tahun'] = $filter['tahun'];
        $data['list_jenis'] = $filter['jenis'];

        if ($this->input->is_ajax_request()) {
            return datatables(
                $this->arsipFisik
                    ->arsipDesaQuery()
                    ->when($this->input->get('jenis'), static function ($query, $jenis) {
                        $query->where('jenis', $jenis);
                    })
                    ->when($this->input->get('tahun'), static function ($query, $tahun) {
                        $query->where('tahun', $tahun);
                    })
                    ->when($this->input->get('kategori'), static function ($query, $kategori) {
                        $query->where('kategori', $kategori);
                    }, static function ($query) {
                        $query->where('kategori', 'layanan_surat');
                    })
            )
                ->addColumn('aksi', static function ($row): string {
                    $aksi = '';
                    $aksi .= View::make('admin.layouts.components.buttons.rincian', [
                        'url'   => "{$row->modul_asli}",
                        'judul' => 'Tampilkan di modul aslinya',
                    ])->render();

                    $aksi .= View::make('admin.layouts.components.buttons.edit', [
                        'url'   => "bumindes_arsip/tindakan_ubah/{$row->kategori}/{$row->id}",
                        'modal' => true,
                    ])->render();

                    if (isset($row->lampiran)) {
                        if ($row->lampiran != '') {
                            $aksi .= View::make('admin.layouts.components.buttons.btn', [
                                'url'        => ci_route('keluar.unduh.lampiran', $row->id),
                                'icon'       => 'fa fa-paperclip',
                                'judul'      => 'Unduh Lampiran',
                                'type'       => 'bg-blue',
                                'buttonOnly' => true,
                            ])->render();
                        }
                        $aksi .= View::make('admin.layouts.components.buttons.unduh', [
                            'url'        => ci_route('keluar.unduh.rtf', $row->id),
                            'buttonOnly' => true,
                        ])->render();
                    } else {
                        $aksi .= View::make('admin.layouts.components.buttons.lihat', [
                            'url'   => site_url("bumindes_arsip/tindakan_lihat/{$row->kategori}/{$row->id}/lihat"),
                            'blank' => true,
                        ])->render();
                        $aksi .= View::make('admin.layouts.components.buttons.unduh', [
                            'url'        => site_url("bumindes_arsip/tindakan_lihat/{$row->kategori}/{$row->id}/unduh"),
                            'buttonOnly' => true,
                        ])->render();
                    }

                    return $aksi;
                })
                ->editColumn('tanggal_dokumen', static fn ($item) => tgl_indo2($item->tanggal_dokumen))
                ->editColumn('nama_jenis', static fn ($item) => strtoupper(str_replace('_', ' ', $item->nama_jenis)))
                ->rawColumns(['aksi'])
                ->addIndexColumn()
                ->make();
        }

        return view('admin.bumindes.arsip.index', $data);
    }

    public function tindakan_lihat($kategori, $id, $tindakan): void
    {
        $tabel  = $this->get_table($kategori);
        $berkas = $this->arsipFisik->getNamaBerkas($tabel, $id);

        switch ($tindakan) {
            case 'lihat':
                $this->tampilkan_berkas($kategori, $tabel, $berkas);
                break;

            case 'unduh':
                $this->unduh_berkas($kategori, $tabel, $berkas);
                break;
        }
    }

    public function tindakan_ubah($kategori, $id)
    {
        return $this->modal_ubah_arsip($kategori, $id);
    }

    public function tampilkan_berkas($kategori, $tabel, ?string $berkas, $tampil = true): void
    {
        $lokasi = '';
        if ($tabel == 'dokumen_hidup') {
            $lokasi = LOKASI_DOKUMEN;
        } elseif ($tabel == 'surat_masuk' || $tabel == 'surat_keluar') {
            $lokasi = LOKASI_ARSIP;
        }

        $redirect = ! empty($lokasi) ? $this->controller . '?kategori=' . $kategori : $this->controller;

        ambilBerkas($berkas, $redirect, null, $lokasi, $tampil ?? false);
    }

    public function unduh_berkas($kategori, $tabel, ?string $berkas): void
    {
        $this->tampilkan_berkas($kategori, $tabel, $berkas, false);
    }

    public function modal_ubah_arsip($tabel, $id)
    {
        $data = [
            'value'       => $this->arsipFisik->getLokasiArsip($tabel, $id),
            'form_action' => site_url("{$this->controller}/ubah_dokumen/{$tabel}/{$id}"),
        ];

        return view('admin.bumindes.arsip.form', $data);
    }

    public function ubah_dokumen($tabel, $id): void
    {
        $lokasi_baru = nama_terbatas($this->input->post('lokasi_arsip'));

        if ($this->arsipFisik->updateLokasi($tabel, $id, $lokasi_baru)) {
            redirect_with('success', 'Berhasil Ubah Data', "{$this->controller}?kategori={$tabel}");
        }

        redirect_with('error', 'Gagal Ubah Data', "{$this->controller}?kategori={$tabel}");
    }

    private function get_table($kategori)
    {
        if ($kategori == 'dokumen_desa' || $kategori == 'kependudukan') {
            return 'dokumen_hidup';
        }
        if ($kategori == 'layanan_surat') {
            return 'log_surat';
        }
        if ($kategori == 'surat_masuk' || $kategori == 'surat_keluar') {
            return $kategori;
        }

        return null;
    }
}
