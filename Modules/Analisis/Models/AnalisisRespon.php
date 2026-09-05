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

namespace Modules\Analisis\Models;

use App\Libraries\SpreadsheetExcelReader;
use App\Models\BaseModel;
use App\Models\PendudukHidup;
use App\Models\Rtm;
use App\Traits\ConfigId;
use Exception;
use Illuminate\Support\Facades\DB;

defined('BASEPATH') || exit('No direct script access allowed');

class AnalisisRespon extends BaseModel
{
    use ConfigId;

    public $timestamps = false;
    public $subjekTipe;

    /**
     * {@inheritDoc}
     */
    protected $table = 'analisis_respon';

    protected $guarded = [];

    public static function updateKuisioner($idMaster, $idPeriode, $postData, $id, $subjekTipe): void
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
                    $p           = preg_split('/\\./', $id_p);
                    $indikatorId = $p[0];
                    $paramRaw    = $p[1] ?? null;

                    // Pastikan id_parameter valid; jika tidak ada, coba cari berdasarkan kode_jawaban/jawaban
                    $param = null;
                    if (is_numeric($paramRaw)) {
                        $param = AnalisisParameter::find($paramRaw);
                    }
                    // Pastikan indikator ada sebelum membuat parameter baru
                    if (! AnalisisIndikator::where('id', $indikatorId)->exists()) {
                        log_message('error', "AnalisisRespon::updateKuisioner - indikator {$indikatorId} tidak ditemukan untuk master {$idMaster}; melewatkan parameter {$paramRaw}");

                        continue;
                    }
                    if (! $param && $paramRaw !== null) {
                        $param = AnalisisParameter::where('id_indikator', $indikatorId)
                            ->where(static function ($query) use ($paramRaw) {
                                $query->where('kode_jawaban', $paramRaw)->orWhere('jawaban', $paramRaw);
                            })->first();
                    }
                    if (! $param && $paramRaw !== null) {
                        // Buat parameter baru jika memang tidak ditemukan
                        $param = AnalisisParameter::create(['jawaban' => $paramRaw, 'id_indikator' => $indikatorId, 'asign' => 0, 'config_id' => identitas('id')]);
                    }

