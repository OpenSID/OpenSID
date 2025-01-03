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

namespace Modules\Analisis\Models;

use App\Models\BaseModel;
use App\Models\PendudukHidup;
use App\Models\Rtm;
use Exception;
use Illuminate\Support\Facades\DB;
use Spreadsheet_Excel_Reader;

defined('BASEPATH') || exit('No direct script access allowed');

class AnalisisRespon extends BaseModel
{
    /**
     * {@inheritDoc}
     */
    protected $table = 'analisis_respon';

    protected $guarded = [];
    public $timestamps = false;

    public static function updateKuisioner($idMaster, $idPeriode, $postData, $id = null): void
    {
        $ia = 0;
        $it = 0;
        $ir = 0;
        $ic = 0;

        if (isset($postData['rb'])) {
            $id_rbx = $postData['rb'];

            foreach ($id_rbx as $id_px) {
                if ($id_px != '') {
                    $ir = 1;
                }
            }
        }
        if (isset($postData['cb'])) {
            $id_rby = $postData['cb'];

            foreach ($id_rby as $id_py) {
                if ($id_py != '') {
                    $ic = 1;
                }
            }
        }
        if (isset($postData['ia'])) {
            $id_iax = $postData['ia'];

            foreach ($id_iax as $id_px) {
                if ($id_px != '') {
                    $ia = 1;
                }
            }
        }
        if (isset($postData['it'])) {
            $id_iay = $postData['it'];

            foreach ($id_iay as $id_py) {
                if ($id_py != '') {
                    $it = 1;
                }
            }
        }

        //CEK ada input
        if ($ir != 0 || $ic != 0 || $ia != 0 || $it != 0) {
            self::where('id_subjek', $id)->where('id_periode', $idPeriode)->delete();
            if (! empty($postData['rb'])) {
                $id_rb = $postData['rb'];

                foreach ($id_rb as $id_p) {
                    if (empty($id_p)) {
                        continue;
                    } // Abaikan isian kosong
                    $p = preg_split('/\\./', $id_p);

                    $data['id_subjek']    = $id;
                    $data['id_periode']   = $idPeriode;
                    $data['id_indikator'] = $p[0];
                    $data['id_parameter'] = $p[1];
                    self::insert($data);
                }
            }
            if (isset($postData['cb'])) {
                $id_cb = $postData['cb'];
                if ($id_cb) {
                    foreach ($id_cb as $id_p) {
                        $p = preg_split('/\\./', $id_p);

                        $data['id_subjek']    = $id;
                        $data['id_periode']   = $idPeriode;
                        $data['id_indikator'] = $p[0];
                        $data['id_parameter'] = $p[1];
                        self::insert($data);
                    }
                }
            }

            if (isset($postData['ia'])) {
                $id_ia = $postData['ia'];

                foreach ($id_ia as $id_p) {
                    if ($id_p != '') {
                        unset($data);
                        $indikator = key($id_ia);
                        $dx        = AnalisisParameter::firstOrCreate(['jawaban' => $id_p, 'id_indikator' => $indikator]);

                        unset($data);
                        $data['id_parameter'] = $dx->id;
                        $data['id_indikator'] = $indikator;
                        $data['id_subjek']    = $id;
                        $data['id_periode']   = $idPeriode;
                        self::create($data);
                    }
                    next($id_ia);
                }
            }
            if (isset($postData['it'])) {
                $id_it = $postData['it'];

                foreach ($id_it as $id_p) {
                    if ($id_p != '') {
                        unset($data);
                        $indikator = key($id_it);
                        $dx        = AnalisisParameter::firstOrCreate(['jawaban' => $id_p, 'id_indikator' => $indikator]);

                        $data2['id_parameter'] = $dx->id;
                        $data2['id_indikator'] = $indikator;
                        $data2['id_subjek']    = $id;
                        $data2['id_periode']   = $idPeriode;
                        self::create($data2);
                    }
                    next($id_it);
                }
            }

            $sql = 'SELECT SUM(i.bobot * nilai) as jml FROM analisis_respon r LEFT JOIN analisis_indikator i ON r.id_indikator = i.id LEFT JOIN analisis_parameter z ON r.id_parameter = z.id WHERE r.id_subjek = ? AND i.act_analisis=1 AND r.id_periode=? ';
            $dx  = (array) DB::select($sql, [$id, $idPeriode])[0];

            $upx['id_master']  = $idMaster;
            $upx['akumulasi']  = 0 + $dx['jml'];
            $upx['id_subjek']  = $id;
            $upx['id_periode'] = $idPeriode;
            $upx['config_id']  = identitas('id');
            AnalisisResponHasil::where('id_subjek', $id)->where('id_periode', $idPeriode)->delete();
            AnalisisResponHasil::create($upx);
        }
    }

