const CACHE_NAME = 'pos-toko-baju-v1';
const urlsToCache = [
    '/pos',
    '/assets/plugins/global/plugins.bundle.css',
    '/assets/css/style.bundle.css',
    '/assets/plugins/global/plugins.bundle.js',
    '/assets/js/scripts.bundle.js'
];

self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => {
                return cache.addAll(urlsToCache);
            })
    );
});

self.addEventListener('fetch', event => {
    // Only handle GET requests
    if (event.request.method !== 'GET') return;
    
    // Network first, fallback to cache
    event.respondWith(
        fetch(event.request)
            .then(response => {
                // Clone and cache the response if it's successful
                if (response && response.status === 200 && response.type === 'basic') {
                    const responseToCache = response.clone();
                    caches.open(CACHE_NAME)
                        .then(cache => {
                            cache.put(event.request, responseToCache);
                        });
                }
                return response;
            })
            .catch(() => {
                return caches.match(event.request);
            })
    );
});
