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

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class PlaywrightController extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();

        if (! in_array(ENVIRONMENT, ['development', 'testing']) && ! config_item('demo_mode')) {
            show_404();
        }
    }

    public function artisan()
    {
        $request = request();

        $this->validated($request, [
            'command'    => 'required|string',
            'parameters' => 'nullable|array',
        ]);

        return $this->handleWithJsonResponse(static function () use ($request) {
            Artisan::call(
                command: $request->input('command'),
                parameters: $request->input('parameters', [])
            );

            return Artisan::output();
        });
    }

    public function user()
    {
        return $this->jsonResponse(auth()->user()?->setHidden([])->setVisible([]));
    }

    public function query()
    {
        $request = request();

        $this->validated($request, [
            'connection' => 'nullable|string',
            'query'      => 'required|string',
            'bindings'   => 'nullable|array',
            'unprepared' => 'nullable|boolean',
        ]);

        return $this->handleWithJsonResponse(static function () use ($request) {
            $connection = DB::connection($request->input('connection'));
            $query      = $request->input('query');
            $bindings   = $request->input('bindings', []);
            $unprepared = $request->boolean('unprepared', false);

            $success = $unprepared
                ? $connection->unprepared($query)
                : $connection->statement($query, $bindings);

            return ['success' => $success];
        });
    }

    public function select()
    {
        $request = request();

        $this->validated($request, [
            'connection' => 'nullable|string',
            'query'      => 'string|required',
            'bindings'   => 'nullable|array',
        ]);

        return $this->handleWithJsonResponse(static function () use ($request) {
            $connection = DB::connection($request->input('connection'));
            $query      = $request->input('query');
            $bindings   = $request->input('bindings', []);

            return $connection->select($query, $bindings);
        });
    }

    protected function jsonResponse($data, int $status = 200)
    {
        return response(json_encode($data), $status)
            ->header('Content-Type', 'application/json')
            ->send();
    }

    protected function handleWithJsonResponse(Closure $callback)
    {
        try {
            return $this->jsonResponse($callback());
        } catch (Exception $e) {
            logger()->error($e);

            return $this->jsonResponse([
                'success' => false,
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
