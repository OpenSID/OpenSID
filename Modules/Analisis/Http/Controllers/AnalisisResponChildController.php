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

require_once FCPATH . 'Modules/Analisis/Http/Controllers/AnalisisResponController.php';

use App\Traits\Upload;
use Illuminate\Support\Facades\DB;
use Modules\Analisis\Models\AnalisisIndikator;
use Modules\Analisis\Models\AnalisisParameter;
use Modules\Analisis\Models\AnalisisPeriode;
use Modules\Analisis\Models\AnalisisRespon;

class AnalisisResponChildController extends AnalisisResponController
{
    use Upload;

    public function formChild($master, $parentSubjek, $idSubjek)
    {
        isCan('u');
        $data['form_action'] = ci_route('analisis_respon.' . $master . '.child.update.' . $parentSubjek, $idSubjek);
        $per                 = $this->getPeriodeChild();
        $data['list_jawab']  = $this->listIndikatorChild($idSubjek, $per);

        return view('analisis::respon.child.form', $data);
    }

    public function updateChild($master, $parentSubjek, $idSubjek): void
    {
        isCan('u');
        DB::beginTransaction();
        $per = $this->getPeriodeChild();

        try {
            AnalisisRespon::updateKuisioner($master, $per, $_POST, $idSubjek);
            DB::commit();
            redirect_with('success', 'Berhasil Simpan Data Kuisioner', ci_route('analisis_respon.' . $master . '.form', $parentSubjek));
        } catch (Exception $e) {
            DB::rollBack();
            log_message('error', $e->getMessage());
            redirect_with('error', 'Gagal Ubah Data Kuisioner ' . $e->getMessage(), ci_route('analisis_respon.' . $master . '.form', $parentSubjek));
        }
    }

    public function listIndikatorChild($idSubjek, $periode)
    {
        $idChild = $this->analisisMaster->id_child;
        $per     = $periode;
        $delik   = session('delik');
        $data    = AnalisisIndikator::where('id_master', $idChild)
            ->orderBy('nomor')
            ->get()
            ->toArray();
        $counter = count($data);

        for ($i = 0; $i < $counter; $i++) {
            $data[$i]['no'] = $i + 1;

            if ($data[$i]['id_tipe'] == 1 || $data[$i]['id_tipe'] == 2) {
                $data[$i]['parameter_respon'] = $this->listJawab4($idSubjek, $data[$i]['id'], $per);
            } else {
                $data[$i]['parameter_respon'] = ($delik) ? '' : $this->listJawab5($idSubjek, $data[$i]['id'], $per);
            }
        }

        return $data;
    }

    private function listJawab4($id = 0, $in = 0, $per = 0)
    {
        $delik = session('delik');
        $query = AnalisisParameter::selectRaw('id as id_parameter,jawaban,kode_jawaban')
            ->where('id_indikator', $in)
            ->orderBy('kode_jawaban', 'ASC');
        if ($delik) {
            $query->selectRaw('0 as cek');
        } else {
            $query->selectRaw('(SELECT count(id_subjek) FROM analisis_respon WHERE id_parameter = analisis_parameter.id AND id_subjek =' . $id . ' AND id_periode=' . $per . ') as cek');
        }

        return $query->get()->toArray();
    }

    private function listJawab5($id = 0, $in = 0, $per = 0)
    {
        return AnalisisRespon::selectRaw('analisis_parameter.id as id_parameter,analisis_parameter.jawaban')
            ->leftJoin('analisis_parameter', 'analisis_respon.id_parameter', '=', 'analisis_parameter.id')
            ->where(['analisis_respon.id_indikator' => $in, 'analisis_respon.id_subjek' => $id, 'analisis_respon.id_periode' => $per])
            ->get()
            ->toArray();
    }

    public function getPeriodeChild()
    {
        $idChild = $this->analisisMaster->id_child;

        return AnalisisPeriode::select('id')->where('id_master', $idChild)->where('aktif', 1)->first()->id;
    }
}
