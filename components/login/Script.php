<script>
    document.addEventListener('DOMContentLoaded', function () {
        const roleButtons = document.querySelectorAll('[data-role]');
        const roleInput = document.getElementById('role');

        const identityLabel = document.getElementById('identityLabel');
        const identityInput = document.getElementById('login_id');

        const passwordSection = document.getElementById('passwordSection');
        const passwordInput = document.getElementById('password');
        const togglePassword = document.getElementById('togglePassword');

        const identityConfig = {
            mahasiswa: {
                label: 'NIM',
                placeholder: 'Masukkan NIM',
                inputmode: 'numeric'
            },
            dosen: {
                label: 'NIP',
                placeholder: 'Masukkan NIP',
                inputmode: 'numeric'
            },
            koordinator_magang: {
                label: 'NIP',
                placeholder: 'Masukkan NIP',
                inputmode: 'numeric'
            },
            tendik: {
                label: 'NIP',
                placeholder: 'Masukkan NIP',
                inputmode: 'numeric'
            },
            mitra: {
                label: 'Kode Mitra',
                placeholder: 'Masukkan kode mitra',
                inputmode: 'text'
            }
        };

        const activeClasses = [
            'bg-blue-600',
            'text-white',
            'shadow-md'
        ];

        const inactiveClasses = [
            'bg-blue-50',
            'text-slate-600',
            'hover:bg-blue-100'
        ];

        // Mengubah role dan identitas login.
        roleButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                const selectedRole = button.dataset.role;
                const config = identityConfig[selectedRole];

                if (!config) {
                    return;
                }

                roleInput.value = selectedRole;

                // Perbarui tampilan tombol role.
                roleButtons.forEach(function (item) {
                    const isActive = item === button;

                    item.setAttribute(
                        'aria-checked',
                        isActive ? 'true' : 'false'
                    );

                    if (isActive) {
                        item.classList.add(...activeClasses);
                        item.classList.remove(...inactiveClasses);
                    } else {
                        item.classList.remove(...activeClasses);
                        item.classList.add(...inactiveClasses);
                    }
                });

                // Perbarui label dan input identitas.
                identityLabel.textContent = config.label;
                identityInput.placeholder = config.placeholder;
                identityInput.inputMode = config.inputmode;
                identityInput.value = '';

                // Mitra tidak menggunakan password.
                const isMitra = selectedRole === 'mitra';

                passwordSection.hidden = isMitra;
                passwordInput.required = !isMitra;

                // Reset password saat berganti role.
                passwordInput.value = '';
                passwordInput.type = 'password';

                if (togglePassword) {
                    togglePassword.setAttribute(
                        'aria-label',
                        'Tampilkan password'
                    );

                    togglePassword.setAttribute(
                        'aria-pressed',
                        'false'
                    );
                }
            });
        });

        // Tampilkan atau sembunyikan password.
        if (passwordInput && togglePassword) {
            togglePassword.addEventListener('click', function () {
                const showPassword =
                    passwordInput.type === 'password';

                passwordInput.type =
                    showPassword ? 'text' : 'password';

                togglePassword.setAttribute(
                    'aria-label',
                    showPassword
                        ? 'Sembunyikan password'
                        : 'Tampilkan password'
                );

                togglePassword.setAttribute(
                    'aria-pressed',
                    showPassword ? 'true' : 'false'
                );
            });
        }
    });
</script>