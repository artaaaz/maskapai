const CACHE_NAME = 'drg-maskapai-v1';
const STATIC_ASSETS = [
    '/',
    '/offline',
    '/manifest.json',
    '/favicon.ico',
    '/icons/icon-72x72.png',
    '/icons/icon-96x96.png',
    '/icons/icon-128x128.png',
    '/icons/icon-144x144.png',
    '/icons/icon-152x152.png',
    '/icons/icon-192x192.png',
    '/icons/icon-384x384.png',
    '/icons/icon-512x512.png',
];

// Install event - cache static assets
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then((cache) => {
                return cache.addAll(STATIC_ASSETS);
            })
            .then(() => {
                return self.skipWaiting();
            })
    );
});

// Activate event - clean old caches
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames
                    .filter((name) => name !== CACHE_NAME)
                    .map((name) => caches.delete(name))
            );
        }).then(() => {
            return self.clients.claim();
        })
    );
});

// Fetch event - Network First for pages, Cache First for assets
self.addEventListener('fetch', (event) => {
    const request = event.request;
    const url = new URL(request.url);

    // Skip non-GET requests
    if (request.method !== 'GET') return;

    // Skip API calls and Midtrans
    if (url.pathname.startsWith('/api/') || 
        url.hostname.includes('midtrans') ||
        url.pathname.startsWith('/midtrans/')) {
        return;
    }

    // Cache First for static assets
    if (
        url.pathname.match(/\.(css|js|png|jpg|jpeg|gif|svg|ico|woff2?|ttf|eot)$/) ||
        url.pathname === '/manifest.json' ||
        url.pathname.startsWith('/icons/') ||
        url.pathname.startsWith('/build/assets/')
    ) {
        event.respondWith(
            caches.match(request)
                .then((cachedResponse) => {
                    if (cachedResponse) {
                        return cachedResponse;
                    }
                    return fetch(request).then((response) => {
                        return caches.open(CACHE_NAME).then((cache) => {
                            cache.put(request, response.clone());
                            return response;
                        });
                    });
                })
                .catch(() => {
                    return caches.match('/offline');
                })
        );
        return;
    }

    // Network First for HTML pages
    event.respondWith(
        fetch(request)
            .then((response) => {
                // Cache the response for offline use
                const clone = response.clone();
                caches.open(CACHE_NAME).then((cache) => {
                    cache.put(request, clone);
                });
                return response;
            })
            .catch(() => {
                // If offline, try cache first, then offline page
                return caches.match(request)
                    .then((cachedResponse) => {
                        if (cachedResponse) {
                            return cachedResponse;
                        }
                        // If it's a page navigation, show offline page
                        if (request.mode === 'navigate') {
                            return caches.match('/offline');
                        }
                        return new Response('Offline', { status: 503 });
                    });
            })
    );
});