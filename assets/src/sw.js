importScripts('/assets/third_party/workbox-v5.1.4/workbox-sw.js');
workbox.setConfig({
  modulePathPrefix: '/assets/third_party/workbox-v5.1.4/'
});
workbox.setConfig({ debug: false });
self.__WB_DISABLE_DEV_LOGS = false;
//This immediately deploys the service worker w/o requiring a refresh
workbox.core.skipWaiting();
workbox.core.clientsClaim();

workbox.precaching.precacheAndRoute(self.__WB_MANIFEST.concat([    
    { "revision": "a3erfddfd1234", "url": "./manifest.json" },
    { "revision": "asfdfdaerere", "url": "./assets/images/icon/icon-192x192.png" },
    { "revision": "asfdfdaerere", "url": "./assets/images/icon/icon-256x256.png" },
    { "revision": "asfdfdaerere", "url": "./assets/images/icon/icon-384x384.png" },
    { "revision": "asfdfdaerere", "url": "./assets/images/icon/icon-512x512.png" },
    { "revision": "asfdfdaerere", "url": "./assets/images/icon/maskable_icon.png" },
    
]));
workbox.routing.registerRoute(new RegExp('/'), new workbox.strategies.NetworkFirst({
                cacheName: 'opensid-pwa',
                plugins: [
                    new workbox.expiration.ExpirationPlugin({
                        maxEntries: 60,
                        maxAgeSeconds: 30 * 24 * 60 * 60, // 30 hari
                    }),
                ],
    })
);

workbox.routing.registerRoute(
    /\.(?:png|jpg|jpeg|svg|gif)$/,
    new workbox.strategies.CacheFirst({
        cacheName: 'images',
        plugins: [
            new workbox.expiration.ExpirationPlugin({
                maxEntries: 150,
                maxAgeSeconds: 30 * 24 * 60 * 60, // 30 hari
            }),
        ],
    })
);

workbox.routing.registerRoute(
    /\.(?:woff2|woff)$/,
    new workbox.strategies.CacheFirst({
        cacheName: 'font-icon',
        plugins: [            
            new workbox.expiration.ExpirationPlugin({
                maxEntries: 25,
                maxAgeSeconds: 30 * 24 * 60 * 60, // 30 hari
            }),
        ],
    })
);