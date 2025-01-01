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

use App\Models\Dokumen as DokumenModel;
use App\Models\Keluarga;
use App\Models\SyaratSurat;

class Dokumen extends Mandiri_Controller
{
    public function index()
    {
        return view('layanan_mandiri.dokumen.index');
    }

    public function datatables()
    {
        if ($this->input->is_ajax_request()) {
            $query = DokumenModel::where('id_pend', $this->is_login->id_pend);

            return datatables($query)
                ->addIndexColumn()
                ->addColumn('aksi', static function ($item): string {
                    $aksi        = '';
                    $editUrl     = site_url("layanan-mandiri/dokumen/form/{$item->id}");
                    $deleteUrl   = site_url("layanan-mandiri/dokumen/hapus/{$item->id}");
                    $downloadUrl = site_url("layanan-mandiri/dokumen/unduh/{$item->id}");

                    $aksi .= '<a href="' . $editUrl . '" title="Ubah" class="btn btn-warning btn-sm"><i class="fa fa-pencil"></i></a> ';
                    $aksi .= '<a href="' . $deleteUrl . '" title="Hapus" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a> ';

                    return $aksi . ('<a target="_blank" href="' . $downloadUrl . '" title="Unduh" class="btn bg-purple btn-sm"><i class="fa fa-eye"></i></a>');
                })
                ->editColumn('id_syarat', static fn ($data) => SyaratSurat::where('ref_syarat_id', $data->id_syarat)->first()->ref_syarat_nama)
                ->editColumn('tgl_upload', static fn ($data) => tgl_indo2($data->tgl_upload))
                ->rawColumns(['aksi', 'ref_syarat_nama', 'nama', 'tgl_upload'])
                ->make();
        }

        return show_404();
    }

    public function form($id = '')
    {
        if ($this->is_login->kk_level == '1') { //Jika Kepala Keluarga
            $data['kk'] = Keluarga::with('anggota')->where('id', $this->is_login->id_kk)->get();
        }

        if ($id) {
            $dokumen             = new DokumenModel();
            $data['dokumen']     = $dokumen->where('id', $id)->where('id_pend', $this->session->is_login->id_pend)->firstOrFail();
            $data['anggota']     = array_column($dokumen->getDokumenDiAnggotaLain($id), 'id_pend');
            $data['id_pend']     = $this->is_login->id_pend;
            $data['nik']         = $this->is_login->nik;
            $data['aksi']        = 'Ubah';
            $data['form_action'] = site_url("layanan-mandiri/dokumen/ubah/{$id}");
        } else {
            $data['dokumen']     = null;
            $data['dokumen']     = null;
            $data['id_pend']     = $this->is_login->id_pend;
            $data['nik']         = $this->is_login->nik;
            $data['aksi']        = 'Tambah';
            $data['form_action'] = site_url('layanan-mandiri/dokumen/tambah');
        }

        $data['jenis_syarat_surat'] = SyaratSurat::orderBy('ref_syarat_id', 'ASC')->get();

        return view('layanan_mandiri.dokumen.form', $data);
    }

    public function tambah(): void
    {
        try {
            $dataInsert               = DokumenModel::validasi($this->input->post());
            $id_pend                  = $dataInsert['id_pend'];
            $dataInsert['satuan']     = $this->upload_dokumen();
            $dataInsert['updated_by'] = $dataInsert['id_pend'];
            $dataInsert['created_by'] = $dataInsert['id_pend'];
            $dokumen                  = DokumenModel::create($dataInsert);

            if ($dataInsert['anggota_kk']) {
                foreach ($dataInsert['anggota_kk'] as $anggota) {
                    $dataInsert['id_parent'] = $dokumen->id;
                    $dataInsert['id_pend']   = $anggota;
                    DokumenModel::create($dataInsert);
                }
            }
            $respon = [
                'status' => 'success',
                'pesan'  => 'Berhasil tambah dokumen',
            ];
            redirect_with('notif', $respon, 'layanan-mandiri/dokumen');
        } catch (Exception) {
            $respon = [
                'status' => 'error',
                'pesan'  => 'Gagal tambah dokumen -> ' . $this->session->error_msg,
            ];
            redirect_with('notif', $respon, 'layanan-mandiri/dokumen/form');
        }
    }

