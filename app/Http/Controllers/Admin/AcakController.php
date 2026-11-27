<?php

namespace App\Http\Controllers\Admin;

use App\Attributes\AdminModul;
use App\Exceptions\Acak\MultiTenantNotSupportedException;
use App\Http\Controllers\Controller;
use App\Models\LogAcak;
use App\Models\Modul;
use App\Services\Acak\AcakOptions;
use App\Services\Acak\AcakResult;
use App\Services\Acak\AcakService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

#[AdminModul(modul: 'pengaturan', subModul: 'acak-data')]
class AcakController extends Controller
{
    private const SEED_DEFAULT = 'opensid-demo';

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $allowed = is_super_admin()
                || (int) setting('penggunaan_server') === 6;

            abort_unless($allowed, 403, 'Fitur acak data hanya tersedia untuk super admin.');
            abort_unless((bool) Modul::where('slug', 'acak-data')->value('aktif'), 404);

            return $next($request);
        });
    }

    public function index(AcakService $acak): View
    {
        if ($acak->isMultiTenant()) {
            return view('admin.acak.multi_tenant');
        }

        return view('admin.acak.konfirmasi', ['seed' => $this->previousSeed()]);
    }

    public function jalankan(Request $request, AcakService $acak): JsonResponse
    {
        if ($acak->isMultiTenant()) {
            return response()->json([
                'status'  => false,
                'message' => MultiTenantNotSupportedException::PESAN,
            ], 422);
        }

        $seed = trim((string) $request->query('seed', '')) ?: $this->previousSeed();
        $this->storeSeed($seed);

        $options = new AcakOptions(
            seed: $seed,
            photoMode: (string) $request->query('foto', 'blur'),
            fotoKontenMode: (string) $request->query('foto_konten', 'watermark'),
            suratMode: (string) $request->query('surat', 'sintetis'),
        );

        $log = LogAcak::create([
            'seed'        => $options->seed,
            'foto'        => $options->photoMode,
            'foto_konten' => $options->fotoKontenMode,
            'surat'       => $options->suratMode,
            'status'      => LogAcak::STATUS_PROSES,
            'step'        => 'Memulai proses…',
        ]);

        $this->luncurkanJob((int) $log->id);

        return response()->json([
            'status' => true,
            'id'     => (int) $log->id,
        ]);
    }

    public function status(Request $request): JsonResponse
    {
        $id  = (int) $request->query('id');
        $log = $id > 0 ? LogAcak::find($id) : LogAcak::latest()->first();

        if ($log === null) {
            return response()->json(['status' => null, 'message' => 'Tidak ada proses acak.']);
        }

        $status  = (int) $log->status;
        $payload = [
            'status'  => $status,
            'step'    => $log->step,
            'message' => match ($status) {
                LogAcak::STATUS_SELESAI    => 'Selesai',
                LogAcak::STATUS_GAGAL      => 'Terjadi kesalahan: ' . ($log->error ?: 'proses acak gagal'),
                LogAcak::STATUS_DIBATALKAN => 'Proses dibatalkan.',
                default                    => $log->step ?: 'Sedang dalam proses…',
            },
        ];

        if ($status === LogAcak::STATUS_SELESAI) {
            $payload['redirect'] = route('acak.hasil') . '?id=' . $log->id;
        }

        return response()->json($payload);
    }

    public function hasil(Request $request): View|RedirectResponse
    {
        $id  = (int) $request->query('id');
        $log = $id > 0
            ? LogAcak::find($id)
            : LogAcak::where('status', LogAcak::STATUS_SELESAI)->latest()->first();

        if ($log === null || (int) $log->status !== LogAcak::STATUS_SELESAI || empty($log->hasil)) {
            return redirect()->route('acak.index');
        }

        $data = json_decode((string) $log->hasil, true);
        if (! is_array($data)) {
            return redirect()->route('acak.index');
        }

        return view('admin.acak.hasil', ['result' => AcakResult::fromArray($data)]);
    }

    /**
     * Job dijalankan detached (index.php job acak_data) agar berjalan di luar
     * request web dan tidak kena batas fastcgi_read_timeout.
     */
    private function luncurkanJob(int $id): void
    {
        $index  = escapeshellarg(FCPATH . 'index.php');
        $binary = escapeshellarg(php_cli_binary());

        shell_exec('nohup ' . $binary . " -f {$index} job acak_data " . $id . ' > /dev/null 2>&1 &');
    }

    private function previousSeed(): string
    {
        $configId = (int) (identitas('id') ?: DB::table('config')->value('id'));
        $seed     = DB::table('setting_aplikasi')
            ->where('config_id', $configId)
            ->where('key', 'acak_seed')
            ->value('value');

        return ($seed !== null && $seed !== '') ? (string) $seed : self::SEED_DEFAULT;
    }

    private function storeSeed(string $seed): void
    {
        $configId = (int) (identitas('id') ?: DB::table('config')->value('id'));

        DB::table('setting_aplikasi')->updateOrInsert(
            ['config_id' => $configId, 'key' => 'acak_seed'],
            ['value' => $seed, 'judul' => 'Seed Acak Terakhir', 'kategori' => 'sistem']
        );
    }
}
