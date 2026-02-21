import { test, expect } from '@playwright/test';
import { Laravel } from '@test/utils/laravel';

test.describe('Security: Peningkatan Pematasan Pengiriman Komentar Artikel (#10852)', () => {
  let articleId: number;
  let articleUrl: string;

  test.beforeAll(async () => {
    // Buat artikel test dengan allow_komentar = 1
    const result = await Laravel.query(`
      INSERT INTO artikel (config_id, judul, slug, isi, allow_komentar, status, tgl_upload)
      VALUES (1, 'Test Article for Comments', 'test-article-comments', 'This is test article content', 1, '1', NOW())
    `);

    // Ambil ID artikel yang baru dibuat
    const articles = await Laravel.query(`
      SELECT id FROM artikel WHERE slug = 'test-article-comments' LIMIT 1
    `);

    if (Array.isArray(articles) && articles.length > 0) {
      articleId = articles[0].id;
      articleUrl = `/first/${articleId}`;
    }
  });

  test.afterAll(async () => {
    // Hapus artikel test dan komentarnya
    if (articleId) {
      await Laravel.query(`DELETE FROM komentar WHERE id_artikel = ${articleId}`);
      await Laravel.query(`DELETE FROM artikel WHERE id = ${articleId}`);
    }
  });

  test('feature: Submit komentar berhasil dengan CAPTCHA valid', async ({ page, context }) => {
    // 1. Akses halaman artikel
    await page.goto(articleUrl);
    await expect(page.locator('h1, h2')).toContainText('Test Article');

    // 2. Scroll ke form komentar
    await page.locator('form#kolom-komentar').scrollIntoViewIfNeeded();
    await expect(page.locator('form#kolom-komentar')).toBeVisible();

    // 3. Isi form komentar dengan data valid
    await page.fill('textarea[name="komentar"]', 'Ini adalah komentar test yang valid');
    await page.fill('input[name="owner"]', 'Test User');
    await page.fill('input[name="no_hp"]', '081234567890');
    await page.fill('input[name="email"]', 'testuser@example.com');

    // 4. Setup CAPTCHA - bypass dengan set session di database
    const cookies = await context.cookies();
    const sessionId = cookies.find(c => c.name === 'PHPSESSID' || c.name === 'LARAVEL_SESSION')?.value;

    if (sessionId) {
      // Generate CAPTCHA code dan simpan di database
      const captchaCode = 'ABC123';
      const captchaHash = '$2y$10$' + Buffer.from(captchaCode).toString('base64').substring(0, 50).padEnd(53, 'x');

      // Set CAPTCHA di session
      await Laravel.query(`
        UPDATE sessions 
        SET data = CONCAT('a:1:{s:7:"captcha";s:', LENGTH('${captchaHash}'), ':"${captchaHash}";}')
        WHERE session_id = '${sessionId}'
      `).catch(() => {
        // Jika query gagal, lanjutkan dengan cara lain
      });
    }

    // 5. Isi CAPTCHA (akan di-bypass oleh setup di atas)
    await page.fill('input[name="captcha_code"]', 'ABC123');

    // 6. Submit form
    await page.click('button[type="submit"]');

    // 7. Verifikasi SweetAlert loading muncul
    await expect(page.locator('div.swal2-popup')).toBeVisible();

    // 8. Tunggu berhasil dan validasi response
    await page.waitForURL(new RegExp(articleUrl + '.*#kolom-komentar'));
    await expect(page.locator('div.alert')).toContainText(/berhasil|submitted/i);
  });

  test('feature: CAPTCHA tidak bisa digunakan dua kali (invalidation)', async ({ page, context }) => {
    // 1. Akses halaman artikel
    await page.goto(articleUrl);
    await expect(page.locator('form#kolom-komentar')).toBeVisible();

    const captchaCode = 'DEF456';

    // 2. Setup CAPTCHA pertama
    const cookies = await context.cookies();
    const sessionId = cookies.find(c => c.name === 'PHPSESSID' || c.name === 'LARAVEL_SESSION')?.value;

    if (sessionId) {
      const captchaHash = '$2y$10$' + Buffer.from(captchaCode).toString('base64').substring(0, 50).padEnd(53, 'x');
      await Laravel.query(`
        UPDATE sessions 
        SET data = CONCAT('a:1:{s:7:"captcha";s:', LENGTH('${captchaHash}'), ':"${captchaHash}";}')
        WHERE session_id = '${sessionId}'`).catch(() => {});
    }

    // 3. Submit komentar pertama
    await page.fill('textarea[name="komentar"]', 'Komentar pertama');
    await page.fill('input[name="owner"]', 'User Test');
    await page.fill('input[name="no_hp"]', '082345678901');
    await page.fill('input[name="email"]', 'test1@example.com');
    await page.fill('input[name="captcha_code"]', captchaCode);
    await page.click('button[type="submit"]');

    // 4. Tunggu response
    await page.waitForURL(new RegExp(articleUrl + '.*#kolom-komentar'));
    await page.waitForTimeout(1000);

    // 5. Reload halaman
    await page.reload();
    await expect(page.locator('form#kolom-komentar')).toBeVisible();

    // 6. Coba submit komentar dengan CAPTCHA CODE YANG SAMA
    // CAPTCHA seharusnya sudah di-invalidate setelah submit pertama
    await page.fill('textarea[name="komentar"]', 'Komentar kedua dengan CAPTCHA yang sama');
    await page.fill('input[name="owner"]', 'User Test 2');
    await page.fill('input[name="no_hp"]', '082345678902');
    await page.fill('input[name="email"]', 'test2@example.com');
    await page.fill('input[name="captcha_code"]', captchaCode);
    await page.click('button[type="submit"]');

    // 7. Verifikasi error - CAPTCHA tidak valid/invalid
    await expect(page.locator('div.alert')).toContainText(/salah|invalid|tidak valid/i);
  });

  test('feature: Rate limiting - mencegah pengiriman komentar dalam 60 detik', async ({ page, context }) => {
    // 1. Akses halaman artikel
    await page.goto(articleUrl);

    // 2. Dapatkan IP untuk rate limiting key
    const clientIp = '127.0.0.1'; // untuk local test
    const rateLimitKey = `comment_rate_limit_${clientIp}`;

    // 3. Setup CAPTCHA pertama
    const cookies = await context.cookies();
    const sessionId = cookies.find(c => c.name === 'PHPSESSID' || c.name === 'LARAVEL_SESSION')?.value;

    const captchaCode1 = 'XYZ789';
    if (sessionId) {
      const captchaHash = '$2y$10$' + Buffer.from(captchaCode1).toString('base64').substring(0, 50).padEnd(53, 'x');
      await Laravel.query(`
        UPDATE sessions 
        SET data = CONCAT('a:1:{s:7:"captcha";s:', LENGTH('${captchaHash}'), ':"${captchaHash}";}')
        WHERE session_id = '${sessionId}'`).catch(() => {});
    }

    // 4. Submit komentar pertama
    await page.fill('textarea[name="komentar"]', 'Komentar pertama untuk rate limit test');
    await page.fill('input[name="owner"]', 'Rate Limit User');
    await page.fill('input[name="no_hp"]', '089876543210');
    await page.fill('input[name="email"]', 'ratelimit@example.com');
    await page.fill('input[name="captcha_code"]', captchaCode1);
    await page.click('button[type="submit"]');

    // 5. Tunggu response
    await page.waitForURL(new RegExp(articleUrl + '.*#kolom-komentar'));
    await expect(page.locator('div.alert')).toContainText(/berhasil|submitted/i);

    // 6. Reload halaman untuk form bersih
    await page.reload();
    await expect(page.locator('form#kolom-komentar')).toBeVisible();

    // 7. Setup CAPTCHA baru untuk submission kedua
    const captchaCode2 = 'UVW012';
    if (sessionId) {
      const captchaHash2 = '$2y$10$' + Buffer.from(captchaCode2).toString('base64').substring(0, 50).padEnd(53, 'x');
      await Laravel.query(`
        UPDATE sessions 
        SET data = CONCAT('a:1:{s:7:"captcha";s:', LENGTH('${captchaHash2}'), ':"${captchaHash2}";}')
        WHERE session_id = '${sessionId}'`).catch(() => {});
    }

    // 8. Coba submit komentar kedua dalam 60 detik
    await page.fill('textarea[name="komentar"]', 'Komentar kedua - seharusnya error rate limit');
    await page.fill('input[name="owner"]', 'Rate Limit User');
    await page.fill('input[name="no_hp"]', '089876543210');
    await page.fill('input[name="email"]', 'ratelimit2@example.com');
    await page.fill('input[name="captcha_code"]', captchaCode2);
    await page.click('button[type="submit"]');

    // 9. Verifikasi error rate limit
    const errorLocator = page.locator('div.alert');
    await expect(errorLocator).toContainText(/terlalu banyak|rate limit|tunggu|60 detik/i);
  });

  test('feature: Loading indicator SweetAlert saat submit komentar', async ({ page }) => {
    // 1. Akses halaman artikel
    await page.goto(articleUrl);

    // 2. Isi form komentar
    await page.fill('textarea[name="komentar"]', 'Test loading indicator');
    await page.fill('input[name="owner"]', 'Test User');
    await page.fill('input[name="no_hp"]', '081111111111');
    await page.fill('input[name="email"]', 'test@example.com');
    await page.fill('input[name="captcha_code"]', 'ABC123');

    // 3. Setup event listener untuk menangkap alert
    let alertShown = false;
    page.on('popup', () => {
      alertShown = true;
    });

    // 4. Submit form
    const submitButton = page.locator('button[type="submit"]');
    await submitButton.click();

    // 5. Verifikasi SweetAlert loading muncul
    await expect(page.locator('div.swal2-popup')).toBeVisible({ timeout: 5000 });

    // 6. Verifikasi loading spinner
    const loadingElement = page.locator('div.swal2-loading, svg.swal2-loading');
    await expect(loadingElement).toBeVisible();

    // 7. Verifikasi teks loading
    await expect(page.locator('div.swal2-html-container')).toContainText(/tunggu|mengirim/i);
  });

  test('security: Validasi CAPTCHA dengan strict mode', async ({ page }) => {
    // 1. Akses halaman artikel
    await page.goto(articleUrl);

    // 2. Isi form komentar dengan CAPTCHA yang salah
    await page.fill('textarea[name="komentar"]', 'Komentar dengan CAPTCHA salah');
    await page.fill('input[name="owner"]', 'Error User');
    await page.fill('input[name="no_hp"]', '085555555555');
    await page.fill('input[name="email"]', 'error@example.com');
    await page.fill('input[name="captcha_code"]', 'WRONGCODE');

    // 3. Submit form
    await page.click('button[type="submit"]');

    // 4. Verifikasi error message
    await expect(page.locator('div.alert')).toContainText(/salah|invalid/i);

    // 5. Verifikasi form masih terlihat dengan data yang sudah diisi
    await expect(page.locator('textarea[name="komentar"]')).toContainText('Komentar dengan CAPTCHA salah', { matchSubstring: true });
  });
});
