const CACHE_NAME = 'diary-cache-v1';
const urlsToCache = [
  '/',
  '/Diary/index.php',
  '/Diary/view_entry.php',
  '/Diary/edit_entry.php',
  '/Diary/add_entry.php',
  '/Diary/manifest.json',
  '/Diary/icon-192x192.png',
  '/Diary/icon-512x512.png',
  'https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css',
  'https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css'
];

self.addEventListener('install', event => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        return cache.addAll(urlsToCache).catch(err => {
          console.error('Failed to cache resources:', err);
        });
      })
  );
});

self.addEventListener('fetch', event => {
  event.respondWith(
    caches.match(event.request)
      .then(response => {
        return response || fetch(event.request);
      })
  );
});
