// Issue: #10625 — Margin kertas custom tidak sama saat preview surat
// https://github.com/OpenSID/OpenSID/issues/10625
import { test, expect } from '../../../utils/regressionTest';


// Surat jenis=4 (surat desa, bukan sistem) dari seed.sql — dapat diedit
const SURAT_MASTER_ID = 215;
const SURAT_URL = 'surat-keterangan-kurang-mampu-215';

test.describe('Bug/error: Margin Kertas Custom tidak sama saat preview Surat #10625', () => {
  test('fix: Margin Kertas Custom tidak sama saat preview Surat', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10625',
    },
  }, async ({ page }) => {
    test.setTimeout(90000);

    const MARGIN_KUSTOM = {
      atas: '2.5',
      bawah: '2.1',
      kiri: '3.2',
      kanan: '3.3',
    };

    // Kembalikan ke margin global (cleanup)
    const restoreGlobalMargin = async () => {
      if (page.isClosed()) return;
      try {
        await page.goto(`/surat_master/form/${SURAT_MASTER_ID}`);
        await page.waitForLoadState('networkidle');
        await page.evaluate(() => {
          const radio = document.querySelector('input[name="margin_global"][value="1"]') as HTMLInputElement;
          radio.checked = true;
          (window as any).$(radio).trigger('change');
        });
        await page.evaluate(() => {
          if (typeof (window as any).tinymce !== 'undefined' && (window as any).tinymce.activeEditor) {
            (window as any).tinymce.triggerSave();
          }
          (window as any).$('#validasi').submit();
        });
        await expect(page.locator('.swal2-popup')).toBeVisible({ timeout: 10000 });
        await page.waitForURL(/surat_master(?!.*form)/, { timeout: 10000 });
      } catch {
        // abaikan error cleanup
      }
    };

    try {
      // ── BAGIAN 1: Ubah margin menjadi kustom di surat_master ────────────────
      // Navigasi langsung ke form surat desa (jenis=4, viewOnly=false)

      await page.goto(`/surat_master/form/${SURAT_MASTER_ID}`);
      await page.waitForLoadState('networkidle');

      // Atur margin kustom via DOM langsung:
      // • Centang radio "Tidak" (value="0") → margin_global=0 terkirim via POST
      // • Isi nilai input di #manual_margin → terkirim via FormData meski div hidden
      // • Tidak perlu menampilkan #manual_margin — FormData selalu menyertakan hidden inputs
      await page.evaluate((m: Record<string, string>) => {
        const radioTidak = document.querySelector('input[name="margin_global"][value="0"]') as HTMLInputElement;
        const radioYa    = document.querySelector('input[name="margin_global"][value="1"]') as HTMLInputElement;
        if (radioYa)    radioYa.checked    = false;
        if (radioTidak) radioTidak.checked = true;

        (['atas', 'bawah', 'kiri', 'kanan'] as const).forEach((key) => {
          const input = document.querySelector(`#manual_margin input[name="${key}"]`) as HTMLInputElement;
          if (input) input.value = m[key];
        });
      }, MARGIN_KUSTOM);

      // Submit via jQuery (form surat_master menggunakan AJAX + SweetAlert, bukan redirect biasa)
      await page.evaluate(() => {
        if (typeof (window as any).tinymce !== 'undefined' && (window as any).tinymce.activeEditor) {
          (window as any).tinymce.triggerSave();
        }
        (window as any).$('#validasi').submit();
      });

      // Tunggu SweetAlert sukses dan redirect ke list
      await expect(page.locator('.swal2-popup')).toBeVisible({ timeout: 10000 });
      await page.waitForURL(/surat_master(?!.*form)/, { timeout: 10000 });

      // ── BAGIAN 2: Buat surat dan verifikasi margin di konsep ────────────────

      // Navigasi langsung ke form surat (bypass DataTables list)
      await page.goto(`/surat/form/${SURAT_URL}`);
      await page.waitForLoadState('networkidle');
      await page.waitForURL(/.*\/surat\/form\/.*/);

      // Pilih penduduk via Select2
      await page.locator('.select2-selection').first().click();
      await page.locator('.select2-search__field').fill('a');
      await expect(page.locator('.select2-results__option--highlighted')).toBeVisible({ timeout: 15000 });
      await page.locator('.select2-results__option--highlighted').click();

      // Isi field Keperluan (required di surat ini)
      await page.getByRole('textbox', { name: 'Keperluan' }).fill('Test margin kertas');

      // Submit ke pratinjau — tombol teks "Lanjut"
      await page.getByRole('button', { name: /Lanjut/i }).click();
      await expect(page).toHaveURL(/.*surat\/pratinjau\/.*/, { timeout: 15000 });

      // Buka modal pengaturan — elemen <a>, bukan <button>
      await page.locator('a#pengaturan').click();
      await expect(page.locator('#modal-pengaturan')).toBeVisible({ timeout: 10000 });

      // Verifikasi nilai margin di modal
      // Nama field di konsep.blade.php: surat_margin[atas], surat_margin[bawah], dst.
      const modal = page.locator('#modal-pengaturan');
      await expect(modal.locator('input[name="surat_margin[atas]"]')).toHaveValue(MARGIN_KUSTOM.atas);
      await expect(modal.locator('input[name="surat_margin[bawah]"]')).toHaveValue(MARGIN_KUSTOM.bawah);
      await expect(modal.locator('input[name="surat_margin[kiri]"]')).toHaveValue(MARGIN_KUSTOM.kiri);
      await expect(modal.locator('input[name="surat_margin[kanan]"]')).toHaveValue(MARGIN_KUSTOM.kanan);

    } finally {
      // Selalu kembalikan ke margin global agar test lain tidak terpengaruh
      await restoreGlobalMargin();
    }
  });
});
