import { test, expect, request } from '@playwright/test';

test.describe('Bug/error: Gambar/icon default menu anjungan tidak muncul #10757', () => {
  test('fix: perbaiki icon default menu anjungan tidak muncul', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10757'
    }
  }, async ({ page }) => {
    const baseURL = process.env.PLAYWRIGHT_BASE_URL;
    const imageResponse = await request.newContext().then(ctx =>
      ctx.get(`${baseURL}/module_asset/anjungan?file=images/menu.png&v=2601.0.2`)
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