    public function import_respon($idMaster, $periode, $subjekTipe, $op = 0)
    {
        $per    = $periode;
        $subjek = $subjekTipe;
        $mas    = $idMaster;
        $key    = ($per + 3) * ($mas + 7) * ($subjek * 3);
        $key    = 'AN' . $key;
        $respon = [];

        $indikator = AnalisisIndikator::where('id_master', $idMaster)->orderBy('id')->get()->toArray();

        try {
            if ($_FILES['respon']['type'] != 'application/vnd.ms-excel') {
                return [
                    'success' => false,
                    'message' => 'File yang diunggah harus berformat .xls',
                ];
            }
            $data  = new Spreadsheet_Excel_Reader($_FILES['respon']['tmp_name']);
            $s     = 0;
            $baris = $data->rowcount($s);
            $kolom = $data->colcount($s);

            $ketemu = 0;

            for ($b = 1; $b <= $baris; $b++) {
                for ($k = 1; $k <= $kolom; $k++) {
                    $isi = $data->val($b, $k, $s);
                    // ketemu njuk stop
                    if ($isi == $key) {
                        $br = $b + 1;
                        $kl = $k + 1;

                        $b      = $baris + 1;
                        $k      = $kolom + 1;
                        $ketemu = 1;
                    }
                }
            }
            if ($ketemu == 1) {
                $dels = '';
                $true = 0;

                for ($i = $br; $i <= $baris; $i++) {
                    $id_subjek = $data->val($i, $kl - 1, $s);

                    $j = $kl;

                    foreach ($indikator as $indi) {
                        $isi = $data->val($i, $j, $s);
                        if ($isi != '') {
                            $true = 1;
                        }

                        $j++;
                    }
                    if ($true == 1) {
                        $dels .= $id_subjek . ',';
                        $true = 0;
                    }
                }

                $dels .= '9999999';
                //cek ada row
                self::where('id_periode', $per)->whereRaw("id_subjek in({$dels})")->delete();
                $dels = '';

                for ($i = $br; $i <= $baris; $i++) {
                    $id_subjek = $data->val($i, $kl - 1, $s);
                    if (strlen($id_subjek) > 14 && $subjek == 1) {
                        $id_subjek = PendudukHidup::select(['id'])->where('nik', $id_subjek)->first()?->id ?? null;
                    } elseif ($subjek == 3) {
                        // sasaran rumah tangga, simpan id, bukan nomor rumah tangga
                        $id_subjek = Rtm::select('id')->where('id_rtm', $id_subjek)->first()?->id ?? null;
                    }

                    $j   = $kl + $op;
                    $all = '';

                    foreach ($indikator as $indi) {
                        $isi = $data->val($i, $j, $s);
                        if ($isi != '') {
                            if ($indi['id_tipe'] == 1) {
                                $param = AnalisisParameter::where('id_indikator', $indi['id'])
                                    ->where(static function ($query) use ($isi) {
                                        $query->where('kode_jawaban', $isi)->orWhere('jawaban', $isi);
                                    })->first()->toArray();
                                if ($param) {
                                    $in_param = $param['id'];
                                } elseif ($isi == '') {
                                    $in_param = 0;
                                } else {
                                    $in_param = -1;
                                }

                                $respon[] = [
                                    'id_parameter' => $in_param,
                                    'id_indikator' => $indi['id'],
                                    'id_subjek'    => $id_subjek,
                                    'id_periode'   => $per,
                                ];
                            } elseif ($indi['id_tipe'] == 2) {
                                $this->respon_checkbox($indi, $isi, $id_subjek, $per, $respon);
                            } else {
                                $param = AnalisisParameter::where('id_indikator', $indi['id'])->where('jawaban', $isi)->first()->toArray();

                                // apakah sdh ada jawaban yg sama
                                if ($param) {
                                    $in_param = $param['id'];
                                } else {
                                    $parameter['jawaban']      = $isi;
                                    $parameter['id_indikator'] = $indi['id'];
                                    $parameter['asign']        = 0;
                                    $parameter['config_id']    = identitas('id');
                                    AnalisisParameter::create($parameter);

                                    $param    = AnalisisParameter::where('id_indikator', $indi['id'])->where('jawaban', $isi)->first()->toArray();
                                    $in_param = $param['id'];
                                }

                                $respon[] = [
                                    'id_parameter' => $in_param,
                                    'id_indikator' => $indi['id'],
                                    'id_subjek'    => $id_subjek,
                                    'id_periode'   => $per,
                                ];
                            }
                        }

                        $j++;
                    }
                }

                if (count($respon) > 0) {
                    AnalisisRespon::insert($respon);
                } else {
                    return [
                        'success' => false,
                        'message' => 'Tidak ada data yang diimpor',
                    ];
                }
            }

            $this->pre_update($idMaster, $per);
        } catch (Exception $e) {
            return [
                'success' => false,
                'pesan'   => $e->getMessage(),
            ];
        }

        return [
            'success' => true,
            'message' => 'Data berhasil diimpor',
        ];
    }

