// Issue: #6411 — Gagal Impor Data Suplemen — notifikasi tidak muncul setelah impor
// https://github.com/OpenSID/premium/issues/6411
//
// Root cause: set_session('notif', $notif) tulis ke CI3 flash session,
//   tapi view baca session('notif') via Laravel helper → null → tabel notifikasi tidak muncul.
// Fix: session()->flash('notif', $notif) di SuplemenController::impor().
import { test, expect } from '../../../utils/regressionTest';
import path from 'path';

test.describe('Bug/error: Gagal Impor Data Suplemen — notifikasi hasil impor tidak muncul #6411', () => {
    test('fix: setelah impor, halaman harus menampilkan tabel notifikasi hasil impor', {
        annotation: {
            type: 'issue',
            description: 'https://github.com/OpenSID/premium/issues/6411',
        },
    }, async ({ page }) => {
        // Navigasi langsung ke form impor suplemen ID=1 (ada di seed — "Conoh Suplemen")
        await page.goto('suplemen/impor_data/1');
        await expect(page.locator('h1')).toContainText('Data Suplemen');
        await expect(page.locator('#file')).toBeAttached();

        // Upload template format impor (berisi header dan '###' — tidak ada baris data valid)
        // Yang diuji bukan isi hasil impor, tapi kemunculan tabel notifikasi setelah redirect
        const filePath = path.resolve(__dirname, '../../../storage/fixtures/format-impor-suplemen.xlsx');
        await page.locator('#file').setInputFiles(filePath);
        await expect(page.locator('#file_path')).toHaveValue(/format-impor-suplemen/, { timeout: 5000 });

        // Submit form impor
        await page.getByRole('button', { name: /simpan/i }).click();

        // Setelah redirect kembali ke impor_data/1, tabel notifikasi HARUS tampil
        // Bug (sebelum fix): set_session() tulis ke CI3 flash, view baca session() Laravel → null → tabel tidak muncul
        // Fix: session()->flash() tulis ke Laravel flash → session('notif') berisi array hasil impor → tabel muncul
        await expect(
            page.locator('dt').filter({ hasText: 'Jumlah Data Gagal' })
        ).toBeVisible({ timeout: 15000 });
        await expect(
            page.locator('dt').filter({ hasText: 'Total Data Berhasil' })
        ).toBeVisible();
    });
});
