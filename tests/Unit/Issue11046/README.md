# Unit Tests untuk Issue #11046 - Pantau Server Down Fix

## Overview

Unit tests untuk mengverifikasi implementasi Circuit Breaker Pattern, Retry Logic dengan Exponential Backoff, dan mekanisme Graceful Degradation ketika Pantau server down.

## Test Files

### 1. `PantauCircuitBreakerTest.php`
Tests untuk circuit breaker helper functions:
- `pantau_is_down()` - Cek status circuit breaker
- `mark_pantau_down()` - Tandai pantau sebagai down
- `reset_pantau_status()` - Reset status pantau
- `get_pantau_attempt_count()` - Ambil jumlah attempt

**Test Cases:**
- Initial state: pantau should be up
- Circuit breaker activation: after marking down
- Backoff periods: 1 min (attempt 1-3), 5 min (attempt 4-6), 30 min (attempt 7+)
- Attempt counting
- Status reset
- Lifecycle test: down → wait → up
- Escalating backoff verification

### 2. `PantauRetryLogicTest.php`
Tests untuk retry logic di httpPost(), opendk_api(), dan get_data_desa():
- Circuit breaker check sebelum request
- Retry attempts dengan backoff
- Status reset pada success
- Marking down setelah semua retry gagal
- Exponential backoff timing

**Test Cases:**
- Successful request returns response
- Circuit breaker prevents retry
- Status reset on success
- Marking down after retries
- Exponential backoff calculation
- Cache key consistency

### 3. `TrackerPantauTest.php`
Tests untuk Tracker.php dan integrasi circuit breaker:
- kirimData() skips saat pantau down
- kirimData() attempts send saat pantau up
- Cascading requests respect circuit breaker
- Graceful degradation during outage
- Recovery mechanism
- Multiple components trigger circuit breaker

**Test Cases:**
- Config-based early returns
- Circuit breaker integration
- Thundering herd prevention
- Recovery after backoff
- Environment-specific behavior
- Concurrent request handling

### 4. `TestHelpers.php`
Helper functions untuk testing:
- `simulatePantauDown()` - Simulate pantau down
- `simulatePantauRecovery()` - Simulate pantau recovery
- `isPantauDown()` - Check pantau status
- `getAttemptCount()` - Get attempt count
- `simulateConcurrentRequests()` - Simulate multiple requests
- `verifyCircuitBreakerUnderLoad()` - Load testing

## Cara Menjalankan Tests

### Run All Tests untuk Issue #11046
```bash
./vendor/bin/phpunit tests/Unit/Issue11046/
```

### Run Specific Test File
```bash
# Circuit Breaker Tests
./vendor/bin/phpunit tests/Unit/Issue11046/PantauCircuitBreakerTest.php

# Retry Logic Tests
./vendor/bin/phpunit tests/Unit/Issue11046/PantauRetryLogicTest.php

# Tracker Tests
./vendor/bin/phpunit tests/Unit/Issue11046/TrackerPantauTest.php
```

### Run Specific Test Method
```bash
./vendor/bin/phpunit tests/Unit/Issue11046/PantauCircuitBreakerTest.php::PantauCircuitBreakerTest::test_pantau_is_down_returns_false_initially
```

### Run With Verbose Output
```bash
./vendor/bin/phpunit tests/Unit/Issue11046/ -v
```

### Run With Coverage Report
```bash
./vendor/bin/phpunit tests/Unit/Issue11046/ --coverage-html=coverage/
```

### Run With Filter
```bash
# Jalankan hanya test yang namanya mengandung "circuit"
./vendor/bin/phpunit tests/Unit/Issue11046/ --filter="circuit"

# Jalankan hanya test yang namanya mengandung "backoff"
./vendor/bin/phpunit tests/Unit/Issue11046/ --filter="backoff"
```

## Test Coverage

