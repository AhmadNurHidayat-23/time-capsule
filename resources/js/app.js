import {startCountdown} from './clock';
const countdown = document.getElementById('countdown');
if (countdown) startCountdown(countdown);
const body = document.getElementById('body');
if (body) body.addEventListener('input', () => {
    document.getElementById('character-count').textContent = `${body.value.length.toLocaleString('id-ID')} / 20.000`;
});
document.getElementById('copy-link')?.addEventListener('click', async () => {
    const input = document.getElementById('share-link');
    const status = document.getElementById('copy-status');
    try {
        await navigator.clipboard.writeText(input.value);
        status.textContent = 'Tautan berhasil disalin. Simpan di tempat yang aman.';
    } catch {
        input.focus(); input.select();
        status.textContent = 'Tautan sudah dipilih. Tekan Ctrl+C atau salin secara manual.';
    }
});
document.getElementById('capsule-form')?.addEventListener('submit', event => {
    const button = event.currentTarget.querySelector('button[type="submit"]');
    button.disabled = true;
    button.textContent = 'Menyimpan pesan…';
});
window.addEventListener('pageshow', () => {
    const button = document.querySelector('#capsule-form button[type="submit"]');
    if (button) { button.disabled = false; button.textContent = 'Simpan Pesan ↗'; }
});