    public function ubah($id = ''): void
    {
        try {
            $dataUpdate               = DokumenModel::validasi($this->input->post());
            $dataUpdate['updated_by'] = $dataUpdate['id_pend'];
            if (isset($_FILES['satuan']) && $_FILES['satuan']['error'] == UPLOAD_ERR_OK) {
                $dataUpdate['satuan'] = $this->upload_dokumen();
            }
            $anggotaKK = $dataUpdate['anggota_kk'] ?? [];
            unset($dataUpdate['anggota_kk'], $dataUpdate['id_pend']);

            $dokumen = DokumenModel::find($id);
            $dokumen->update($dataUpdate);

            $id_pend = $dokumen->id_pend;

            $dokumenLain      = $dokumen->children;
            $anggotaLain      = $dokumenLain ? $dokumenLain->pluck('id_pend')->all() : [];
            $intersectAnggota = array_intersect($anggotaKK, $anggotaLain);

            foreach ($intersectAnggota as $value) {
                $dokumen->children->firstWhere('id_pend', $value)->update($dataUpdate);
            }

            $diffDeleteAnggota = array_diff($anggotaLain, $anggotaKK);

            foreach ($diffDeleteAnggota as $value) {
                $dokumen->children->firstWhere('id_pend', $value)->delete();
            }

            $diffInsertAnggota = array_diff($anggotaKK, $anggotaLain);

            foreach ($diffInsertAnggota as $value) {
                $dataUpdate['id_parent'] = $dokumen->id;
                $dataUpdate['id_pend']   = $value;
                $dataUpdate['satuan']    = $dokumen->satuan;
                DokumenModel::create($dataUpdate);
            }

            $respon = [
                'status' => 'success',
                'pesan'  => 'Berhasil ubah dokumen',
            ];
            redirect_with('notif', $respon, 'layanan-mandiri/dokumen/form/' . $id);
        } catch (Exception) {
            $respon = [
                'status' => 'error',
                'pesan'  => 'Gagal ubah dokumen -> ' . $this->session->error_msg,
            ];
            redirect_with('notif', $respon, 'layanan-mandiri/dokumen/form');
        }
    }

    private function upload_dokumen()
    {
        $old_file                = $this->input->post('old_file', true);
        $config['upload_path']   = LOKASI_DOKUMEN;
        $config['allowed_types'] = 'jpg|jpeg|png|pdf';
        $config['file_name']     = namafile($this->input->post('nama', true));

        $this->load->library('upload');
        $this->upload->initialize($config);

        if (! $this->upload->do_upload('satuan')) {
            session_error($this->upload->display_errors(null, null));

            return false;
        }

        if (empty($old_file)) {
            unlink(LOKASI_DOKUMEN . $old_file);
        }

        return $this->upload->data()['file_name'];
    }

    public function hapus($id = ''): void
    {
        try {
            DokumenModel::whereIdPend($this->session->is_login->id_pend)->whereIn('id_parent', $this->request['id_cb'] ?? [$id])->delete();
            DokumenModel::destroy($this->request['id_cb'] ?? $id);
            redirect_with('success', 'Berhasil hapus dokumen', 'layanan-mandiri/dokumen');
        } catch (Exception) {
            redirect_with('error', 'Gagal hapus dokumen', 'layanan-mandiri/dokumen');
        }
    }

    public function unduh($id = ''): void
    {
        $dokumen = new DokumenModel();
        // Ambil nama berkas dari database
        if ($berkas = $dokumen->getNamaBerkas($id, $this->is_login->id_pend)) {
            ambilBerkas($berkas, 'layanan-mandiri/dokumen', null, LOKASI_DOKUMEN, true);
        } else {
            $respon = [
                'status' => 'error',
                'pesan'  => 'Gagal unduh dokumen',
            ];
            redirect_with('notif', $respon, 'layanan-mandiri/dokumen');
        }
    }
}
