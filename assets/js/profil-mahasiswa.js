
document.addEventListener('DOMContentLoaded', () => {
    const openButtons = document.querySelectorAll('[data-open-modal]');
    const closeButtons = document.querySelectorAll('[data-close-modal]');
    const modals = document.querySelectorAll('[role="dialog"][id^="modal-"]');

    const openModal = (modal) => {
        if (!modal) return;

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.classList.add('overflow-hidden');

        const firstInput = modal.querySelector(
            'input:not([readonly]):not([type="hidden"]), textarea, select'
        );

        if (firstInput) firstInput.focus();
    };

    const closeModal = (modal) => {
        if (!modal) return;

        modal.classList.add('hidden');
        modal.classList.remove('flex');

        if (![...modals].some((item) => !item.classList.contains('hidden'))) {
            document.body.classList.remove('overflow-hidden');
        }
    };

    openButtons.forEach((button) => {
        button.addEventListener('click', () => {
            openModal(document.getElementById(button.dataset.openModal));
        });
    });

    closeButtons.forEach((button) => {
        button.addEventListener('click', () => {
            closeModal(button.closest('[role="dialog"]'));
        });
    });

    modals.forEach((modal) => {
        modal.addEventListener('click', (event) => {
            if (event.target === modal) closeModal(modal);
        });
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            modals.forEach((modal) => {
                if (!modal.classList.contains('hidden')) {
                    closeModal(modal);
                }
            });
        }
    });

    // Hubungkan form profil ke backend.
    document.querySelectorAll('[data-profile-form]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            const message = form.querySelector('[data-form-message]');

            // Validasi bawaan browser tetap digunakan.
            if (!form.reportValidity()) {
                event.preventDefault();
                return;
            }

            // Pastikan form memiliki alamat tujuan.
            if (!form.action) {
                event.preventDefault();

                if (message) {
                    message.textContent = 'Alamat tujuan form belum tersedia.';
                    message.classList.remove('hidden');
                }

                return;
            }

            // Biarkan browser mengirim form dengan POST secara normal.
            // Termasuk file foto melalui multipart/form-data.
            if (message) {
                message.classList.add('hidden');
                message.textContent = '';
            }

            const submitButton = form.querySelector(
                'button[type="submit"]'
            );

            if (submitButton) {
                submitButton.disabled = true;
                submitButton.textContent = 'Menyimpan...';
            }
        });
    });
});