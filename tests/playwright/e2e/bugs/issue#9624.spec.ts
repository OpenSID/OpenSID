import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: background kehadiran tidak tampil #9624', () => {
  test('fix: perbaiki background kehadiran tidak tampil', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9624',
    },
  }, async ({ page }) => {
    // akses ke halaman
    await page.goto('kehadiran/masuk');

    // lakukan pengecekan apakah halaman pada kelas .form-left ada gambar di css
    const hasBackgroundImage = await page.locator('.form-left').evaluate(el => {
      const style = window.getComputedStyle(el);
      return style.backgroundImage !== 'none';
    });

    // pastikan ada gambar di css
    expect(hasBackgroundImage).toBe(true);
  });
});