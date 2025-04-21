import { test, expect, request } from '@playwright/test';

test.describe('Bug/error: Latar Belakang Kehadiran setiap update versi hilang #9466', () => {
  test('fix: perbaiki latar belakang kehadiran tidak tampil', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9466'
    }
  }, async ({ page }) => {
    const baseURL = process.env.PLAYWRIGHT_BASE_URL;
    const imageResponse = await request.newContext().then(ctx =>
      ctx.get(`${baseURL}/kehadiran/latar-kehadiran`)
    );

    // Status harus 200 OK
    expect(imageResponse.status()).toBe(200);

    // Harus berisi konten tipe image
    const contentType = imageResponse.headers()['content-type'];
    expect(contentType).toMatch(/^image\//);

    // Harus ada isi (buffer tidak kosong)
    const buffer = await imageResponse.body();
    expect(buffer.length).toBeGreaterThan(0);
  });
});