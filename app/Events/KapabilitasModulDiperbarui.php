<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;

/**
 * Event netral: entitlement/langganan sebuah desa baru saja diperbarui
 * (mis. token langganan disegarkan oleh klien Layanan).
 *
 * Menggantikan kopling-terbalik langsung di mana modul Pelanggan menyentuh
 * model milik modul lain (mis. `Anjungan::where(...)->update(...)`). Kini
 * penyedia data langganan cukup men-dispatch event ini; modul yang
 * berkepentingan (mis. Anjungan) memasang listener untuk mereaktivasi
 * barisnya. Core tak tahu modul mana pun.
 */
class KapabilitasModulDiperbarui
{
    use Dispatchable;

    /**
     * @param string|null $feature Kunci fitur yang diperbarui bila diketahui
     *                             (mis. 'anjungan'); `null` = pembaruan umum.
     * @param array<string, mixed> $context Konteks tambahan opsional (mis. kode desa).
     */
    public function __construct(
        public readonly ?string $feature = null,
        public readonly array $context = [],
    ) {}
}
