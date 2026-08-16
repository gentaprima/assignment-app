const CACHE_NAME = 'tomoro-absensi-v1';

const STATIC_ASSETS = [
    '/icons/icon-192.png',
    '/icons/icon-512.png',
];

self.addEventListener('install', event => {
    console.log('Service Worker: installing');

    self.skipWaiting();

    event.waitUntil(
        caches.open(CACHE_NAME).then(async cache => {
            for (const asset of STATIC_ASSETS) {
                try {
                    await cache.add(asset);
                    console.log('Cached:', asset);
                } catch (error) {
                    console.warn('Gagal cache:', asset, error);
                }
            }
        })
    );
});

self.addEventListener('activate', event => {
    console.log('Service Worker: activated');

    event.waitUntil(
        caches.keys().then(keys => {
            return Promise.all(
                keys
                    .filter(key => key !== CACHE_NAME)
                    .map(key => caches.delete(key))
            );
        })
    );

    self.clients.claim();
});

self.addEventListener('fetch', event => {
    if (event.request.method !== 'GET') {
        return;
    }

    // Jangan intercept request halaman Laravel dulu.
    // Fokus PWA ke asset/static file.
    const url = new URL(event.request.url);

    if (url.origin !== self.location.origin) {
        return;
    }

    // Hanya cache asset statis
    const isStaticAsset =
        url.pathname.startsWith('/icons/') ||
        url.pathname.startsWith('/build/') ||
        url.pathname.startsWith('/css/') ||
        url.pathname.startsWith('/js/');

    if (!isStaticAsset) {
        return;
    }

    event.respondWith(
        caches.match(event.request).then(cachedResponse => {
            if (cachedResponse) {
                return cachedResponse;
            }

            return fetch(event.request)
                .then(response => {
                    if (!response || response.status !== 200) {
                        return response;
                    }

                    const responseClone = response.clone();

                    caches.open(CACHE_NAME).then(cache => {
                        cache.put(event.request, responseClone);
                    });

                    return response;
                });
        })
    );
});