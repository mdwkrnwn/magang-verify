(function () {

    const STORAGE_KEY = 'magang-theme';


    /*
    |--------------------------------------------------------------------------
    | Get saved theme
    |--------------------------------------------------------------------------
    */

    function getTheme() {

        return localStorage.getItem(STORAGE_KEY) || 'light';

    }


    /*
    |--------------------------------------------------------------------------
    | Apply theme
    |--------------------------------------------------------------------------
    */

    function applyTheme(theme) {

        const html = document.documentElement;

        if (theme === 'dark') {

            html.classList.add('dark');

        } else {

            html.classList.remove('dark');

        }

        localStorage.setItem(
            STORAGE_KEY,
            theme
        );

        updateThemeButtons(theme);

    }


    /*
    |--------------------------------------------------------------------------
    | Update buttons
    |--------------------------------------------------------------------------
    */

    function updateThemeButtons(theme) {

        const buttons =
            document.querySelectorAll('[data-theme]');


        buttons.forEach(function (button) {

            const isActive =
                button.dataset.theme === theme;


            button.classList.toggle(
                'bg-white',
                isActive
            );

            button.classList.toggle(
                'text-blue-600',
                isActive
            );

            button.classList.toggle(
                'shadow-sm',
                isActive
            );


            if (!isActive) {

                button.classList.add(
                    'text-slate-500'
                );

            } else {

                button.classList.remove(
                    'text-slate-500'
                );

            }

        });

    }


    /*
    |--------------------------------------------------------------------------
    | Apply theme immediately
    |--------------------------------------------------------------------------
    */

    applyTheme(
        getTheme()
    );


    /*
    |--------------------------------------------------------------------------
    | Public function
    |--------------------------------------------------------------------------
    */

    window.setDashboardTheme = function (theme) {

        if (
            theme !== 'light'
            &&
            theme !== 'dark'
        ) {
            return;
        }

        applyTheme(theme);

    };


    /*
    |--------------------------------------------------------------------------
    | Button event
    |--------------------------------------------------------------------------
    */

    document.addEventListener(
        'DOMContentLoaded',
        function () {

            document
                .querySelectorAll('[data-theme]')
                .forEach(function (button) {

                    button.addEventListener(
                        'click',
                        function () {

                            setDashboardTheme(
                                button.dataset.theme
                            );

                        }
                    );

                });


            updateThemeButtons(
                getTheme()
            );

        }
    );

})();