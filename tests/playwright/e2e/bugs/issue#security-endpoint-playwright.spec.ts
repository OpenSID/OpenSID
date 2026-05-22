import { test, expect } from '@playwright/test';

test.describe('PlaywrightController: assert 404 di environment non-development/testing', () => {
    test('POST /playwright/artisan harus 404 dengan payload valid', {
        annotation: {
            type: 'issue',
            description: 'PlaywrightController@artisan hanya aktif di environment development/testing',
        },
    }, async ({ page }) => {
        const response = await page.request.post('/playwright/artisan', {
            data: { command: 'inspire', parameters: [] },
        });

        expect(response.status()).toBe(404);
    });

    test('POST /playwright/artisan harus 404 tanpa payload', {
        annotation: {
            type: 'issue',
            description: 'PlaywrightController@artisan hanya aktif di environment development/testing',
        },
    }, async ({ page }) => {
        const response = await page.request.post('/playwright/artisan');

        expect(response.status()).toBe(404);
    });

    test('POST /playwright/artisan harus 404 dengan payload tidak lengkap (tanpa command)', {
        annotation: {
            type: 'issue',
            description: 'PlaywrightController@artisan hanya aktif di environment development/testing',
        },
    }, async ({ page }) => {
        const response = await page.request.post('/playwright/artisan', {
            data: { parameters: ['--force'] },
        });

        expect(response.status()).toBe(404);
    });

    test('POST /playwright/user harus 404 tanpa payload', {
        annotation: {
            type: 'issue',
            description: 'PlaywrightController@user hanya aktif di environment development/testing',
        },
    }, async ({ page }) => {
        const response = await page.request.post('/playwright/user');

        expect(response.status()).toBe(404);
    });

    test('POST /playwright/user harus 404 dengan payload apapun', {
        annotation: {
            type: 'issue',
            description: 'PlaywrightController@user hanya aktif di environment development/testing',
        },
    }, async ({ page }) => {
        const response = await page.request.post('/playwright/user', {
            data: { foo: 'bar' },
        });

        expect(response.status()).toBe(404);
    });

    test('POST /playwright/query harus 404 dengan payload valid (statement)', {
        annotation: {
            type: 'issue',
            description: 'PlaywrightController@query hanya aktif di environment development/testing',
        },
    }, async ({ page }) => {
        const response = await page.request.post('/playwright/query', {
            data: { query: 'SELECT 1', bindings: [], unprepared: false },
        });

        expect(response.status()).toBe(404);
    });

    test('POST /playwright/query harus 404 dengan payload valid (unprepared)', {
        annotation: {
            type: 'issue',
            description: 'PlaywrightController@query hanya aktif di environment development/testing',
        },
    }, async ({ page }) => {
        const response = await page.request.post('/playwright/query', {
            data: { query: 'SET NAMES utf8mb4', unprepared: true },
        });

        expect(response.status()).toBe(404);
    });

    test('POST /playwright/query harus 404 tanpa payload', {
        annotation: {
            type: 'issue',
            description: 'PlaywrightController@query hanya aktif di environment development/testing',
        },
    }, async ({ page }) => {
        const response = await page.request.post('/playwright/query');

        expect(response.status()).toBe(404);
    });

    test('POST /playwright/query harus 404 dengan payload tidak lengkap (tanpa query)', {
        annotation: {
            type: 'issue',
            description: 'PlaywrightController@query hanya aktif di environment development/testing',
        },
    }, async ({ page }) => {
        const response = await page.request.post('/playwright/query', {
            data: { connection: 'mysql', bindings: [] },
        });

        expect(response.status()).toBe(404);
    });

    test('POST /playwright/query harus 404 dengan connection eksplisit', {
        annotation: {
            type: 'issue',
            description: 'PlaywrightController@query hanya aktif di environment development/testing',
        },
    }, async ({ page }) => {
        const response = await page.request.post('/playwright/query', {
            data: { connection: 'mysql', query: 'SELECT 1' },
        });

        expect(response.status()).toBe(404);
    });

    test('POST /playwright/select harus 404 dengan payload valid', {
        annotation: {
            type: 'issue',
            description: 'PlaywrightController@select hanya aktif di environment development/testing',
        },
    }, async ({ page }) => {
        const response = await page.request.post('/playwright/select', {
            data: { query: 'SELECT 1 AS result', bindings: [] },
        });

        expect(response.status()).toBe(404);
    });

    test('POST /playwright/select harus 404 tanpa payload', {
        annotation: {
            type: 'issue',
            description: 'PlaywrightController@select hanya aktif di environment development/testing',
        },
    }, async ({ page }) => {
        const response = await page.request.post('/playwright/select');

        expect(response.status()).toBe(404);
    });

    test('POST /playwright/select harus 404 dengan payload tidak lengkap (tanpa query)', {
        annotation: {
            type: 'issue',
            description: 'PlaywrightController@select hanya aktif di environment development/testing',
        },
    }, async ({ page }) => {
        const response = await page.request.post('/playwright/select', {
            data: { connection: 'mysql', bindings: [] },
        });

        expect(response.status()).toBe(404);
    });

    test('POST /playwright/select harus 404 dengan connection eksplisit', {
        annotation: {
            type: 'issue',
            description: 'PlaywrightController@select hanya aktif di environment development/testing',
        },
    }, async ({ page }) => {
        const response = await page.request.post('/playwright/select', {
            data: { connection: 'mysql', query: 'SELECT * FROM users LIMIT 1' },
        });

        expect(response.status()).toBe(404);
    });

    test('POST /playwright/select harus 404 dengan bindings parameter', {
        annotation: {
            type: 'issue',
            description: 'PlaywrightController@select hanya aktif di environment development/testing',
        },
    }, async ({ page }) => {
        const response = await page.request.post('/playwright/select', {
            data: { query: 'SELECT * FROM users WHERE id = ?', bindings: [1] },
        });

        expect(response.status()).toBe(404);
    });
});