import { test, expect } from '@playwright/test';

/**
 * Test untuk Issue #6056: Kontak Pelapor & Isi Pengaduan Privat Terbuka
 *
 * Bug: Endpoint publik GET /internal_api/pengaduan mengekspos field sensitif
 * nik, email, telepon, ip_address melalui PengaduanTransformer::transform()
 * yang sebelumnya memanggil $pengaduan->toArray() tanpa whitelist.
 */
test.describe('Issue #6056 - Kebocoran Data pada Endpoint Publik Pengaduan', () => {

  test('response tidak boleh mengandung field sensitif pelapor', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/premium/issues/6056',
    },
  }, async ({ request }) => {
    const response = await request.get('/internal_api/pengaduan');

    expect(response.status()).toBe(200);

    const body = await response.json();
    const data: any[] = body?.data ?? [];

    if (data.length === 0) {
      test.skip(true, 'Tidak ada data pengaduan untuk diuji');
      return;
    }

    const attrs = data[0]?.attributes ?? data[0];

    // ----------------------------------------------------------------
    // Field sensitif TIDAK boleh ada di response
    // ----------------------------------------------------------------
    expect(attrs).not.toHaveProperty('nik');
    expect(attrs).not.toHaveProperty('email');
    expect(attrs).not.toHaveProperty('telepon');
    expect(attrs).not.toHaveProperty('ip_address');
    expect(attrs).not.toHaveProperty('config_id');
    expect(attrs).not.toHaveProperty('id_pengaduan');

    // ----------------------------------------------------------------
    // Field aman HARUS ada
    // ----------------------------------------------------------------
    expect(attrs).toHaveProperty('id');
    expect(attrs).toHaveProperty('judul');
    expect(attrs).toHaveProperty('isi');
    expect(attrs).toHaveProperty('status');
    expect(attrs).toHaveProperty('nama');
    expect(attrs).toHaveProperty('foto');
    expect(attrs).toHaveProperty('created_at');
    expect(attrs).toHaveProperty('updated_at');
    expect(attrs).toHaveProperty('child_count');
    expect(attrs).toHaveProperty('child');

    // ----------------------------------------------------------------
    // isi harus dipotong: maks 50 karakter + '…'
    // ----------------------------------------------------------------
    const isi: string = attrs.isi ?? '';
    if (isi.length > 0) {
      expect([...isi].length).toBeLessThanOrEqual(51); // 50 char + '…'
    }

    // ----------------------------------------------------------------
    // Tanggal harus format Indonesia tanpa detik: "dd Bulan YYYY HH:MM"
    // Contoh: "01 Maret 2026 08:00"
    // ----------------------------------------------------------------
    const indoDatePattern = /^\d{1,2} \w+ \d{4} \d{2}:\d{2}$/;
    expect(attrs.created_at).toMatch(indoDatePattern);
    expect(attrs.updated_at).toMatch(indoDatePattern);
  });

  test('child replies tidak boleh mengandung field sensitif', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/premium/issues/6056',
    },
  }, async ({ request }) => {
    const response = await request.get('/internal_api/pengaduan');
    expect(response.status()).toBe(200);

    const body = await response.json();
    const data: any[] = body?.data ?? [];

    // Cari pengaduan yang punya minimal satu child
    const withChild = data.find(d => {
      const attrs = d?.attributes ?? d;
      return (attrs?.child ?? []).length > 0;
    });

    if (!withChild) {
      test.skip(true, 'Tidak ada data pengaduan dengan balasan (child) untuk diuji');
      return;
    }

    const children: any[] = (withChild?.attributes ?? withChild)?.child ?? [];
    const indoDatePattern = /^\d{1,2} \w+ \d{4} \d{2}:\d{2}$/;

    for (const child of children) {
      // Field sensitif TIDAK boleh ada di balasan
      expect(child).not.toHaveProperty('nik');
      expect(child).not.toHaveProperty('email');
      expect(child).not.toHaveProperty('telepon');
      expect(child).not.toHaveProperty('ip_address');
      expect(child).not.toHaveProperty('config_id');
      expect(child).not.toHaveProperty('id_pengaduan');

      // Field aman HARUS ada di balasan
      expect(child).toHaveProperty('id');
      expect(child).toHaveProperty('nama');
      expect(child).toHaveProperty('isi');
      expect(child).toHaveProperty('created_at');

      // Tanggal balasan juga harus format Indonesia
      expect(child.created_at).toMatch(indoDatePattern);
    }
  });

  test('sparse fieldset sensitif harus diabaikan', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/premium/issues/6056',
    },
  }, async ({ request }) => {
    // Coba paksa minta field sensitif via sparse fieldset
    const response = await request.get('/internal_api/pengaduan', {
      params: { 'fields[pengaduan]': 'id,nik,telepon,email,ip_address' },
    });

    expect(response.status()).toBe(200);

    const body = await response.json();
    const data: any[] = body?.data ?? [];

    if (data.length === 0) {
      test.skip(true, 'Tidak ada data pengaduan untuk diuji');
      return;
    }

    const attrs = data[0]?.attributes ?? data[0];

    // Field sensitif TIDAK boleh ada meski diminta eksplisit
    expect(attrs).not.toHaveProperty('nik');
    expect(attrs).not.toHaveProperty('telepon');
    expect(attrs).not.toHaveProperty('email');
    expect(attrs).not.toHaveProperty('ip_address');
  });

});
