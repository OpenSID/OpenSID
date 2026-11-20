// Issue: #9750 — Tema tidak ditemukan
// https://github.com/OpenSID/OpenSID/issues/9750
import { test, expect } from '../../../utils/regressionTest';
import { Laravel } from '@test/utils/laravel';


test.beforeEach(async () => {
  await Laravel.query(`
    UPDATE theme SET status = 1 WHERE slug = 'desa-silir-web-theme';
    UPDATE theme SET status = 0 WHERE slug = 'natra';
  `, [], { unprepared: true });
});

test.afterEach(async () => {
  await Laravel.query(`
    UPDATE theme SET status = 1 WHERE slug = 'natra';
    UPDATE theme SET status = 0 WHERE slug = 'desa-silir-web-theme';
  `, [], { unprepared: true });
});

test.describe('Tema tidak ditemukan #9750', () => {
  test('fix: perbaiki tema tidak ditemukan', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9750',
    },
  }, async ({ page }) => {
    await page.goto('/');
    await expect(page.getByText('Artikel Terkini').first()).toBeVisible();
  });
});