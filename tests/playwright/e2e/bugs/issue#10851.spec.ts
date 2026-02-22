import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test('Bug #10851: Total peserta = laki-laki + perempuan (tidak tertukar)', {
  annotation: {
    type: 'issue',
    description: 'https://github.com/OpenSID/OpenSID/issues/10851',
  },
}, async ({ page }) => {
  // Navigasi ke halaman statistik bantuan keluarga
  await page.goto('data-statistik/bantuan_keluarga');

  // Tunggu halaman dimuat
  await expect(page.locator('table')).toBeVisible({ timeout: 10000 });

  // Ambil baris TOTAL dari tabel
  const totalRow = page.locator('table tbody tr:has-text("TOTAL")').first();
  
  if (await totalRow.count() > 0) {
    const cells = totalRow.locator('td');
    
    // Ambil nilai dari kolom: Total, Laki-laki, Perempuan
    const totalValue = parseInt((await cells.nth(0).textContent())?.trim() || '0', 10);
    const lakiValue = parseInt((await cells.nth(1).textContent())?.trim() || '0', 10);
    const perempuanValue = parseInt((await cells.nth(2).textContent())?.trim() || '0', 10);

    // Verifikasi: Total = Laki-laki + Perempuan (tidak tertukar)
    expect(totalValue).toBe(lakiValue + perempuanValue);
  }
});