    private function respon_checkbox($indi, $isi, $id_subjek, $per, &$respon): void
    {
        $list_isi = explode(',', $isi);

        foreach ($list_isi as $isi_ini) {
            if ($indi['is_teks'] == 1) {
                // Isian sebagai teks pilihan bukan kode
                $teks  = strtolower($isi_ini);
                $param = AnalisisParameter::where('id_indikator', $indi['id'])->whereRaw("LOWER(jawaban) = '{$teks}'")->first()->toArray();
            } else {
                $param = AnalisisParameter::where('id_indikator', $indi['id'])->where('kode_jawaban', $isi_ini)->first()->toArray();
            }
            if ($param['id'] != '') {
                $in_param = $param['id'];
                $respon[] = [
                    'id_parameter' => $in_param,
                    'id_indikator' => $indi['id'],
                    'id_subjek'    => $id_subjek,
                    'id_periode'   => $per,
                    'config_id'    => identitas('id'),
                ];
            }
        }
    }

    public function pre_update($idMaster, $per): void
    {
        $data = AnalisisRespon::selectRaw('distinct(id_subjek) as id')->where('id_periode', $per)->get()->toArray();

        AnalisisResponHasil::where('id_subjek', 0)->delete();
        AnalisisRespon::where('id_subjek', 0)->delete();
        AnalisisResponHasil::where('id_periode', $per)->delete();

        $counter = count($data);

        for ($i = 0; $i < $counter; $i++) {
            $sql = 'SELECT SUM(i.bobot * nilai) as jml FROM analisis_respon r LEFT JOIN analisis_indikator i ON r.id_indikator = i.id LEFT JOIN analisis_parameter z ON r.id_parameter = z.id WHERE r.id_subjek = ? AND i.act_analisis=1 AND r.id_periode=?';
            $dx  = (array) DB::select($sql, [$data[$i]['id'], $per])[0];

            $upx[$i]['id_master']  = $idMaster;
            $upx[$i]['akumulasi']  = 0 + $dx['jml'];
            $upx[$i]['id_subjek']  = $data[$i]['id'];
            $upx[$i]['id_periode'] = $per;
            $upx[$i]['config_id']  = identitas('id');
        }
        if (@$upx) {
            AnalisisResponHasil::insert($upx);
        }
    }
}
