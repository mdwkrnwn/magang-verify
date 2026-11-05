<script>
    document.addEventListener('DOMContentLoaded', function() {

        // ==========================================================
        // ELEMENT FORM
        // ==========================================================

        const roleButtons = document.querySelectorAll('[data-role]');

        const roleInput = document.getElementById('role');
        const identityLabel = document.getElementById('identityLabel');
        const identityInput = document.getElementById('login_id');

        const passwordSection = document.getElementById('passwordSection');
        const passwordInput = document.getElementById('password');
        const togglePassword = document.getElementById('togglePassword');

        const loginForm = document.querySelector('form');
        const loginButton = document.getElementById('loginButton');
        const loginButtonText = document.getElementById('loginButtonText');
        const loginSpinner = document.getElementById('loginSpinner');
        const loginArrow = document.getElementById('loginArrow');


        // ==========================================================
        // KONFIGURASI IDENTITAS ROLE
        // ==========================================================

        const accountConfig = {

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


        // ==========================================================
        // CLASS TOMBOL ROLE
        // ==========================================================

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


        // ==========================================================
        // FUNGSI MEMILIH ROLE
        // ==========================================================

        function selectRole(selectedRole) {

            const config = accountConfig[selectedRole];

            if (!config) {
                console.error('Konfigurasi role tidak ditemukan:', selectedRole);
                return;
            }

            // Simpan role
            roleInput.value = selectedRole;


            // Update tombol role
            roleButtons.forEach(function(button) {

                const isActive = button.dataset.role === selectedRole;

                button.setAttribute(
                    'aria-checked',
                    isActive ? 'true' : 'false'
                );

                if (isActive) {

                    button.classList.add(...activeClasses);
                    button.classList.remove(...inactiveClasses);

                } else {

                    button.classList.remove(...activeClasses);
                    button.classList.add(...inactiveClasses);

                }

            });


            // Update label identitas
            identityLabel.textContent = config.label;

            // Update placeholder
            identityInput.placeholder = config.placeholder;

            // Update input mode
            identityInput.inputMode = config.inputmode;


            // Reset input ketika role berubah
            identityInput.value = '';
            passwordInput.value = '';
            passwordInput.type = 'password';


            // Pengaturan khusus role Mitra
            const isMitra = selectedRole === 'mitra';

            passwordSection.hidden = isMitra;
            passwordInput.required = !isMitra;
            passwordInput.disabled = isMitra;


            // Reset toggle password
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

        }


        // ==========================================================
        // EVENT KLIK ROLE
        // ==========================================================

        roleButtons.forEach(function(button) {

            button.addEventListener('click', function() {

                selectRole(button.dataset.role);

            });

        });


        // ==========================================================
        // TOGGLE PASSWORD
        // ==========================================================

        if (passwordInput && togglePassword) {

            togglePassword.addEventListener('click', function() {

                const showPassword = passwordInput.type === 'password';

                passwordInput.type = showPassword ? 'text' : 'password';

                togglePassword.setAttribute(
                    'aria-label',
                    showPassword ?
                    'Sembunyikan password' :
                    'Tampilkan password'
                );

                togglePassword.setAttribute(
                    'aria-pressed',
                    showPassword ? 'true' : 'false'
                );

            });

        }


        // ==========================================================
        // LOGIN BUTTON LOADING
        // ==========================================================

        if (loginForm && loginButton) {

            loginForm.addEventListener('submit', function(event) {

                // Cegah submit berulang
                if (loginButton.disabled) {
                    event.preventDefault();
                    return;
                }

                // Aktifkan loading
                loginButton.disabled = true;

                // Ubah teks
                if (loginButtonText) {
                    loginButtonText.textContent = 'Memproses...';
                }

                // Tampilkan spinner Tailwind
                if (loginSpinner) {
                    loginSpinner.classList.remove('hidden');
                    loginSpinner.classList.add('animate-spin');
                }

                // Sembunyikan ikon panah
                if (loginArrow) {
                    loginArrow.classList.add('hidden');
                }

                // Ubah warna tombol
                loginButton.classList.remove('bg-blue-600', 'hover:bg-blue-700');
                loginButton.classList.add('bg-blue-700', 'cursor-not-allowed');

            });

        }


        // ==========================================================
        // DEFAULT ROLE
        // ==========================================================

        selectRole('mahasiswa');

    });
</script>