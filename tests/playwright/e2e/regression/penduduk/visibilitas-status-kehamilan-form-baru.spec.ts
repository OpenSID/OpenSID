// Issue: #11229 — Fix visibilitas field status kehamilan pada entri penduduk baru
// https://github.com/OpenSID/OpenSID/issues/11229
import { test, expect } from '../../../utils/regressionTest';

async function gotoFormPeristiwa(page: any, peristiwa: number) {
  await page.goto(`penduduk/form_peristiwa/${peristiwa}`);
  await page.bringToFront();
  await page.waitForLoadState('domcontentloaded');
  // Wait for jQuery and form JS to initialize
  await page.waitForFunction(() => typeof (window as any).jQuery !== 'undefined');
  await page.waitForTimeout(500);
}

test.describe('Bug Fix: Visibilitas field Status Kehamilan pada entri penduduk baru #11229', () => {
  test('UTAMA: Field Status Kehamilan tidak boleh tampil untuk perempuan saat entri penduduk baru (jenis_peristiwa=1)', {
    annotation: {
      type: 'issue',
      description: 'Issue #11229 - Field Status Kehamilan harus disembunyikan saat entri penduduk baru',
    },
  }, async ({ page }) => {
    await gotoFormPeristiwa(page, 1);

    const sexSelect = page.locator('select[name="sex"]');
    await sexSelect.scrollIntoViewIfNeeded();
    await expect(sexSelect).toBeVisible({ timeout: 5000 });
    await sexSelect.selectOption('2'); // Perempuan
    await page.waitForTimeout(500);

    const isisianHamilSection = page.locator('#isian_hamil').first();
    const isHidden = await isisianHamilSection.isHidden({ timeout: 2000 }).catch(() => true);
    const isNotInDOM = (await isisianHamilSection.count()) === 0;
    expect(isHidden || isNotInDOM).toBeTruthy();
  });

  test('REGRESI: Field Status Kehamilan TIDAK boleh tampil untuk laki-laki pada event apapun', {
    annotation: {
      type: 'regression',
      description: 'Verifikasi field Status Kehamilan tidak pernah tampil untuk laki-laki',
    },
  }, async ({ page }) => {
    await gotoFormPeristiwa(page, 1);

    const sexSelect = page.locator('select[name="sex"]');
    await sexSelect.scrollIntoViewIfNeeded();
    await expect(sexSelect).toBeVisible({ timeout: 5000 });
    await sexSelect.selectOption('1'); // Laki-laki
    await page.waitForTimeout(500);

    const isisianHamilSection = page.locator('#isian_hamil').first();
    const isHidden = await isisianHamilSection.isHidden({ timeout: 2000 }).catch(() => true);
    const isNotInDOM = (await isisianHamilSection.count()) === 0;
    expect(isHidden || isNotInDOM).toBeTruthy();
  });

  test('VERIFIKASI: Fungsi ubah_sex berisi pengecekan jenis_peristiwa', {
    annotation: {
      type: 'verification',
      description: 'Verifikasi fix ada dalam kode JavaScript',
    },
  }, async ({ page }) => {
    await gotoFormPeristiwa(page, 1);

    const pageContent = await page.content();
    expect(pageContent).toContain('jenis_peristiwa != 1');
    expect(pageContent).toContain('ubah_sex');
    expect(pageContent).toContain('isian_hamil');
  });

  test('EDGE CASE: Form reset harus mempertahankan state benar dari field Status Kehamilan', {
    annotation: {
      type: 'edge-case',
      description: 'Verifikasi visibilitas field Status Kehamilan setelah form di-reset',
    },
  }, async ({ page }) => {
    await gotoFormPeristiwa(page, 1);

    const sexSelect = page.locator('select[name="sex"]');
    await sexSelect.scrollIntoViewIfNeeded();
    await expect(sexSelect).toBeVisible({ timeout: 5000 });
    await sexSelect.selectOption('2'); // Perempuan
    await page.waitForTimeout(300);

    const isisianHamilSection = page.locator('#isian_hamil').first();
    const isHiddenAfterSelect = await isisianHamilSection.isHidden({ timeout: 2000 }).catch(() => true);
    expect(isHiddenAfterSelect).toBeTruthy();

    const resetButton = page.locator('button[type="reset"], input[type="reset"]').first();
    if (await resetButton.isVisible({ timeout: 1000 }).catch(() => false)) {
      await resetButton.click();
      await page.waitForTimeout(500);
      const isHiddenAfterReset = await isisianHamilSection.isHidden({ timeout: 2000 }).catch(() => true);
      const isNotInDOM = (await isisianHamilSection.count()) === 0;
      expect(isHiddenAfterReset || isNotInDOM).toBeTruthy();
    }
  });

  test('INTERAKSI: Perubahan field Jenis Kelamin harus langsung update visibilitas Status Kehamilan', {
    annotation: {
      type: 'interaction',
      description: 'Verifikasi perubahan field sex langsung memicu fungsi ubah_sex',
    },
  }, async ({ page }) => {
    await gotoFormPeristiwa(page, 1);

    const sexSelect = page.locator('select[name="sex"]');
    await sexSelect.scrollIntoViewIfNeeded();
    await expect(sexSelect).toBeVisible({ timeout: 5000 });
    const isisianHamilSection = page.locator('#isian_hamil').first();

    // Laki-laki → hidden
    await sexSelect.selectOption('1');
    await page.waitForTimeout(300);
    let isHidden = await isisianHamilSection.isHidden({ timeout: 1000 }).catch(() => true);
    expect(isHidden).toBeTruthy();

    // Perempuan on peristiwa=1 → still hidden
    await sexSelect.selectOption('2');
    await page.waitForTimeout(300);
    isHidden = await isisianHamilSection.isHidden({ timeout: 1000 }).catch(() => true);
    expect(isHidden).toBeTruthy();

    // Back to Laki-laki → hidden
    await sexSelect.selectOption('1');
    await page.waitForTimeout(300);
    isHidden = await isisianHamilSection.isHidden({ timeout: 1000 }).catch(() => true);
    expect(isHidden).toBeTruthy();
  });
});
