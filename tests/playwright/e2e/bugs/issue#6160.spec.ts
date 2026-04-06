import { test, expect } from '@playwright/test';

test.describe('Bug/error: SQL Injection (Blind) pada Parameter filter[tahun] di Endpoint Bantuan Penduduk #6160', () => {
  test('fix: perbaiki SQL Injection pada filter[tahun] endpoint api bantuan-penduduk', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/6160',
    },
  }, async ({ page }) => {
    // Lakukan simulasi payload malicios SQL injection
    const apiResponse = await page.request.get('/internal_api/peserta_bantuan/bantuan_penduduk', {
      params: {
        'filter[tahun]': '2024 AND 1=db_id()'
      }
    });

    // Pastikan API kembalikan status 200 OK yang mengartikan error SQL Injection (500) sudah diatasi
    expect(apiResponse.ok()).toBeTruthy();

    // Verifikasi struktur JSON-nya
    const apiData = await apiResponse.json();
    expect(apiData).toHaveProperty('data');
    expect(Array.isArray(apiData.data)).toBeTruthy();
  });
});
