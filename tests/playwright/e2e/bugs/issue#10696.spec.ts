import { test, expect } from '@playwright/test';

test.describe('tanggal sk pengangkatan doubel #10696', () => {
  test('fix: perbaikan tanggal sk pengangkatan doubel', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10696',
    },
  }, async ({ page }) => {
    await page.goto('lembaga_anggota');
    // Menunggu tabel dan isinya dimuat
    await page.waitForSelector('table.dataTable tbody tr');

    // Klik tombol rincian pada baris pertama
    await page.locator('tbody tr').first().getByRole('link', { name: 'Rincian' }).click();

    // Tunggu hingga halaman detail dimuat sepenuhnya
    await page.waitForURL(/\/lembaga_anggota\/detail\/\d+/);
    await page.waitForLoadState('domcontentloaded');

    // Cek bahwa hanya ada satu header 'Tanggal SK Pengangkatan'
    const headerLocator = page.locator('th', { hasText: /^Tanggal SK Pengangkatan$/ });
    await expect(headerLocator).toHaveCount(1);
  });
});
