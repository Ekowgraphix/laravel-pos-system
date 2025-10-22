// Service Worker for Laravel POS PWA
const CACHE_NAME = 'laravel-pos-v1.0.0';
const OFFLINE_URL = '/offline.html';

// Assets to cache on install
const STATIC_ASSETS = [
  '/',
  '/offline.html',
  '/css/app.css',
  '/js/app.js',
  '/css/dark-mode.css',
  '/js/dark-mode.js',
  '/manifest.json',
];

// Install event - cache static assets
self.addEventListener('install', (event) => {
  console.log('[Service Worker] Installing...');
  
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      console.log('[Service Worker] Caching static assets');
      return cache.addAll(STATIC_ASSETS).catch((err) => {
        console.error('[Service Worker] Cache addAll error:', err);
      });
    })
  );
  
  self.skipWaiting();
});

// Activate event - clean up old caches
self.addEventListener('activate', (event) => {
  console.log('[Service Worker] Activating...');
  
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames.map((cacheName) => {
          if (cacheName !== CACHE_NAME) {
            console.log('[Service Worker] Deleting old cache:', cacheName);
            return caches.delete(cacheName);
          }
        })
      );
    })
  );
  
  self.clients.claim();
});

// Fetch event - network first, fallback to cache
self.addEventListener('fetch', (event) => {
  // Skip cross-origin requests
  if (!event.request.url.startsWith(self.location.origin)) {
    return;
  }

  // Skip API requests from caching (always fetch fresh)
  if (event.request.url.includes('/api/')) {
    event.respondWith(
      fetch(event.request).catch(() => {
        return new Response(JSON.stringify({ 
          success: false, 
          message: 'You are offline' 
        }), {
          headers: { 'Content-Type': 'application/json' }
        });
      })
    );
    return;
  }

  event.respondWith(
    fetch(event.request)
      .then((response) => {
        // Clone the response
        const responseClone = response.clone();
        
        // Cache successful GET requests
        if (event.request.method === 'GET' && response.status === 200) {
          caches.open(CACHE_NAME).then((cache) => {
            cache.put(event.request, responseClone);
          });
        }
        
        return response;
      })
      .catch(() => {
        // Network failed, try cache
        return caches.match(event.request).then((cachedResponse) => {
          if (cachedResponse) {
            return cachedResponse;
          }
          
          // If HTML page, return offline page
          if (event.request.headers.get('accept').includes('text/html')) {
            return caches.match(OFFLINE_URL);
          }
          
          // Return a default offline response
          return new Response('Offline', {
            status: 503,
            statusText: 'Service Unavailable'
          });
        });
      })
  );
});

// Background sync for offline orders
self.addEventListener('sync', (event) => {
  console.log('[Service Worker] Background sync:', event.tag);
  
  if (event.tag === 'sync-orders') {
    event.waitUntil(syncOrders());
  }
});

async function syncOrders() {
  try {
    // Get pending orders from IndexedDB
    const db = await openDatabase();
    const pendingOrders = await getPendingOrders(db);
    
    // Sync each order
    for (const order of pendingOrders) {
      try {
        const response = await fetch('/api/v1/orders', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + order.token
          },
          body: JSON.stringify(order.data)
        });
        
        if (response.ok) {
          await removeOrder(db, order.id);
          console.log('[Service Worker] Order synced:', order.id);
        }
      } catch (err) {
        console.error('[Service Worker] Failed to sync order:', err);
      }
    }
  } catch (err) {
    console.error('[Service Worker] Background sync failed:', err);
  }
}

// IndexedDB helpers
function openDatabase() {
  return new Promise((resolve, reject) => {
    const request = indexedDB.open('pos-offline', 1);
    
    request.onerror = () => reject(request.error);
    request.onsuccess = () => resolve(request.result);
    
    request.onupgradeneeded = (event) => {
      const db = event.target.result;
      if (!db.objectStoreNames.contains('pending-orders')) {
        db.createObjectStore('pending-orders', { keyPath: 'id', autoIncrement: true });
      }
    };
  });
}

function getPendingOrders(db) {
  return new Promise((resolve, reject) => {
    const transaction = db.transaction(['pending-orders'], 'readonly');
    const store = transaction.objectStore('pending-orders');
    const request = store.getAll();
    
    request.onerror = () => reject(request.error);
    request.onsuccess = () => resolve(request.result);
  });
}

function removeOrder(db, orderId) {
  return new Promise((resolve, reject) => {
    const transaction = db.transaction(['pending-orders'], 'readwrite');
    const store = transaction.objectStore('pending-orders');
    const request = store.delete(orderId);
    
    request.onerror = () => reject(request.error);
    request.onsuccess = () => resolve();
  });
}

// Push notification
self.addEventListener('push', (event) => {
  console.log('[Service Worker] Push notification received');
  
  const data = event.data ? event.data.json() : {};
  const title = data.title || 'Laravel POS';
  const options = {
    body: data.body || 'You have a new notification',
    icon: '/images/icons/icon-192x192.png',
    badge: '/images/icons/icon-96x96.png',
    vibrate: [200, 100, 200],
    tag: data.tag || 'default',
    data: data.url || '/',
    actions: [
      { action: 'open', title: 'Open' },
      { action: 'close', title: 'Close' }
    ]
  };
  
  event.waitUntil(
    self.registration.showNotification(title, options)
  );
});

// Notification click
self.addEventListener('notificationclick', (event) => {
  console.log('[Service Worker] Notification clicked:', event.action);
  
  event.notification.close();
  
  if (event.action === 'open') {
    event.waitUntil(
      clients.openWindow(event.notification.data)
    );
  }
});

// Message from client
self.addEventListener('message', (event) => {
  console.log('[Service Worker] Message received:', event.data);
  
  if (event.data.action === 'skipWaiting') {
    self.skipWaiting();
  }
  
  if (event.data.action === 'clearCache') {
    event.waitUntil(
      caches.keys().then((cacheNames) => {
        return Promise.all(
          cacheNames.map((cacheName) => caches.delete(cacheName))
        );
      })
    );
  }
});

console.log('[Service Worker] Loaded successfully');
