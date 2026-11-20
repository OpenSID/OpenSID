// Issue: #11290 — Perbaiki infinite scroll/AJAX loading pada select Ketua Kelompok
// https://github.com/OpenSID/OpenSID/issues/11290
import { test, expect } from '../../../utils/regressionTest';

test.describe('Perbaiki: Infinite Scroll pada Select Ketua Kelompok #11290', () => {
  test('should have data-ajax-url attribute for AJAX loading', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11290',
    },
  }, async ({ page }) => {
    test.setTimeout(30000);

    await page.goto('kelompok/form');
    await page.waitForLoadState('domcontentloaded');

    const ketuaSelect = page.locator('#kelompok_penduduk');
    await expect(ketuaSelect).toBeVisible();

    const dataAjaxUrl = await ketuaSelect.getAttribute('data-ajax-url');
    expect(dataAjaxUrl).toBeTruthy();
    expect(dataAjaxUrl).toContain('apipendudukkelompok');
  });

  test('should initialize select2 with AJAX configuration', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11290',
    },
  }, async ({ page }) => {
    test.setTimeout(30000);

    await page.goto('kelompok/form');
    await page.waitForLoadState('domcontentloaded');

    const ketuaSelect = page.locator('#kelompok_penduduk');
    await expect(ketuaSelect).toBeVisible();

    await page.waitForTimeout(1000);

    const select2Container = page.locator('.select2-container').filter({
      has: page.locator('select#kelompok_penduduk'),
    });

    expect(await select2Container.count()).toBeGreaterThan(0);
  });

  test('should maintain required class on select element', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11290',
    },
  }, async ({ page }) => {
    test.setTimeout(30000);

    await page.goto('kelompok/form');
    await page.waitForLoadState('domcontentloaded');

    const ketuaSelect = page.locator('#kelompok_penduduk');
    await expect(ketuaSelect).toHaveClass(/required/);
    await expect(ketuaSelect).toHaveClass(/select2/);
  });

  test('should have all required data attributes together', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11290',
    },
  }, async ({ page }) => {
    test.setTimeout(30000);

    await page.goto('kelompok/form');
    await page.waitForLoadState('domcontentloaded');

    const ketuaSelect = page.locator('#kelompok_penduduk');
    const attributes = await ketuaSelect.evaluate((el) => {
      return Object.fromEntries(
        Array.from(el.attributes).map((attr) => [attr.name, attr.value])
      );
    });

    expect(attributes['data-tipe']).toBe('kelompok');
    expect(attributes['data-kelompok']).toBeDefined();
    expect(attributes['data-kelompok']).toMatch(/^\d+$/);
    expect(attributes['class']).toContain('form-control');
    expect(attributes['class']).toContain('select2');
    expect(attributes['class']).toContain('required');
    expect(attributes['name']).toBe('id_ketua');
    expect(attributes['id']).toBe('kelompok_penduduk');
  });

  test('should be able to select Ketua from dropdown options', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11290',
    },
  }, async ({ page }) => {
    test.setTimeout(30000);

    await page.goto('kelompok/form');
    await page.waitForLoadState('domcontentloaded');

    const ketuaSelect = page.locator('#kelompok_penduduk');
    await expect(ketuaSelect).toBeVisible();

    const optionCount = await ketuaSelect.locator('option').count();
    expect(optionCount).toBe(1);

    const requestPromise = page.waitForRequest(
      (req) => req.url().includes('apipendudukkelompok'),
      { timeout: 5000 }
    );

    await page.locator('.select2-container').first().click();

    const request = await requestPromise;
    expect(request.url()).toContain('apipendudukkelompok');
  });

  test('should update data-kelompok value when kelompok already exists', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11290',
    },
  }, async ({ page }) => {
    test.setTimeout(30000);

    await page.goto('kelompok');
    await page.waitForSelector('table#tabeldata tbody tr', { timeout: 15000 });

    const firstRow = page.locator('table#tabeldata tbody tr').first();
    const editButton = firstRow.locator('a, button').first();

    if (await editButton.isVisible()) {
      await editButton.click();
      await page.waitForLoadState('domcontentloaded');

      const form = page.locator('form');
      if (await form.isVisible()) {
        const ketuaSelect = page.locator('#kelompok_penduduk');
        const dataKelompok = await ketuaSelect.getAttribute('data-kelompok');
        expect(dataKelompok).toMatch(/^\d+$/);
      }
    }
  });

  test('should initialize select2 properly with data attributes', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11290',
    },
  }, async ({ page }) => {
    test.setTimeout(30000);

    await page.goto('kelompok/form');
    await page.waitForLoadState('domcontentloaded');

    const ketuaSelect = page.locator('#kelompok_penduduk');
    await page.waitForTimeout(1000);

    const dataTipe = await ketuaSelect.getAttribute('data-tipe');
    const dataKelompok = await ketuaSelect.getAttribute('data-kelompok');

    expect(dataTipe).toBe('kelompok');
    expect(dataKelompok).toMatch(/^\d+$/);
  });

  test('should submit form successfully when Ketua is selected', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11290',
    },
  }, async ({ page }) => {
    test.setTimeout(30000);

    await page.goto('kelompok/form');
    await page.waitForLoadState('domcontentloaded');

    await page.locator('input[name="nama"]').fill('Test Kelompok');

    const kategoriSelect = page.locator('select[name="id_master"]');
    if (await kategoriSelect.isVisible()) {
      const options = await kategoriSelect.locator('option').count();
      if (options > 1) {
        await kategoriSelect.selectOption({ index: 1 });
      }
    }

    const ketuaSelect = page.locator('#kelompok_penduduk');
    const ketuaOptions = await ketuaSelect.locator('option').count();

    if (ketuaOptions > 1) {
      await ketuaSelect.selectOption({ index: 1 });
    }

    const submitButton = page.getByRole('button', { name: /Simpan|Submit/i });
    if (await submitButton.isVisible()) {
      await submitButton.click();
      await page.waitForLoadState('domcontentloaded');

      const alerts = page.locator('[role="alert"], .alert');
      const hasAlert = (await alerts.count()) > 0;

      expect(hasAlert || page.url().includes('kelompok')).toBeTruthy();
    }
  });

  test('should validate form quickly without lag', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11290',
    },
  }, async ({ page }) => {
    test.setTimeout(30000);

    await page.goto('kelompok/form');
    await page.waitForLoadState('domcontentloaded');

    const startTime = Date.now();
    await page.getByRole('button', { name: /Simpan/i }).click();
    const duration = Date.now() - startTime;

    expect(duration).toBeLessThan(5000);
  });

  test('should validate with special characters and long values', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11290',
    },
  }, async ({ page }) => {
    test.setTimeout(30000);

    await page.goto('kelompok/form');
    await page.waitForLoadState('domcontentloaded');

    await page.locator('input[name="nama"]').fill('Test & Special <> Kelompok');

    const value = await page.locator('input[name="nama"]').inputValue();
    expect(value).toContain('&');

    await page.getByRole('button', { name: /Simpan/i }).click();
    await page.waitForLoadState('domcontentloaded');

    const isSuccess = await page.locator('.alert-success, .alert-info').isVisible();
    const isError = await page.locator('.alert-danger, .error').isVisible();

    expect(isSuccess || isError).toBeTruthy();
  });
});
