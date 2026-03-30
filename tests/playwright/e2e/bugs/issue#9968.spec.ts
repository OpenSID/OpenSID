import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: belum bisa auto replay #9968', () => {
  test('fix: Perbaikan validasi form pengaturan dan penambahan autoplay', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9968',
    },
  }, async ({ page }) => {
    await page.goto('anjungan_pengaturan');
    await expect(page.getByRole('textbox', { name: 'Masukkan url youtube' })).toBeVisible();
    await page.getByRole('textbox', { name: 'Masukkan url youtube' }).click();
    await page.getByRole('textbox', { name: 'Masukkan url youtube' }).fill('https://www.youtube.com/embed/PuxiuH-YUF4');
    await page.getByRole('button', { name: ' Simpan' }).click();
    await page.getByText('Berhasil Ubah Data').click();

    
    await page.goto('anjungan-mandiri');
    const iframe = page.locator('iframe.video-view');
    await expect(iframe).toBeVisible();

    // Ambil src dari iframe
    const src = await iframe.getAttribute('src');

    expect(src).toContain('youtube.com/embed/');
    expect(src).toContain('autoplay=1');
    expect(src).toContain('loop=1');
    expect(src).toContain('playlist=');

    // Pastikan videoId ada di src
    const match = src?.match(/embed\/([a-zA-Z0-9_-]{11})/);
    expect(match).not.toBeNull();
  });
});