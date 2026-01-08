import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: akses database error ketika user melakukan pengajuan izin dan upload dokumen #10699', () => {
  test('fix: perbaiki hak akses folder pengajuan_izin', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10699',
    },
  }, async ({ page }) => {
    await page.goto('info_sistem#folder_desa');
    await expect(page.getByText('pengajuan_izin\\(777)')).toBeVisible();
  });
});
