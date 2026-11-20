// Issue: #11224 — Perbaiki nama hasil unduhan data wilayah administratif
// https://github.com/OpenSID/OpenSID/issues/11224
import { test, expect } from '../../../utils/regressionTest';


test.describe('Bug/error: Perbaiki nama hasil unduhan Data Wilayah Administratif #11224', () => {
  test('fix: download Data Wilayah Administratif menampilkan filename deskriptif bukan hanya tanggal', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11224',
    },
  }, async ({ page }) => {
    // Regresi #11224 (level dusun): nama berkas unduhan harus deskriptif, bukan
    // hanya tanggal. Di level dusun, $target=false → tombol Unduh membuka modal
    // penandatangan (#modalBox, form#validasi) dengan dua select WAJIB
    // (pamong_ttd & pamong_ketahui), lalu submit men-stream berkas .xls.
    await page.goto('wilayah');
    await expect(page.locator('#tabeldata tbody tr').first()).toBeVisible({ timeout: 15000 });

    // Buka split-button Cetak/Unduh, lalu pilih menu Unduh (memuat modal).
    await page.locator('a[data-toggle="dropdown"]', { hasText: 'Cetak/Unduh' }).click();
    await page.locator('ul.dropdown-menu a[title="Unduh Data"]').click();

    // Modal penandatangan termuat lewat AJAX ke #modalBox.
    const modal = page.locator('#modalBox');
    const form = modal.locator('form#validasi');
    await expect(form).toBeVisible({ timeout: 15000 });

    // Penuhi dua select WAJIB (validasi jQuery menolak submit bila kosong).
    await form.locator('select[name="pamong_ttd"]').selectOption({ index: 1 });
    await form.locator('select[name="pamong_ketahui"]').selectOption({ index: 1 });

    // Submit → unduhan dimulai (form target=_blank, ditangkap sebagai download).
    const downloadPromise = page.waitForEvent('download', { timeout: 15000 });
    await form.locator('button[type="submit"]').click();
    const download = await downloadPromise;
    const filename = download.suggestedFilename();

    // Regresi inti #11224: ada prefix deskriptif, bukan hanya tanggal.
    // Level dusun mengekspor sebagai "data_dusun_<tanggal>.xls".
    expect(filename).not.toMatch(/^_\d{1,2}_\d{1,2}_\d{4}\.xls$/i);
    expect(filename).toMatch(/^data_dusun_\d{1,2}_\d{1,2}_\d{4}\.xls$/i);
  });

  test('fix: download Data Wilayah RW menampilkan filename dengan konteks RW dan dusun', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11224 - RW level download',
    },
  }, async ({ page }) => {
    // Level RW: $target=true → tombol Unduh adalah tautan target=_blank
    // (unduhan langsung, tanpa modal). Data dusun/RW deterministik dari seed.
    await page.goto('wilayah');
    await expect(page.locator('#tabeldata tbody tr').first()).toBeVisible({ timeout: 15000 });

    // Masuk ke level RW lewat tautan aksi pada baris dusun pertama.
    await page.locator('#tabeldata tbody tr').first().locator('a').first().click();
    await expect(page.locator('#tabeldata tbody tr').first()).toBeVisible({ timeout: 15000 });

    // Buka split-button Cetak/Unduh lalu klik Unduh (unduhan langsung).
    await page.locator('a[data-toggle="dropdown"]', { hasText: 'Cetak/Unduh' }).click();
    const downloadPromise = page.waitForEvent('download', { timeout: 15000 });
    await page.locator('ul.dropdown-menu a[title="Unduh Data"]').click();
    const filename = (await downloadPromise).suggestedFilename();

    // Regresi #11224 (level RW): nama berkas deskriptif berisi level + konteks
    // jorong, bukan hanya tanggal (mis. data_rw_wilayah_jorong_<dusun>_<tgl>.xls).
    expect(filename).not.toMatch(/^_\d{1,2}_\d{1,2}_\d{4}\.xls$/i);
    expect(filename).toMatch(/^data_rw_wilayah_jorong_.+_\d{1,2}_\d{1,2}_\d{4}\.xls$/i);
  });

  test('fix: download Data Wilayah RT menampilkan filename dengan konteks RT, RW dan dusun', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11224 - RT level download',
    },
  }, async ({ page }) => {
    // Level RT: dua kali masuk (dusun → RW → RT). $target=true → unduhan
    // langsung. Data dusun/RW/RT deterministik dari seed.
    await page.goto('wilayah');
    await expect(page.locator('#tabeldata tbody tr').first()).toBeVisible({ timeout: 15000 });

    // Masuk ke level RW (baris dusun pertama), lalu ke level RT (baris RW pertama).
    await page.locator('#tabeldata tbody tr').first().locator('a').first().click();
    await expect(page.locator('#tabeldata tbody tr').first()).toBeVisible({ timeout: 15000 });
    await page.locator('#tabeldata tbody tr').first().locator('a').first().click();
    await expect(page.locator('#tabeldata tbody tr').first()).toBeVisible({ timeout: 15000 });

    // Buka split-button Cetak/Unduh lalu klik Unduh (unduhan langsung).
    await page.locator('a[data-toggle="dropdown"]', { hasText: 'Cetak/Unduh' }).click();
    const downloadPromise = page.waitForEvent('download', { timeout: 15000 });
    await page.locator('ul.dropdown-menu a[title="Unduh Data"]').click();
    const filename = (await downloadPromise).suggestedFilename();

    // Regresi #11224 (level RT): nama berkas deskriptif berisi level RT + konteks
    // RW/jorong, bukan hanya tanggal (data_rt_wilayah_rw_jorong_<dusun>_<tgl>.xls).
    expect(filename).not.toMatch(/^_\d{1,2}_\d{1,2}_\d{4}\.xls$/i);
    expect(filename).toMatch(/^data_rt_wilayah_rw_jorong_.+_\d{1,2}_\d{1,2}_\d{4}\.xls$/i);
  });
});
