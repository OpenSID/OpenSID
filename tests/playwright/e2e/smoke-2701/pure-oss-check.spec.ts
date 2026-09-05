import { test, expect } from '@playwright/test';
import fs from 'fs';
import path from 'path';

/**
 * Skenario 2 (Fase 2, OpenSID/OpenSID#11931): instalasi baru TIDAK memuat
 * Anjungan/Pelanggan/BukuTamu/DTSEN -- tak ada folder Modules/-nya, tak ada
 * entri menu terkait. Ini memverifikasi tujuan refaktor #1 ("Umum murni OSS").
 */
const MODUL_ADDON = ['Anjungan', 'Pelanggan', 'BukuTamu', 'DTSEN'];

test.describe('Cek murni-OSS', () => {
  test('tidak ada folder Modules/ add-on di tree', () => {
    const root = path.resolve(__dirname, '../../../..');
    for (const modul of MODUL_ADDON) {
      const modulPath = path.join(root, 'Modules', modul);
      expect(fs.existsSync(modulPath), `Modules/${modul} tidak boleh ada di instalasi baru`).toBe(false);
    }
  });

  test('menu admin tidak menampilkan entri modul add-on', async ({ page }) => {
    await page.goto('/index.php/siteman');
    await page.getByPlaceholder('Nama pengguna').fill(process.env.PLAYWRIGHT_AUTH_USERNAME || 'admin');
    await page.getByPlaceholder('Kata sandi').fill(process.env.PLAYWRIGHT_AUTH_PASSWORD || 'Admin$123');
    await page.getByRole('button', { name: 'Masuk' }).first().click();
    await expect(page.getByPlaceholder('Nama pengguna')).toHaveCount(0);

    const bodyText = await page.locator('body').innerText();
    for (const modul of ['Anjungan', 'Buku Tamu', 'DTSEN']) {
      expect(bodyText, `menu "${modul}" tidak boleh muncul tanpa add-on terpasang`).not.toContain(modul);
    }
  });
});