### Circuit Breaker (PantauCircuitBreakerTest)
- ✅ Initial state verification
- ✅ Down state activation
- ✅ Backoff time calculation (1 min, 5 min, 30 min)
- ✅ Attempt counter tracking
- ✅ Status reset mechanism
- ✅ Lifecycle management
- ✅ Data provider test dengan multiple attempts

### Retry Logic (PantauRetryLogicTest)
- ✅ Successful requests
- ✅ Circuit breaker bypass
- ✅ Status reset after success
- ✅ Marking down after retries
- ✅ Exponential backoff timing
- ✅ Consecutive failures escalation
- ✅ get_data_desa() circuit breaker
- ✅ Cache key consistency

### Tracker Integration (TrackerPantauTest)
- ✅ Configuration handling
- ✅ Circuit breaker skip behavior
- ✅ Normal send attempts
- ✅ Thundering herd prevention
- ✅ Cascade handling
- ✅ Multiple backoff periods
- ✅ Recovery mechanism
- ✅ Graceful degradation

## Expected Results

Semua tests seharusnya **PASS** ✅

```
OK (X tests, Y assertions)
```

Jika ada test yang FAIL, cek:
1. Cache system sudah dikonfigurasi dengan benar
2. Helper functions sudah dimuat
3. Database migrations sudah dijalankan (jika ada)

## Test Data & Assertions

### Backoff Data Provider
```
Attempt 1-3 → Cache 1 minute (50-60 seconds)
Attempt 4-6 → Cache 5 minutes (290-300 seconds)
Attempt 7+ → Cache 30 minutes (1790-1800 seconds)
```

### Exponential Backoff Calculation
```
Attempt 1 → 2^0 = 1 second
Attempt 2 → 2^1 = 2 seconds
Attempt 3 → 2^2 = 4 seconds
```

## Monitoring & Debugging

### Enable PHPUnit Debug Output
```bash
./vendor/bin/phpunit tests/Unit/Issue11046/ --debug
```

### Run Single Test with Detailed Output
```bash
./vendor/bin/phpunit tests/Unit/Issue11046/PantauCircuitBreakerTest.php::PantauCircuitBreakerTest::test_mark_pantau_down_sets_1_minute_backoff_for_attempt_1_to_3 -v
```

### Check Test File Syntax
```bash
php -l tests/Unit/Issue11046/PantauCircuitBreakerTest.php
```

## Integration Tests

Untuk full integration testing dengan actual HTTP requests (optional):

```bash
# Feature tests (jika ada di folder tests/Feature/)
./vendor/bin/phpunit tests/Feature/Issue11046/

# End-to-end tests dengan Playwright
./vendor/bin/playwright test tests/playwright/issue-11046/
```

## Continuous Integration

Untuk CI/CD pipeline, gunakan:

```yaml
# .github/workflows/test.yml
- name: Run Issue #11046 Tests
  run: ./vendor/bin/phpunit tests/Unit/Issue11046/ --coverage-html=coverage/
```

## Troubleshooting

### Test Timeout
Jika test timeout, adjust di `phpunit.xml`:
```xml
<ini name="default_socket_timeout" value="30"/>
```

### Cache Issues
```bash
# Clear cache sebelum test
php artisan cache:clear

# Verify cache config
php artisan config:cache
```

### Missing Dependencies
```bash
composer install
composer dump-autoload
```

## Notes

1. **Tests are idempotent**: Bisa dijalankan berkali-kali dengan hasil yang sama
2. **Cache is flushed**: Setiap test setUp() membersihkan cache
3. **No database required**: Hanya test cache behavior, bukan database
4. **Async-safe**: Tests tidak tergantung timing (menggunakan cache TTL)

## Related Files

- Implementation: `donjo-app/helpers/opensid_helper.php`
- Implementation: `donjo-app/helpers/donjolib_helper.php`
- Implementation: `app/Libraries/Tracker.php`
- Documentation: `ISSUE_11046_FIX_DOCUMENTATION.md`

---

**Issue**: https://github.com/OpenSID/OpenSID/issues/11046
**Version**: OpenSID Premium v2604.0.0+
**Date**: 2026-04-21
