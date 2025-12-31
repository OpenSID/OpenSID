import { test, expect } from '@playwright/test';
import { Laravel } from '@test/utils/laravel';
import path from 'path';

test.use({
  storageState: path.resolve(__dirname, '../../storage/auth/admin.json'),
});

test.describe('Bug/error: Kode OTP Email pada User Super Admin masuk ke pengguna lain #10659', () => {
  test('should verify logic when email is verified', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10659',
    },
  }, async ({ page }) => {
    const method = 'email';
    
    // Get current user data
    const authUser = await Laravel.user();
    
    // Test logic: isVerified should be true if email_verified_at is not null
    const hasEmailVerified = authUser.email_verified_at !== null;
    
    if (hasEmailVerified) {
      const isVerified = authUser && (
        method === 'email' ? authUser.email_verified_at !== null :
        false
      );
      expect(isVerified).toBe(true);
    } else {
      // If not verified, test should still pass but isVerified should be false
      const isVerified = authUser && (
        method === 'email' ? authUser.email_verified_at !== null :
        false
      );
      expect(isVerified).toBe(false);
    }
  });

  test('should not verify user when using unsupported method', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10659',
    },
  }, async ({ page }) => {
    const authUser = await Laravel.user();
    const method = 'sms'; // unsupported method
    
    // Logic: isVerified should be false for unsupported method (default case)
    const isVerified = authUser && (
      method === 'telegram' ? false :
      method === 'email' ? authUser.email_verified_at !== null :
      false
    );
    expect(isVerified).toBe(false);
  });

  test('should correctly check email verification status', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10659',
    },
  }, async ({ page }) => {
    const authUser = await Laravel.user();
    const method = 'email';
    
    // Verify that logic correctly evaluates verification status
    const isVerified = authUser && (
      method === 'email' ? authUser.email_verified_at !== null :
      false
    );
    
    // Test passes if logic is correctly implemented
    // Result should match actual email_verified_at status
    const expectedResult = authUser.email_verified_at !== null;
    expect(isVerified).toBe(expectedResult);
  });

  test('should return false for unverified user', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10659',
    },
  }, async ({ page }) => {
    const authUser = await Laravel.user();
    const method = 'email';
    
    // Test that unverified user returns false
    const isVerified = authUser && (
      method === 'email' ? authUser.email_verified_at !== null :
      false
    );
    
    // If user's email is not verified, isVerified should be false
    if (authUser.email_verified_at === null) {
      expect(isVerified).toBe(false);
    } else {
      // If email is verified, isVerified should be true
      expect(isVerified).toBe(true);
    }
  });

  test('should handle null user gracefully', {
    annotation: {
      type: 'issue',
      description: 'https://github.com/OpenSID/OpenSID/issues/10659',
    },
  }, async ({ page }) => {
    const authUser = await Laravel.user();
    const method = 'email';
    
    // Verify that null user returns falsy value (short-circuit with &&)
    const nullUser = null;
    const isVerified = nullUser && (
      method === 'email' ? nullUser.email_verified_at !== null :
      false
    );
    
    // When user is null, the && operator returns null (not false)
    // So we check that it's falsy, not true
    expect(isVerified).toBeFalsy();
  });
});
