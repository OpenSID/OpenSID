// Issue: #11910 — icon kuser.png not Found saat Tambah Penduduk
// https://github.com/OpenSID/OpenSID/issues/11910
import { test, expect } from '../../../utils/regressionTest';

test.describe('[BUG] icon kuser.png not Found saat Tambah Penduduk #11910', () => {
  test('Foto default kuser.png dan wuser.png termuat dengan benar saat tambah penduduk dan ubah jenis kelamin', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11910',
    },
  }, async ({ page }) => {
    // 1. Buka halaman form tambah penduduk lahir
    await page.goto('penduduk/form_peristiwa/1');
    await page.waitForSelector('#foto', { timeout: 10000 });

    const fotoImg = page.locator('#foto');
    await expect(fotoImg).toBeVisible();

    // 2. Pastikan src default mengarah ke path asset yang valid (kuser.png)
    const initialSrc = await fotoImg.getAttribute('src');
    expect(initialSrc).toContain('assets/images/pengguna/kuser.png');
    expect(initialSrc).not.toContain('form_peristiwa/assets');
    expect(initialSrc).not.toContain('assets/assets');

    // 3. Verifikasi gambar kuser.png berhasil di-load browser (naturalWidth > 0)
    const isImageLoaded = await fotoImg.evaluate((img: HTMLImageElement) => img.complete && img.naturalWidth > 0);
    expect(isImageLoaded).toBeTruthy();

    // 4. Ubah jenis kelamin ke Perempuan (2)
    const sexSelect = page.locator('select[name="sex"]');
    await sexSelect.selectOption('2');
    await page.waitForTimeout(300);

    // 5. Pastikan foto berubah menjadi wuser.png dan berhasil di-load
    const femaleSrc = await fotoImg.getAttribute('src');
    expect(femaleSrc).toContain('assets/images/pengguna/wuser.png');
    expect(femaleSrc).not.toContain('form_peristiwa/assets');
    expect(femaleSrc).not.toContain('assets/assets');

    const isFemaleImageLoaded = await fotoImg.evaluate((img: HTMLImageElement) => img.complete && img.naturalWidth > 0);
    expect(isFemaleImageLoaded).toBeTruthy();

    // 6. Ubah jenis kelamin kembali ke Laki-laki (1)
    await sexSelect.selectOption('1');
    await page.waitForTimeout(300);

    const maleSrc = await fotoImg.getAttribute('src');
    expect(maleSrc).toContain('assets/images/pengguna/kuser.png');
    expect(maleSrc).not.toContain('form_peristiwa/assets');
    expect(maleSrc).not.toContain('assets/assets');

    const isMaleImageLoaded = await fotoImg.evaluate((img: HTMLImageElement) => img.complete && img.naturalWidth > 0);
    expect(isMaleImageLoaded).toBeTruthy();
  });
});
