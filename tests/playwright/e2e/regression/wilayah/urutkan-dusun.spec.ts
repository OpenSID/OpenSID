// Issue: #10626 — Urutkan dusun di wilayah administratif
// https://github.com/OpenSID/OpenSID/issues/10626
import { test, expect } from '../../../utils/regressionTest';

test.describe('Bug/error: urutkan dusun #10626', () => {
  // KARANTINA (utang teknis triase) — rekaman codegen asli hanya meng-klik
  // gridcell ber-nama kosong tanpa assertion apa pun (hijau palsu), dan tidak
  // pernah benar-benar mengubah urutan. Fitur urut dusun dipersist lewat
  // drag-and-drop jQuery UI pada `#dragable` → POST `wilayah.tukar`
  // (param `data[]` = daftar id terurut), DAN endpoint dilindungi CSRF
  // (csrf_protection = true).
  //
  // Penulisan ulang deterministik yang benar harus:
  //   1) baca urutan awal `data-id` dari `#tabeldata tbody tr.dragable-handle`;
  //   2) ubah urutan secara stabil — DnD via mouse-events (sortable jQuery UI
  //      tidak merespons drag HTML5), ATAU request POST `wilayah.tukar`
  //      yang menyertakan token CSRF;
  //   3) muat ulang `wilayah`, pastikan urutan berubah sesuai harapan; dan
  //   4) PULIHKAN urutan semula (uji destruktif → kembalikan dirinya, §4).
  //
  // Di-`fixme` agar terlacak sebagai utang, bukan dihapus diam-diam atau
  // dibiarkan sebagai tes hijau-palsu.
  test.fixme('fix: ubah urutan dusun dipersist (perlu rewrite deterministik)', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10626',
    },
  }, async ({ page }) => {
    await page.goto('wilayah');
    const rows = page.locator('#tabeldata tbody tr.dragable-handle');
    await expect(rows.first()).toBeVisible({ timeout: 15000 });
    // TODO(triase): implementasikan reorder deterministik + verifikasi + pulihkan.
  });
});
