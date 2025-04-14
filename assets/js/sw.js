self.addEventListener("install", (event) => {
	console.log("Service Worker installed.");
	event.waitUntil(self.skipWaiting());
});

self.addEventListener("activate", (event) => {
	console.log("Service Worker activated.");
	event.waitUntil(self.clients.claim());
});

self.addEventListener("fetch", (event) => {
	// Abaikan fetch yang tidak terkait dengan printer
	if (!event.request.url.includes("/bluetooth-print")) return;

	event.respondWith(
		new Response("Service Worker handling Bluetooth Print.", {
			status: 200,
			statusText: "OK",
		})
	);
});
