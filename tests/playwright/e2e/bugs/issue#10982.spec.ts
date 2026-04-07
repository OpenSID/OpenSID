import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: nama file hasil unduh xls pada menu laporan kelompok rentan tidak berisi nama hanya timestamp saja #10982', () => {
  test('fix: perbaiki nama file hasil unduh xls pada menu laporan kelompok rentan tidak berisi nama', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10982',
    },
  }, async ({ page }) => {
    await page.goto('laporan_rentan');

    // Tunggu event download
    const [download] = await Promise.all([
      page.waitForEvent('download'),
      page.getByRole('link', { name: ' Unduh' }).click(),
    ]);

    // Ambil nama file hasil unduhan
    const fileName = download.suggestedFilename();
    // Pastikan nama file sesuai pola 'kelompok_rentan_*.xls' (atau ekstensi lain jika perlu)
    expect(fileName).toMatch(/^kelompok_rentan_.*\.(xls|xlsx|csv)$/);
  });
});