// Issue: #9429 — Filter status media sosial
// https://github.com/OpenSID/OpenSID/issues/9429
import { test, expect} from '../../../utils/regressionTest';


test.describe('Filter Status Medsos #9429', () => {
  test('Menambahkan filter status media sosial', {
    annotation: {
        type: 'issue',
        description: 'https://github.com/OpenSID/OpenSID/issues/9429'
    }
  }, async ({ page }) => {
    await page.goto('sosmed');
    await expect(page.locator('#select2-status-container')).toContainText('Aktif');
    await expect(page.locator('#dragable')).toContainText('Aktif');
    await page.getByRole('combobox', { name: 'Aktif' }).locator('b').click();
    await page.getByRole('treeitem', { name: 'Tidak Aktif' }).click();
    await expect(page.locator('#select2-status-container')).toContainText('Tidak Aktif');
    await expect(page.locator('#dragable')).toContainText('Tidak Aktif');
  });
});