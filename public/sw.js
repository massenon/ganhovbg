/* Service worker GANXÔ (v1). Portée : toute l'application.
   - /assets/ganho/* et /build/assets/* (URL versionnées ?v=hash ou hachées) : cache-first dès la 2e visite.
   - Pages publiques : réseau d'abord, repli sur /offline.
   - Jamais en cache : POST, /admin, /assistant/*, /guest/*, espace client, tout ce qui est authentifié. */
const VERSION = 'gn-v3';
const STATIC = VERSION + '-static';
const PAGES = VERSION + '-pages';
const PRECACHE = [
  '/offline',
  '/assets/ganho/css/ganho.css',
  '/assets/ganho/img/icone-appli-192.png',
  '/assets/ganho/fonts/Montserrat-latin-normal.woff2'
];
const NEVER = /^\/(admin|administration|espace-client|customer|assistant|guest|login|logout|register|password|user|api|installer|broadcasting)(\/|$)/;

self.addEventListener('install', e => {
  e.waitUntil(caches.open(STATIC).then(c => Promise.all(PRECACHE.map(u => c.add(new Request(u, {cache: 'reload'})).catch(() => null)))).then(() => self.skipWaiting()));
});
self.addEventListener('activate', e => {
  e.waitUntil(caches.keys().then(ks => Promise.all(ks.filter(k => !k.startsWith(VERSION)).map(k => caches.delete(k)))).then(() => self.clients.claim()));
});
self.addEventListener('fetch', e => {
  const req = e.request;
  if (req.method !== 'GET') return;
  const url = new URL(req.url);
  if (url.origin !== location.origin) return;
  const p = url.pathname;
  if (p.startsWith('/assets/ganho/') || p.startsWith('/build/assets/')) {
    e.respondWith(caches.open(STATIC).then(async c => {
      const hit = await c.match(req);
      if (hit) return hit;
      const res = await fetch(req);
      if (res.ok) c.put(req, res.clone());
      return res;
    }));
    return;
  }
  if (req.mode === 'navigate' && !NEVER.test(p)) {
    e.respondWith(fetch(req).then(res => {
      if (res.ok && !res.headers.get('Set-Cookie') && /text\/html/.test(res.headers.get('Content-Type') || '') && !res.redirected) {
        const copy = res.clone();
        caches.open(PAGES).then(c => c.put(req, copy));
      }
      return res;
    }).catch(async () => (await caches.match(req)) || (await caches.match('/offline'))));
  }
});
