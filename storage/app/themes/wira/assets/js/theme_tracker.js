/**
 * Perwira Theme Installation Tracker
 * Sends usage data to the theme developer's analytics server.
 */

(function () {
    'use strict';

    // Configuration
    var TRACKING_ENDPOINT = 'https://analytics.digidesa.id/api/theme-tracker';
    var THEME_NAME = 'perwira';
    var THEME_VERSION = 'v2601.0.0';

    // Skip tracking on localhost/dev environments
    var hostname = window.location.hostname;
    var IS_LOCALHOST = hostname === 'localhost' ||
        hostname === '127.0.0.1' ||
        hostname.endsWith('.test') ||
        hostname.endsWith('.local');

    if (IS_LOCALHOST) {
        return; // Don't track dev environments
    }

    // Storage key to prevent excessive requests
    var CACHE_KEY = 'perwira_theme_tracking_sent';
    var CACHE_DURATION = 24 * 60 * 60 * 1000; // 24 hours

    function getTrackingData() {
        return {
            domain: window.location.hostname,
            url: window.location.origin,
            theme: THEME_NAME,
            version: THEME_VERSION,
            referrer: document.referrer || '',
            screenWidth: screen.width,
            screenHeight: screen.height,
            language: navigator.language || '',
            timestamp: new Date().toISOString()
        };
    }

    function markAsTracked() {
        try {
            localStorage.setItem(CACHE_KEY, Date.now().toString());
        } catch (e) {
            // localStorage might be unavailable (private browsing, etc.)
        }
    }

    function wasRecentlyTracked() {
        try {
            var lastTracked = localStorage.getItem(CACHE_KEY);
            if (lastTracked && (Date.now() - parseInt(lastTracked)) < CACHE_DURATION) {
                return true;
            }
        } catch (e) {
            // localStorage unavailable - proceed with tracking
        }
        return false;
    }

    function sendTrackingData() {
        // Check if recently tracked
        if (wasRecentlyTracked()) {
            return;
        }

        var data = getTrackingData();

        // Use fetch as primary method (ensures Content-Type header is sent correctly)
        if (typeof fetch !== 'undefined') {
            fetch(TRACKING_ENDPOINT, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data),
                mode: 'cors',
                keepalive: true
            })
                .then(function (response) {
                    if (response.ok) {
                        markAsTracked();
                    } else {
                        console.warn('[ThemeTracker] Server returned:', response.status);
                    }
                })
                .catch(function (err) {
                    console.warn('[ThemeTracker] Failed to send:', err.message);
                    // Retry once after 30 seconds
                    setTimeout(function () {
                        retrySend(data);
                    }, 30000);
                });
        }
        // Fallback: use sendBeacon (less reliable for JSON but works during page unload)
        else if (navigator.sendBeacon) {
            try {
                var blob = new Blob([JSON.stringify(data)], { type: 'application/json' });
                var success = navigator.sendBeacon(TRACKING_ENDPOINT, blob);
                if (success) {
                    markAsTracked();
                }
            } catch (e) {
                console.warn('[ThemeTracker] sendBeacon failed:', e.message);
            }
        }
        // Last resort: Image pixel (limited data via query params)
        else {
            var img = new Image();
            img.onload = function () { markAsTracked(); };
            img.src = TRACKING_ENDPOINT + '?domain=' + encodeURIComponent(data.domain) +
                '&theme=' + encodeURIComponent(data.theme) +
                '&version=' + encodeURIComponent(data.version) +
                '&t=' + Date.now();
        }
    }

    function retrySend(data) {
        if (wasRecentlyTracked()) return;

        fetch(TRACKING_ENDPOINT, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data),
            mode: 'cors',
            keepalive: true
        })
            .then(function (response) {
                if (response.ok) {
                    markAsTracked();
                }
            })
            .catch(function () {
                // Give up after retry
            });
    }

    // Also send on page unload as a last-chance attempt
    window.addEventListener('beforeunload', function () {
        if (wasRecentlyTracked()) return;
        if (navigator.sendBeacon) {
            var data = getTrackingData();
            var blob = new Blob([JSON.stringify(data)], { type: 'application/json' });
            navigator.sendBeacon(TRACKING_ENDPOINT, blob);
        }
    });

    // Run tracking when page is idle or loaded
    if (typeof requestIdleCallback !== 'undefined') {
        requestIdleCallback(sendTrackingData, { timeout: 5000 });
    } else {
        window.addEventListener('load', function () {
            setTimeout(sendTrackingData, 2000);
        });
    }

})();
