export class ServerClock {
    constructor(serverNow, opensAt, monotonic = () => performance.now()) {
        this.monotonic = monotonic;
        this.opensAt = Number(opensAt);
        this.sync(Number(serverNow));
    }
    sync(serverNow, roundTrip = 0) {
        this.serverNow = serverNow + Math.max(0, roundTrip) / 2;
        this.anchor = this.monotonic();
    }
    remaining() { return Math.max(0, this.opensAt - (this.serverNow + this.monotonic() - this.anchor)); }
    parts() {
        const total = Math.ceil(this.remaining() / 1000);
        return {days:Math.floor(total / 86400),hours:Math.floor(total / 3600) % 24,minutes:Math.floor(total / 60) % 60,seconds:total % 60};
    }
}
export function startCountdown(root) {
    const clock = new ServerClock(root.dataset.serverNow, root.dataset.opensAt);
    const status = document.getElementById('clock-status');
    let pending = false, lastCheck = -Infinity, lastSuccess = performance.now();
    const render = () => {
        const parts = clock.parts();
        root.classList.toggle('long-countdown', String(parts.days).length > 3);
        Object.entries(parts).forEach(([key,value]) => { root.querySelector(`[data-${key}]`).textContent = String(value).padStart(2,'0'); });
        if (clock.remaining() === 0) {
            status.textContent = 'Memverifikasi waktu pembukaan dengan server…';
            if (performance.now() - lastCheck >= 5000) check();
        }
    };
    const check = async () => {
        if (pending || document.hidden) return;
        pending = true; lastCheck = performance.now();
        const start = performance.now();
        try {
            const response = await fetch(root.dataset.statusUrl,{cache:'no-store',headers:{Accept:'application/json'},signal:AbortSignal.timeout(10000)});
            if (!response.ok) throw new Error();
            const state = await response.json();
            clock.sync(state.serverNow, performance.now() - start);
            lastSuccess = performance.now();
            if (state.unlocked === true) { window.location.reload(); return; }
            status.textContent = 'Waktu tersinkron dengan server. Pesan masih terkunci.';
        } catch {
            status.textContent = 'Koneksi ke server tertunda. Pembukaan tetap menunggu verifikasi server.';
        } finally { pending = false; }
    };
    render(); check();
    setInterval(() => {
        render();
        if (performance.now() - lastSuccess > 30000 && performance.now() - lastCheck > 10000) check();
    },1000);
    setInterval(check,30000);
    document.addEventListener('visibilitychange', () => { if (!document.hidden) check(); });
}
