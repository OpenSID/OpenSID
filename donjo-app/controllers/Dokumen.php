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

use App\Enums\DokumenEnum;
use App\Enums\KategoriPublicEnum;
use App\Enums\StatusEnum;
use App\Models\Dokumen as DokumenModel;
use App\Models\DokumenHidup;
use App\Models\LogEkspor;
use App\Traits\Upload;
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class Dokumen extends Admin_Controller
{
    use Upload;

    public $modul_ini     = 'sekretariat';
    public $sub_modul_ini = 'informasi-publik';

    public function __construct()
    {
        parent::__construct();
        isCan('b');
        $this->load->helper('download');
    }

    public function index(): void
    {
        $data['status']   = [StatusEnum::YA => 'Aktif', StatusEnum::TIDAK => 'Tidak Aktif'];
        $data['kat_nama'] = DokumenEnum::valueOf(DokumenEnum::INFORMASI_PUBLIK);

        view('admin.dokumen.informasi_publik.index ', $data);
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
        $status    = $this->input->get('status') ?? null;
        $canUpdate = can('u');
        $canDelete = can('h');

        return datatables()->of(
            DokumenHidup::informasiPublik()
                ->when($status != null, static fn ($q) => $q->whereEnabled($status))
        )->addColumn('ceklist', static function ($row) use ($canDelete) {
                if ($canDelete) {
                    return '<input type="checkbox" name="id_cb[]" value="' . $row->id . '"/>';
                }
            })
            ->addIndexColumn()
            ->addColumn('aksi', static function ($row) use ($canUpdate, $canDelete): string {
                $aksi = '';
                if ($canUpdate) {
                    if (in_array($row->kategori, [DokumenEnum::KEPUTUSAN_KEPALA_DESA, DokumenEnum::PERATURAN])) {
                        $aksi .= '<a href="' . ci_route('dokumen_sekretariat.form.' . $row->kategori, $row->id) . '" class="btn btn-warning btn-sm" title="Ubah" style="margin-right: 2px"><i class="fa fa-edit"></i></a>';
                    } else {
                        $aksi .= '<a href="' . ci_route('dokumen.form', $row->id) . '" class="btn btn-warning btn-sm" title="Ubah" style="margin-right: 2px"><i class="fa fa-edit"></i></a>';
                    }

                    if ($row->isActive()) {
                        $aksi .= '<a href="' . ci_route('dokumen.lock', $row->id) . '" class="btn bg-navy btn-sm" title="Non Aktifkan" style="margin-right: 2px"><i class="fa fa-unlock"></i></a>';
                    } else {
                        $aksi .= '<a href="' . ci_route('dokumen.lock', $row->id) . '" class="btn bg-navy btn-sm" title="Aktifkan" style="margin-right: 2px"><i class="fa fa-lock"></i></a>';
                    }
                }

                if ($row->tipe == '1') {
                    $aksi .= "<a href='" . ci_route('dokumen.unduh_berkas', $row->id) . '\' class="btn bg-purple btn-sm"  title="Unduh" style="margin-right: 2px"><i class="fa fa-download"></i></a>';
                } else {
                    $aksi .= "<a href='" . $row->url . '\' class="btn bg-purple btn-sm"  title="Unduh" target="_blank" style="margin-right: 2px"><i class="fa fa-download"></i></a>';
                }

                if ($canDelete) {
                    $aksi .= '<a href="#" data-href="' . ci_route('dokumen.delete', $row->id) . '" class="btn bg-maroon btn-sm"  title="Hapus" data-toggle="modal" data-target="#confirm-delete" style="margin-right: 2px"><i class="fa fa-trash-o"></i></a>';
                }

                return $aksi;
            })
            ->addColumn('infoPublic', static fn ($row): ?string => KategoriPublicEnum::valueOf($row->kategori_info_publik))
            ->addColumn('aktif', static fn ($row): string => $row->isActive() ? 'Ya' : 'Tidak')
            ->addColumn('dimuat', static fn ($row): string => tgl_indo2($row->tgl_upload))
            ->rawColumns(['ceklist', 'aksi'])
            ->make();
        }

        return show_404();
    }

    public function form($id = '')
    {
        isCan('u');

        if ($id) {
            $data['dokumen']     = DokumenHidup::where('id', $id)->first();
            $data['form_action'] = ci_route('dokumen.update', $id);
        } else {
            $data['dokumen']     = null;
            $data['form_action'] = ci_route('dokumen.insert');
        }

        $data['kat_nama']             = DokumenEnum::valueOf(DokumenEnum::INFORMASI_PUBLIK);
        $data['list_kategori_publik'] = KategoriPublicEnum::all();

        return view('admin.dokumen.informasi_publik.form', $data);
    }

    public function insert(): void
    {
        isCan('u');
        $post             = $this->input->post();
        $post['kategori'] = DokumenEnum::INFORMASI_PUBLIK;
        $data             = DokumenModel::validasi($post);
        if ($this->request['satuan']) {
            $config['upload_path']   = LOKASI_DOKUMEN;
            $config['allowed_types'] = 'jpg|jpeg|png|pdf';
            $config['file_name']     = namafile($this->input->post('nama', true));

            $data['satuan'] = $this->upload('satuan', $config);
        }

        try {
            DokumenModel::create($data);
            redirect_with('success', 'Dokumen berhasil disimpan');
        } catch (Exception $e) {
            log_message('error', $e->getMessage());
            redirect_with('error', 'Dokumen gagal disimpan');
        }
    }

    public function update($id): void
    {
        isCan('u');

        $dokumen = DokumenModel::find($id) ?? show_404();
        $data    = DokumenModel::validasi($this->input->post());
        if ($this->request['satuan']) {
            $config['upload_path']   = LOKASI_DOKUMEN;
            $config['allowed_types'] = 'jpg|jpeg|png|pdf';
            $config['file_name']     = namafile($this->input->post('nama', true));

            $data['satuan'] = $this->upload('satuan', $config);
        }
        if ($dokumen->update($data)) {
            redirect_with('success', 'Berhasil Ubah Data Dokumen');
        }
        redirect_with('error', 'Gagal Ubah Data Dokumen');
    }

    public function delete($cat, $id = 0): void
    {
        isCan('h');
        DokumenModel::destroy($this->request['id_cb'] ?? $id);
        redirect_with('success', 'Dokumen berhasil dihapus');
    }

    public function lock($id): void
    {
        isCan('u');
        if (DokumenModel::gantiStatus($id, 'enabled')) {
            redirect_with('success', 'Berhasil ubah status dokumen');
        }

        redirect_with('error', 'Gagal status dokumen');
    }

    public function dialog_cetak($aksi = 'cetak')
    {
        $data['tahun_laporan'] = DokumenHidup::getTahun(DokumenEnum::INFORMASI_PUBLIK);
        $data['aksi']          = $aksi;
        $data['kat']           = DokumenEnum::INFORMASI_PUBLIK;
        $data['form_action']   = ci_route('dokumen.cetak', $aksi);

        return view('admin.layouts.components.kades.dialog_cetak', $data);
    }

    public function cetak($aksi = 'cetak')
    {
        $tahun         = $this->input->post('tahun') ?? null;
        $data          = $this->modal_penandatangan();
        $data['tahun'] = $tahun;
        $data['aksi']  = $aksi;
        $data['main']  = DokumenHidup::informasiPublik()->when($tahun, static fn ($q) => $q->where(['tahun' => $tahun]))->get();

        $data['file']      = 'Dokumen_Informasi_Publik_' . date('Y-m-d');
        $data['kategori']  = 'Informasi Publik';
        $data['isi']       = 'admin.dokumen.informasi_publik.cetak';
        $data['letak_ttd'] = ['2', '2', '5'];

        view('admin.layouts.components.format_cetak', $data);
    }

    /**
     * Unduh berkas berdasarkan kolom dokumen.id
     *
     * @param int        $id_dokumen Id berkas pada koloam dokumen.id
     * @param mixed|null $id_pend
     * @param mixed      $tampil
     * @param mixed      $popup
     */
    public function unduh_berkas($id_dokumen, $id_pend = null, $tampil = false, $popup = 0): void
    {
        // Ambil nama berkas dari database
        $data = DokumenHidup::getDokumen($id_dokumen);

        if ($data['url'] != null) {
            redirect($data['url']);
        }

        ambilBerkas($data['satuan'], $this->controller, null, LOKASI_DOKUMEN, $tampil, $popup);
    }

    public function tampilkan_berkas($id_dokumen, $id_pend = 0 ? null : null, $popup = 0): void
    {
        $this->unduh_berkas($id_dokumen, $id_pend, $tampil = true, $popup);
    }

    public function ekspor()
    {
        $data['form_action']   = ci_route('dokumen.ekspor_csv');
        $data['log_semua']     = LogEkspor::where(['kode_ekspor' => 'informasi_publik', 'semua' => 1])->orderByDesc('tgl_ekspor')->first();
        $data['log_perubahan'] = LogEkspor::where(['kode_ekspor' => 'informasi_publik', 'semua' => 2])->orderByDesc('tgl_ekspor')->first();

        view('admin.dokumen.informasi_publik.ekspor', $data);
    }

    public function ekspor_csv()
    {
        $filename = 'informasi_publik_' . date('Ymd') . '.csv';
        // Gunakan file temporer
        $tmpfname = tempnam(sys_get_temp_dir(), '');
        // Siapkan daftar berkas untuk dimasukkan ke zip
        $berkas   = [];
        $berkas[] = [
            'nama' => $filename,
            'file' => $tmpfname,
        ];
        // Folder untuk berkas dokumen dalam zip
        $berkas[] = [
            'nama' => 'dir',
            'file' => 'berkas',
        ];

        // Ambil data dan berkas infoemasi publik
        $file       = fopen($tmpfname, 'wb');
        $tipeEkspor = $this->input->post('data_ekspor');
        $kodeDesa   = identitas('kode_desa');
        $tglDari    = $this->input->post('tgl_dari');
        if ($tipeEkspor == 1) {
            $data = DokumenHidup::informasiPublik()->selectRaw("id, 0 as aksi,'{$kodeDesa}' as kode_desa, satuan, nama, tgl_upload, updated_at, enabled, kategori_info_publik as kategori, tahun")->get()->toArray();
        } else {
            $data = DokumenHidup::informasiPublik()->selectRaw("id,
			(CASE when deleted = 1
				then '3'
				else
					case when DATE(tgl_upload) > STR_TO_DATE('{$tglDari}', '%d-%m-%Y')
						then '1'
						else '2'
					end
				end) as aksi
		,'{$kodeDesa}' as kode_desa, satuan, nama, tgl_upload, updated_at, enabled, kategori_info_publik as kategori, tahun")->whereRaw(DB::raw("DATE(updated_at) > STR_TO_DATE('{$tglDari}', '%d-%m-%Y')"))->get()->toArray();
        }

        $header = array_keys($data[0]);
        fputcsv($file, $header);

        foreach ($data as $baris) {
            fputcsv($file, array_values($baris));
            // Masukkan berkas ke dalam folder dalam zip
            $berkas[] = [
                'nama' => 'berkas/' . $baris['satuan'],
                'file' => FCPATH . LOKASI_DOKUMEN . $baris['satuan'],
            ];
        }
        fclose($file);

        // Tulis log ekspor
        $log = [
            'kode_ekspor' => 'informasi_publik',
            'semua'       => $this->input->post('data_ekspor'),
            'total'       => count($data),
        ];
        LogEkspor::create($log);

        // Masukkan semua berkas ke dalam zip
        $berkas_zip = masukkan_zip($berkas);
        // Unduh berkas zip
        $data = $this->header['desa'];
        header('Content-Description: File Transfer');
        header('Content-disposition: attachment; filename=informasi_publik_' . $data['kode_desa'] . '_' . date('d-m-Y') . '.zip');
        header('Content-type: application/zip');
        readfile($berkas_zip);
    }
}
