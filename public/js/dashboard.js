function toggleSidebar() {

    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');

    sidebar.classList.toggle('open');
    overlay.classList.toggle('show');

}
// PWA Service Worker
if ('serviceWorker' in navigator) {

    window.addEventListener('load', function () {

        navigator.serviceWorker
            .register('/sw.js')
            .then(function (registration) {
                console.log('ServiceWorker registered');
            })
            .catch(function (error) {
                console.log('ServiceWorker registration failed:', error);
            });

    });

}
