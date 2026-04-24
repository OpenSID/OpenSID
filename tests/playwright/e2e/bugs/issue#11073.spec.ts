import { test, expect } from '@playwright/test';
import path from 'path';

test.use({
    storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Status perkawinan pada isian 408 DTSEN tidak otomatis terisi berdasarkan data penduduk #11073', () => {
    test('fix: perbaiki Status perkawinan pada isian 408 DTSEN tidak otomatis terisi berdasarkan data penduduk #11073', {
        annotation: {
            type: 'issue',
            description: 'https://github.com/OpenSID/OpenSID/issues/11073',
        },
    }, async ({ page }) => {
        // Open DTSEN form tab 4
        await page.goto('/dtsen/pendataan/form/4');

        // Wait for family members table to be visible
        const table = page.locator('#tabel_art_dtsen');
        await expect(table).toBeVisible();

        // Get first member row and its name
        const firstRow = table.locator('tbody tr').first();
        const nameCell = firstRow.locator('td').nth(1); // assuming name is in second column
        const memberName = (await nameCell.innerText()).trim();

        // Open modal for this member
        await firstRow.locator('text=Lihat').click();
        const modal = page.locator('#modal-tab4');
        await expect(modal).toBeVisible();

        // Verify dynamic name replacement in placeholders
        const namePlaceholders = modal.locator('.ganti-nama');
        const count = await namePlaceholders.count();
        for (let i = 0; i < count; i++) {
            const txt = await namePlaceholders.nth(i).innerText();
            expect(txt).toContain(memberName);
        }

        // Verify marital status (field 408) is auto‑filled and enabled
        const statusSelect = modal.locator('#pilihan_4_408');
        await expect(statusSelect).not.toBeDisabled();
        const selectedValue = await statusSelect.evaluate((el: HTMLSelectElement) => el.value);
        expect(selectedValue).not.toBe('');
        // Optional: compare with master data stored in row attribute
        const masterStatus = await firstRow.getAttribute('data-status');
        if (masterStatus) {
            expect(selectedValue).toBe(masterStatus);
        }

        // Close modal
        await modal.locator('.close').click();
        await page.waitForTimeout(300);
    });
});