// Issue: #9553 — Data tidak sesuai pada shortcut
// https://github.com/OpenSID/OpenSID/issues/9553
import { test, expect } from '../../../utils/regressionTest';

test.describe('Bug/error: Data tidak sesuai pada Shortcut #9553', () => {
  test('fix: shortcut surat', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/9553',
    },
  }, async ({ page }) => {
    await page.goto('shortcut');
    await page.getByRole('link', { name: ' Tambah' }).click();
    await page.locator('input[name="judul"]').click();
    await page.locator('input[name="judul"]').fill('Surat');
    await page.locator('#select2-raw_query-container').click();
    await page.locator('input[type="search"]').fill('jumlah surat');
    await page.getByRole('treeitem', { name: 'Jumlah Surat', exact: true }).click();
    await page.locator('.input-group-addon').click();
    await page.locator('.colorpicker-saturation').click();
    await page.getByRole('textbox', { name: 'Tidak' }).click();
    await page.getByRole('treeitem', { name: 'Ya' }).click();
    await page.getByRole('button', { name: ' Simpan' }).click();
    await page.getByRole('link', { name: ' Layanan Surat ' }).click();
    await page.getByRole('link', { name: ' Pengaturan Surat' }).click();

    // Read actual count from DataTable instead of hardcoding
    await page.waitForLoadState('networkidle');
    const infoLocator = page.locator('#tabeldata_info');
    await expect(infoLocator).toContainText('entri', { timeout: 15000 });
    const infoText = await infoLocator.textContent() ?? '';
    const countMatch = infoText.match(/dari (\d+) entri/);
    const actualCount = countMatch ? countMatch[1] : '0';

    await page.getByRole('link', { name: ' Beranda' }).first().click();
    await expect(page.locator('#maincontent')).toContainText(`${actualCount} Surat`);

    // Cleanup: delete the 'Surat' shortcut by matching exact name in table cell
    await page.getByRole('link', { name: ' Pengaturan ' }).click();
    await page.getByRole('link', { name: ' Shortcut' }).click();
    await page.waitForLoadState('networkidle');
    await page.locator('tr').filter({ has: page.locator('td', { hasText: /^\s*Surat\s*$/ }) }).getByRole('link').nth(2).click();
    const modal = page.locator('#confirm-delete');
    await expect(modal).toBeVisible();
    // Wait for shown.bs.modal to attach the input handler (it auto-focuses after 150ms)
    await expect(modal.locator('#confirm-input')).toBeFocused({ timeout: 5000 });
    await modal.locator('#confirm-input').fill('HAPUS');
    await expect(modal.locator('#ok-delete')).not.toBeDisabled();
    await modal.locator('#ok-delete').click();
    await expect(page.getByText('Berhasil Hapus Data')).toBeVisible();
  });
});
