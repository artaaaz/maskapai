<div id="pwa-install-container" class="fixed bottom-6 left-1/2 -translate-x-1/2 z-50" style="display:none;">
    <div class="bg-white rounded-2xl shadow-2xl border border-slate-200 p-4 flex items-center gap-4 max-w-sm mx-auto">
        <div class="w-12 h-12 bg-gradient-to-br from-blue-600 to-blue-700 rounded-xl flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
            </svg>
        </div>
        <div class="flex-1 min-w-0">
            <p class="font-bold text-slate-800 text-sm">Install drgMaskapai</p>
            <p class="text-xs text-slate-500 truncate">Akses cepat dari layar utama</p>
        </div>
        <button id="pwa-install-btn" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl text-sm transition-colors whitespace-nowrap">
            Install
        </button>
        <button id="pwa-install-close" class="p-1 hover:bg-slate-100 rounded-lg transition-colors flex-shrink-0" aria-label="Tutup">
            <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    let deferredPrompt = null;
    const container = document.getElementById('pwa-install-container');
    const installBtn = document.getElementById('pwa-install-btn');
    const closeBtn = document.getElementById('pwa-install-close');

    // Check if already installed
    if (window.matchMedia('(display-mode: standalone)').matches) {
        return;
    }

    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredPrompt = e;
        container.style.display = 'block';
    });

    installBtn.addEventListener('click', async () => {
        if (!deferredPrompt) return;
        deferredPrompt.prompt();
        const result = await deferredPrompt.userChoice;
        if (result.outcome === 'accepted') {
            container.style.display = 'none';
        }
        deferredPrompt = null;
    });

    closeBtn.addEventListener('click', () => {
        container.style.display = 'none';
    });

    // Hide if app is already installed
    window.addEventListener('appinstalled', () => {
        container.style.display = 'none';
    });
});
</script>
</div>