// Golden path test untuk modul Suplemen — digunakan sebagai safety net konversi CI3 → Laravel.
// Harus LULUS sebelum konversi (CI3) DAN sesudah konversi (Laravel).
import { test, expect } from '../../../utils/regressionTest';

test.describe('Golden path: Modul Suplemen', () => {
  test('halaman daftar suplemen memuat DataTable dengan data', async ({ page }) => {
    test.setTimeout(30000);

    await page.goto('suplemen');
    await expect(page.locator('h1')).toContainText('Data Suplemen');

    // Tunggu DataTable selesai load (minimal satu baris)
    const tbody = page.locator('#tabeldata tbody');
    await expect(tbody.locator('tr').first()).toBeVisible({ timeout: 15000 });

    // Kolom Sasaran dan tombol aksi harus ada
    await expect(page.locator('#tabeldata tbody tr').first()).toBeVisible();
  });

  test('halaman rincian suplemen memuat daftar terdata', async ({ page }) => {
    test.setTimeout(30000);

    await page.goto('suplemen');

    // Klik link Rincian pertama yang ditemukan
    const rincianLink = page.locator('a[href*="suplemen/rincian/"]').first();
    await expect(rincianLink).toBeVisible({ timeout: 15000 });
    const href = await rincianLink.getAttribute('href');
    const id = href?.split('/').pop();

    await page.goto(`suplemen/rincian/${id}`);
    await expect(page.locator('h1')).toContainText('Data Suplemen');

    // Tabel terdata harus ada
    const tabelTerdata = page.locator('#tabeldata');
    await expect(tabelTerdata).toBeVisible({ timeout: 15000 });
  });

  test('halaman impor data suplemen memuat form upload', async ({ page }) => {
    test.setTimeout(30000);

    await page.goto('suplemen');

    // Cari tombol Impor (hanya muncul pada suplemen non-OpenKab)
    const imporLink = page.locator('a[href*="suplemen/impor_data/"]').first();
    await expect(imporLink).toBeVisible({ timeout: 15000 });
    const href = await imporLink.getAttribute('href');
    const id = href?.split('/').pop();

    await page.goto(`suplemen/impor_data/${id}`);
    await expect(page.locator('h1')).toContainText('Data Suplemen');

    // Form upload harus ada
    await expect(page.locator('form[enctype="multipart/form-data"]')).toBeVisible();
    await expect(page.locator('#file')).toBeAttached();
    await expect(page.locator('#id_suplemen')).toHaveValue(id!);

    // Link download format impor harus ada
    await expect(page.getByRole('link', { name: /Contoh Format Impor/i })).toBeVisible();
  });
});
