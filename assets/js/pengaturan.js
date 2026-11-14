/**
 * ============================================================
 * PENGATURAN AKUN
 * ============================================================
 * Fitur:
 * - Toggle tampil/sembunyikan password
 * - Validasi konfirmasi password
 * ============================================================
 */

document.addEventListener("DOMContentLoaded", function () {
  // Toggle Password
  document.querySelectorAll("[data-password-toggle]").forEach((button) => {
    button.addEventListener("click", function () {
      const input = document.getElementById(this.dataset.passwordToggle);

      if (!input) return;

      const show = input.type === "password";

      input.type = show ? "text" : "password";

      this.innerHTML = show
        ? `
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M3 3l18 18"/>
                        <path d="M10.6 10.6a2 2 0 0 0 2.8 2.8"/>
                        <path d="M9.9 5.2A10.8 10.8 0 0 1 12 5c6.4 0 10 7 10 7"/>
                    </svg>
                `
        : `
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7-10-7-10-7Z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                `;

      this.setAttribute(
        "aria-label",
        show ? "Sembunyikan kata sandi" : "Tampilkan kata sandi",
      );
    });
  });

  // Validasi konfirmasi password
  const form = document.getElementById("changePasswordForm");

  if (form) {
    form.addEventListener("submit", function (event) {
      const password = document.getElementById("new_password");
      const confirmation = document.getElementById("new_password_confirmation");

      if (!password || !confirmation) return;

      if (password.value !== confirmation.value) {
        event.preventDefault();

        confirmation.setCustomValidity("Konfirmasi kata sandi tidak sama.");

        confirmation.reportValidity();
      }
    });

    const confirmation = document.getElementById("new_password_confirmation");

    if (confirmation) {
      confirmation.addEventListener("input", function () {
        this.setCustomValidity("");
      });
    }
  }
});
