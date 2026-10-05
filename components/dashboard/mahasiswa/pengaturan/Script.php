<script>
document.addEventListener('DOMContentLoaded', function () {

    document
        .querySelectorAll('[data-password-toggle]')
        .forEach(function (button) {

            button.addEventListener('click', function () {

                const inputId =
                    button.getAttribute(
                        'data-password-toggle'
                    );

                const input =
                    document.getElementById(inputId);

                if (!input) {
                    return;
                }

                input.type =
                    input.type === 'password'
                        ? 'text'
                        : 'password';

            });

        });

});
</script>