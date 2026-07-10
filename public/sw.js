const CACHE_NAME = 'drgmaskapai-v1';
const TICKET_CACHE = 'eticket-cache';

self.addEventListener('fetch', (event) => {
    const url = new URL(event.request.url);
    
    // Cache First untuk e-ticket
    if (url.pathname.match(/\/bookings\/.*\/ticket/)) {
        event.respondWith(
            caches.open(TICKET_CACHE).then((cache) => {
                return cache.match(event.request).then((response) => {
                    return response || fetch(event.request).then((response) => {
                        cache.put(event.request, response.clone());
                        return response;
                    });
                });
            })
        );
    }
});