                    if ($param) {
                        $data['id_subjek']    = $id;
                        $data[$subjekTipe]    = $id;
                        $data['id_periode']   = $idPeriode;
                        $data['id_indikator'] = $indikatorId;
                        $data['id_parameter'] = $param->id;
                        $data['config_id']    = identitas('id');
                        self::insert($data);
                    }
                }
            }
            if (isset($postData['cb'])) {
                $id_cb = $postData['cb'];
                if ($id_cb) {
                    foreach ($id_cb as $id_p) {
                        $p           = preg_split('/\\./', $id_p);
                        $indikatorId = $p[0];
                        $paramRaw    = $p[1] ?? null;

                        $param = null;
                        if (is_numeric($paramRaw)) {
                            $param = AnalisisParameter::find($paramRaw);
                        }
                        // Pastikan indikator ada sebelum membuat parameter baru
                        if (! AnalisisIndikator::where('id', $indikatorId)->exists()) {
                            log_message('error', "AnalisisRespon::updateKuisioner - indikator {$indikatorId} tidak ditemukan untuk master {$idMaster}; melewatkan parameter {$paramRaw}");

                            continue;
                        }
                        if (! $param && $paramRaw !== null) {
                            $param = AnalisisParameter::where('id_indikator', $indikatorId)
                                ->where(static function ($query) use ($paramRaw) {
                                    $query->where('kode_jawaban', $paramRaw)->orWhere('jawaban', $paramRaw);
                                })->first();
                        }
                        if (! $param && $paramRaw !== null) {
                            $param = AnalisisParameter::create(['jawaban' => $paramRaw, 'id_indikator' => $indikatorId, 'asign' => 0, 'config_id' => identitas('id')]);
                        }

                        if ($param) {
                            $data['id_subjek']    = $id;
                            $data[$subjekTipe]    = $id;
                            $data['id_periode']   = $idPeriode;
                            $data['id_indikator'] = $indikatorId;
                            $data['id_parameter'] = $param->id;
                            $data['config_id']    = identitas('id');
                            self::insert($data);
                        }
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
                        $data[$subjekTipe]    = $id;
                        $data['id_periode']   = $idPeriode;
                        $data['config_id']    = identitas('id');
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
                        $data2[$subjekTipe]    = $id;
                        $data2['id_periode']   = $idPeriode;
                        $data2['config_id']    = identitas('id');
                        self::create($data2);
                    }
                    next($id_it);
                }
            }

            $jml = DB::table('analisis_respon as r')
                ->selectRaw('SUM(i.bobot * nilai) as jml')
                ->leftJoin('analisis_indikator as i', 'r.id_indikator', '=', 'i.id')
                ->leftJoin('analisis_parameter as z', 'r.id_parameter', '=', 'z.id')
                ->where('r.config_id', identitas('id'))
                ->where(static fn ($query) => $query->where('r.id_subjek', $id)->orWhere("r.{$subjekTipe}", $id))
                ->where('i.act_analisis', 1)
                ->where('r.id_periode', $idPeriode)
                ->value('jml');

            $upx['id_master']  = $idMaster;
            $upx['akumulasi']  = 0 + $jml;
            $upx['id_subjek']  = $id;
            $upx[$subjekTipe]  = $id;
            $upx['id_periode'] = $idPeriode;
            $upx['config_id']  = identitas('id');

            AnalisisResponHasil::where(static fn ($query) => $query->where('id_subjek', $id)->orWhere($subjekTipe, $id))->where('id_periode', $idPeriode)->delete();
            AnalisisResponHasil::create($upx);
        }
    }

    public function indikator()
    {
        return $this->belongsTo(AnalisisIndikator::class, 'id_indikator');
    }

    public function import_respon($idMaster, $periode, $subjekTipe, $op, $mapSubjek)
    {
        $configID = identitas('id');
        $per      = $periode;
        $subjek   = $subjekTipe;
        $mas      = $idMaster;
        $key      = ($per + 3) * ($mas + 7) * ($subjek * 3);
        $key      = 'AN' . $key;
        $respon   = [];

        $indikator = AnalisisIndikator::where('id_master', $idMaster)->orderBy('id')->get()?->toArray();

        try {
            if ($_FILES['respon']['type'] != 'application/vnd.ms-excel') {
                return [
                    'success' => false,
                    'message' => 'File yang diunggah harus berformat .xls',
                ];
            }
            $data  = new SpreadsheetExcelReader($_FILES['respon']['tmp_name']);
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
                // Gunakan array untuk menyimpan id_subjek yang akan dihapus
                $id_subjek_list = [];
                $true           = 0;

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
                        // Simpan ke array,
                        $id_subjek_list[] = $id_subjek;
                        $true             = 0;
                    }
                }

                // Hapus hanya data yang sesuai dengan master dan periode ini
                // filter berdasarkan id_master melalui relasi indikator
                if (! empty($id_subjek_list)) {
                    self::where('id_periode', $per)
                        ->whereIn('id_subjek', $id_subjek_list)
                        ->whereHas('indikator', static function ($query) use ($mas) {
                            $query->where('id_master', $mas);
                        })
                        ->delete();
                }

                for ($i = $br; $i <= $baris; $i++) {
                    $id_subjek = $data->val($i, $kl - 1, $s);
                    if (strlen($id_subjek) > 14 && $subjek == 1) {
                        $id_subjek = PendudukHidup::select(['id'])->where('nik', $id_subjek)->first()?->id ?? null;
                    } elseif ($subjek == 3) {
                        // sasaran rumah tangga, simpan id, bukan nomor rumah tangga
                        $id_subjek = Rtm::select('id')->where('id', $id_subjek)->first()?->id ?? null;
                    }

                    $j = $kl + $op;

                    foreach ($indikator as $indi) {
                        $isi = $data->val($i, $j, $s);
                        if ($isi != '') {
                            if ($indi['id_tipe'] == 1) {
                                $param = AnalisisParameter::where('id_indikator', $indi['id'])
                                    ->where(static function ($query) use ($isi) {
                                        $query->where('kode_jawaban', $isi)->orWhere('jawaban', $isi);
                                    })->first()?->toArray();
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
                                    $mapSubjek     => $id_subjek,
                                    'id_periode'   => $per,
                                    'config_id'    => $configID,
                                ];
                            } elseif ($indi['id_tipe'] == 2) {
                                $this->respon_checkbox($indi, $isi, $id_subjek, $per, $respon, $mapSubjek);
                            } else {
                                $param = AnalisisParameter::where('id_indikator', $indi['id'])->where('jawaban', $isi)->first()?->toArray();

                                // apakah sdh ada jawaban yg sama
                                if ($param) {
                                    $in_param = $param['id'];
                                } else {
                                    $parameter['jawaban']      = $isi;
                                    $parameter['id_indikator'] = $indi['id'];
                                    $parameter['asign']        = 0;
                                    $parameter['config_id']    = $configID;
                                    AnalisisParameter::create($parameter);

                                    $param    = AnalisisParameter::where('id_indikator', $indi['id'])->where('jawaban', $isi)->first()?->toArray();
                                    $in_param = $param['id'];
                                }

                                $respon[] = [
                                    'id_parameter' => $in_param,
                                    'id_indikator' => $indi['id'],
                                    'id_subjek'    => $id_subjek,
                                    $mapSubjek     => $id_subjek,
                                    'id_periode'   => $per,
                                    'config_id'    => $configID,
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
            logger()->error($e);

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

    public function pre_update($idMaster, $per): void
    {
        $subjekTipe = $this->subjekTipe;

        // Filter data berdasarkan id_master melalui relasi indikator
        $data = AnalisisRespon::selectRaw("DISTINCT({$subjekTipe}) as id")
            ->whereHas('indikator', static function ($query) use ($idMaster) {
                $query->where('id_master', $idMaster);
            })
            ->where('id_periode', $per)
            ->pluck('id')
            ->filter()
            ->all();

        AnalisisResponHasil::where('id_master', $idMaster)
            ->where('id_periode', $per)
            ->where(static function ($query) use ($subjekTipe) {
                $query->whereNull('id_subjek')->orWhereNull($subjekTipe);
            })
            ->delete();

        // Tambahkan filter id_periode dan whereHas untuk memastikan hanya data dari master ini yang dihapus
        AnalisisRespon::where('id_periode', $per)
            ->where(static function ($query) use ($subjekTipe) {
                $query->whereNull('id_subjek')->orWhereNull($subjekTipe);
            })
            ->whereHas('indikator', static function ($query) use ($idMaster) {
                $query->where('id_master', $idMaster);
            })
            ->delete();

        // Hapus hanya hasil yang terkait dengan id_master DAN id_periode ini
        AnalisisResponHasil::where('id_master', $idMaster)
            ->where('id_periode', $per)
            ->delete();

        if (empty($data)) {
            return;
        }

        $upx = [];

        foreach ($data as $id) {
            // Tambahkan filter id_master pada query perhitungan
            $jml = DB::table('analisis_respon as r')
                ->selectRaw('SUM(i.bobot * nilai) as jml')
                ->leftJoin('analisis_indikator as i', 'r.id_indikator', '=', 'i.id')
                ->leftJoin('analisis_parameter as z', 'r.id_parameter', '=', 'z.id')
                ->where('r.id_subjek', $id)
                ->where('i.id_master', $idMaster)  // Filter berdasarkan id_master
                ->where('i.act_analisis', 1)
                ->where('r.id_periode', $per)
                ->value('jml');

            $upx[] = [
                'id_master'  => $idMaster,
                'akumulasi'  => (float) $jml,
                'id_subjek'  => $id,
                $subjekTipe  => $id,
                'id_periode' => $per,
                'config_id'  => identitas('id'),
            ];
        }

        if ($upx) {
            AnalisisResponHasil::insert($upx);
        }
    }

    private function respon_checkbox($indi, $isi, $id_subjek, $per, &$respon, $mapSubjek): void
    {
        $list_isi = explode(',', $isi);

        foreach ($list_isi as $isi_ini) {
            if ($indi['is_teks'] == 1) {
                // Isian sebagai teks pilihan bukan kode
                $teks  = strtolower($isi_ini);
                $param = AnalisisParameter::where('id_indikator', $indi['id'])->whereRaw("LOWER(jawaban) = '{$teks}'")->first()?->toArray();
            } else {
                $param = AnalisisParameter::where('id_indikator', $indi['id'])->where('kode_jawaban', $isi_ini)->first()?->toArray();
            }
            if ($param['id'] != '') {
                $in_param = $param['id'];
                $respon[] = [
                    'id_parameter' => $in_param,
                    'id_indikator' => $indi['id'],
                    'id_subjek'    => $id_subjek,
                    $mapSubjek     => $id_subjek,
                    'id_periode'   => $per,
                    'config_id'    => identitas('id'),
                ];
            }
        }
    }
}
