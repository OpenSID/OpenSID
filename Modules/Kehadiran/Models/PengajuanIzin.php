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

namespace Modules\Kehadiran\Models;

use App\Models\BaseModel;
use App\Models\Pamong;
use App\Models\User;
use App\Traits\ConfigId;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Kehadiran\Enums\JenisIzin;
use Modules\Kehadiran\Enums\StatusApproval;

defined('BASEPATH') || exit('No direct script access allowed');

class PengajuanIzin extends BaseModel
{
    use ConfigId;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'kehadiran_pengajuan_izin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_pamong',
        'jenis_izin',
        'tanggal_mulai',
        'tanggal_selesai',
        'keterangan',
        'status_approval',
        'approved_by',
        'tanggal_approval',
        'keterangan_approval',
        'lampiran',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'tanggal_mulai'    => 'date',
        'tanggal_selesai'  => 'date',
        'tanggal_approval' => 'datetime',
        'created_at'       => 'datetime',
        'updated_at'       => 'datetime',
    ];

    /**
     * Get jenis izin options.
     */
    public static function getJenisIzinOptions(): array
    {
        return JenisIzin::all();
    }

    /**
     * Get status approval options.
     */
    public static function getStatusApprovalOptions(): array
    {
        return StatusApproval::all();
    }

    /**
     * Validasi apakah tanggal pengajuan memenuhi minimal 10 hari kerja sebelumnya.
     */
    public static function validateMinimalHariKerja(string $tanggalMulai): bool
    {
        $tanggalMulaiCarbon = Carbon::parse($tanggalMulai);
        $today              = Carbon::now();

        // Hitung hari kerja (Senin-Jumat) antara hari ini dan tanggal mulai izin
        $hariKerja   = 0;
        $currentDate = $today->copy();

        while ($currentDate->lt($tanggalMulaiCarbon)) {
            // Skip weekend (Sabtu dan Minggu)
            if ($currentDate->isWeekday()) {
                $hariKerja++;
            }
            $currentDate->addDay();
        }

        return $hariKerja >= 10;
    }

    /**
     * Check apakah ada konflik dengan pengajuan izin lain.
     */
    public static function hasConflict(int $pamongId, string $tanggalMulai, string $tanggalSelesai, ?int $excludeId = null): bool
    {
        $query = self::where('id_pamong', $pamongId)
            ->where('status_approval', '!=', StatusApproval::REJECTED)
            ->where(static function ($q) use ($tanggalMulai, $tanggalSelesai) {
                $q->whereBetween('tanggal_mulai', [$tanggalMulai, $tanggalSelesai])
                    ->orWhereBetween('tanggal_selesai', [$tanggalMulai, $tanggalSelesai])
                    ->orWhere(static function ($q2) use ($tanggalMulai, $tanggalSelesai) {
                        $q2->where('tanggal_mulai', '<=', $tanggalMulai)
                            ->where('tanggal_selesai', '>=', $tanggalSelesai);
                    });
            });

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return $query->exists();
    }

    public static function deleteFile($model, ?string $file, $deleting = false): void
    {
        if ($model->isDirty($file) || $deleting) {
            $gambar = LOKASI_PENGAJUAN_IZIN . $model->getOriginal($file);
            if (file_exists($gambar)) {
                unlink($gambar);
            }
        }
    }

    /**
     * Boot method untuk auto-generate detail records
     */
    protected static function boot()
    {
        parent::boot();

        static::created(static function ($pengajuan) {
            $pengajuan->generateDetailRecords();
        });

        static::updated(static function ($pengajuan) {
            // Jika status berubah, update detail records
            if ($pengajuan->wasChanged('status_approval')) {
                $pengajuan->updateDetailStatus();
            }
            static::deleteFile($pengajuan, 'lampiran');
        });

        static::deleting(static function ($pengajuan): void {
            static::deleteFile($pengajuan, 'lampiran', true);
        });
    }

    /**
     * Define a many-to-one relationship with Pamong.
     */
    public function pamong(): BelongsTo
    {
        return $this->belongsTo(Pamong::class, 'id_pamong', 'pamong_id');
    }

    /**
     * Define a many-to-one relationship with User (approved by).
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by', 'id');
    }

    /**
     * Define a one-to-many relationship with PengajuanIzinDetail.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function details()
    {
        return $this->hasMany(PengajuanIzinDetail::class, 'pengajuan_izin_id');
    }

    /**
     * Get durasi izin dalam hari.
     */
    public function getDurasiHariAttribute(): int
    {
        return $this->tanggal_mulai->diffInDays($this->tanggal_selesai) + 1;
    }

    /**
     * Scope untuk filter berdasarkan status approval.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status_approval', $status);
    }

    /**
     * Scope untuk filter berdasarkan pamong.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePamong($query, int $pamongId)
    {
        return $query->where('id_pamong', $pamongId);
    }

    /**
     * Scope untuk filter berdasarkan jenis izin.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeJenisIzin($query, string $jenisIzin)
    {
        return $query->where('jenis_izin', $jenisIzin);
    }

    /**
     * Scope untuk filter berdasarkan rentang tanggal.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDateRange($query, string $tanggalMulai, string $tanggalSelesai)
    {
        return $query->whereBetween('tanggal_mulai', [$tanggalMulai, $tanggalSelesai])
            ->orWhereBetween('tanggal_selesai', [$tanggalMulai, $tanggalSelesai]);
    }

    /**
     * Approve pengajuan izin.
     */
    public function approve(int $approvedBy, ?string $keterangan = null): bool
    {
        return $this->update([
            'status_approval'     => StatusApproval::APPROVED,
            'approved_by'         => $approvedBy,
            'tanggal_approval'    => now(),
            'keterangan_approval' => $keterangan,
        ]);
    }

    /**
     * Reject pengajuan izin.
     */
    public function reject(int $approvedBy, string $keterangan): bool
    {
        return $this->update([
            'status_approval'     => StatusApproval::REJECTED,
            'approved_by'         => $approvedBy,
            'tanggal_approval'    => now(),
            'keterangan_approval' => $keterangan,
        ]);
    }

    /**
     * Generate detail records untuk setiap tanggal dalam periode izin
     */
    public function generateDetailRecords()
    {
        // Hapus detail yang sudah ada (jika ada)
        $this->details()->delete();

        $period = CarbonPeriod::create($this->tanggal_mulai, $this->tanggal_selesai);

        foreach ($period as $date) {
            PengajuanIzinDetail::create([
                'config_id'         => $this->config_id,
                'pengajuan_izin_id' => $this->id,
                'tanggal'           => $date->format('Y-m-d'),
                'jenis_izin'        => $this->jenis_izin,
                'id_pamong'         => $this->id_pamong,
            ]);
        }
    }

    /**
     * Update status di detail records
     */
    public function updateDetailStatus()
    {
        $this->details()->update([
            'status' => $this->status_approval,
        ]);
    }

    public function getLinkLampiranAttribute(): ?string
    {
        if ($this->lampiran) {
            return LOKASI_UPLOAD . 'pengajuan_izin/' . $this->lampiran;
        }

        return null;
    }

    public function insertKehadiranForIzin(): void
    {
        $period = CarbonPeriod::create($this->tanggal_mulai, $this->tanggal_selesai);

        foreach ($period as $date) {
            // Cek apakah sudah ada record kehadiran untuk tanggal dan pamong ini
            $existing = Kehadiran::where('config_id', $this->config_id)
                ->where('tanggal', $date->format('Y-m-d'))
                ->where('pamong_id', $this->id_pamong)
                ->first();
            if (! $existing) {
                Kehadiran::create([
                    'tanggal'          => $date->format('Y-m-d'),
                    'pamong_id'        => $this->id_pamong,
                    'jam_masuk'        => null,
                    'jam_keluar'       => null,
                    'status_kehadiran' => $this->jenis_izin,
                ]);
            }
        }
    }
}
