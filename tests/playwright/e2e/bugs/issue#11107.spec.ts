import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: ketika edit surat muncul popup eror ajax #11107', () => {
  test('fix: perbaiki ajax datatable syarat surat', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/11107',
    },
  }, async ({ page }) => {
    await page.goto('surat_master/form');
  });
});
