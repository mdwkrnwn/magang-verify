<script>
    document.addEventListener('DOMContentLoaded', function () {

        /*
         * ==========================================================
         * ELEMENT FORM
         * ==========================================================
         */

        const roleButtons =
            document.querySelectorAll('[data-role]');

        const roleInput =
            document.getElementById('role');

        const identityLabel =
            document.getElementById('identityLabel');

        const identityInput =
            document.getElementById('login_id');

        const passwordSection =
            document.getElementById('passwordSection');

        const passwordInput =
            document.getElementById('password');

        const togglePassword =
            document.getElementById('togglePassword');


        /*
         * ==========================================================
         * IDENTITAS SETIAP ROLE
         * ==========================================================
         *
         * Hanya metadata label/form field. Kredensial harus
         * dimasukkan pengguna, bukan diisi otomatis di browser.
         */

        const accountConfig = {

            mahasiswa: {
                label: 'NIM',
                placeholder: 'Masukkan NIM',
                inputmode: 'numeric',
            },

            dosen: {
                label: 'NIP',
                placeholder: 'Masukkan NIP',
                inputmode: 'numeric',
            },

            koordinator_magang: {
                label: 'NIP',
                placeholder: 'Masukkan NIP',
                inputmode: 'numeric',
            },

            tendik: {
                label: 'NIP',
                placeholder: 'Masukkan NIP',
                inputmode: 'numeric',
            },

            mitra: {
                label: 'Kode Mitra',
                placeholder: 'Kode Mitra',
                inputmode: 'text',
            }

        };


        /*
         * ==========================================================
         * CLASS TOMBOL ROLE
         * ==========================================================
         */

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


        /*
         * ==========================================================
         * FUNGSI MEMILIH ROLE
         * ==========================================================
         */

        function selectRole(selectedRole) {

            const config =
                accountConfig[selectedRole];


            /*
             * Jika role tidak ditemukan,
             * hentikan proses.
             */
            if (!config) {
                console.error(
                    'Konfigurasi role tidak ditemukan:',
                    selectedRole
                );

                return;
            }


            /*
             * ------------------------------------------------------
             * SIMPAN ROLE
             * ------------------------------------------------------
             */

            roleInput.value =
                selectedRole;


            /*
             * ------------------------------------------------------
             * UPDATE TOMBOL ROLE
             * ------------------------------------------------------
             */

            roleButtons.forEach(function (button) {

                const isActive =
                    button.dataset.role === selectedRole;


                button.setAttribute(
                    'aria-checked',
                    isActive
                        ? 'true'
                        : 'false'
                );


                if (isActive) {

                    button.classList.add(
                        ...activeClasses
                    );

                    button.classList.remove(
                        ...inactiveClasses
                    );

                } else {

                    button.classList.remove(
                        ...activeClasses
                    );

                    button.classList.add(
                        ...inactiveClasses
                    );

                }

            });


            /*
             * ------------------------------------------------------
             * UPDATE LABEL IDENTITAS
             * ------------------------------------------------------
             */

            identityLabel.textContent =
                config.label;


            /*
             * ------------------------------------------------------
             * UPDATE PLACEHOLDER
             * ------------------------------------------------------
             */

            identityInput.placeholder =
                config.placeholder;


            /*
             * ------------------------------------------------------
             * UPDATE INPUT MODE
             * ------------------------------------------------------
             */

            identityInput.inputMode =
                config.inputmode;


            // Jangan membawa kredensial dari role sebelumnya.
            identityInput.value = '';
            passwordInput.value = '';
            passwordInput.type = 'password';

            const isMitra = selectedRole === 'mitra';
            passwordSection.hidden = isMitra;
            passwordInput.required = !isMitra;
            passwordInput.disabled = isMitra;


            /*
             * ------------------------------------------------------
             * RESET TOGGLE PASSWORD
             * ------------------------------------------------------
             */

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


        /*
         * ==========================================================
         * EVENT KLIK SETIAP ROLE
         * ==========================================================
         */

        roleButtons.forEach(function (button) {

            button.addEventListener(
                'click',
                function () {

                    const selectedRole =
                        button.dataset.role;


                    selectRole(
                        selectedRole
                    );

                }
            );

        });


        /*
         * ==========================================================
         * TOGGLE PASSWORD
         * ==========================================================
         */

        if (
            passwordInput &&
            togglePassword
        ) {

            togglePassword.addEventListener(
                'click',
                function () {

                    const showPassword =
                        passwordInput.type === 'password';


                    passwordInput.type =
                        showPassword
                            ? 'text'
                            : 'password';


                    togglePassword.setAttribute(
                        'aria-label',
                        showPassword
                            ? 'Sembunyikan password'
                            : 'Tampilkan password'
                    );


                    togglePassword.setAttribute(
                        'aria-pressed',
                        showPassword
                            ? 'true'
                            : 'false'
                    );

                }
            );

        }


        /*
         * ==========================================================
         * DEFAULT ROLE
         * ==========================================================
         *
         * Saat halaman login pertama kali dibuka,
         * Mahasiswa otomatis dipilih.
         *
         * Sekaligus:
         *
         */

        selectRole('mahasiswa');

    });
</script>