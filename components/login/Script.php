<script>
    // Pilih peran
    const roleButtons = document.querySelectorAll('[data-role]');
    const roleInput = document.getElementById('role');
    const aktif = ['bg-blue-600', 'text-white', 'shadow-md'];
    const nonaktif = ['bg-blue-50', 'text-slate-600', 'hover:bg-blue-100'];

    roleButtons.forEach((btn) => {
        btn.addEventListener('click', () => {
            roleButtons.forEach((b) => {
                b.classList.remove(...aktif);
                b.classList.add(...nonaktif);
                b.setAttribute('aria-checked', 'false');
            });
            btn.classList.remove(...nonaktif);
            btn.classList.add(...aktif);
            btn.setAttribute('aria-checked', 'true');
            roleInput.value = btn.dataset.role;
        });
    });

    // Tampilkan / sembunyikan password
    const pw = document.getElementById('password');
    document.getElementById('togglePassword').addEventListener('click', (e) => {
        const tampil = pw.type === 'password';
        pw.type = tampil ? 'text' : 'password';
        e.currentTarget.setAttribute('aria-label', tampil ? 'Sembunyikan password' : 'Tampilkan password');
    });
</script>