// Dashboard - Sidebar Toggle

document.addEventListener('DOMContentLoaded', function () {

    const sidebar =
        document.getElementById('sidebar');

    const overlay =
        document.getElementById('sidebar-overlay');

    const toggle =
        document.getElementById('sidebar-toggle');

    const toggleIcon =
        document.getElementById(
            'sidebar-toggle-icon'
        );


    if (
        !sidebar ||
        !overlay ||
        !toggle
    ) {
        console.warn(
            'Sidebar dashboard tidak ditemukan.'
        );

        return;
    }


    const desktopQuery =
        window.matchMedia(
            '(min-width: 1024px)'
        );


    const STORAGE_KEY =
        'dashboard-sidebar-collapsed';


    let collapsed =
        localStorage.getItem(
            STORAGE_KEY
        ) === 'true';


    /*
    |--------------------------------------------------------------------------
    | Icon
    |--------------------------------------------------------------------------
    */

    const hamburgerIcon = `
        <svg
            class="w-5 h-5"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
            aria-hidden="true"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M4 6h16M4 12h16M4 18h16"
            />
        </svg>
    `;


    const closeIcon = `
        <svg
            class="w-5 h-5"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
            aria-hidden="true"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M6 18 18 6M6 6l12 12"
            />
        </svg>
    `;


    /*
    |--------------------------------------------------------------------------
    | Ambil semua header dan main
    |--------------------------------------------------------------------------
    */

    const headers =
        document.querySelectorAll(
            'header'
        );


    const mains =
        document.querySelectorAll(
            'main'
        );


    /*
    |--------------------------------------------------------------------------
    | Desktop - Apply
    |--------------------------------------------------------------------------
    */

    function applyDesktop() {

        /*
        |--------------------------------------------------------------------------
        | Sidebar
        |--------------------------------------------------------------------------
        */

        sidebar.classList.remove(
            '-translate-x-full'
        );

        sidebar.style.width =
            collapsed
                ? '5rem'
                : '16rem';


        /*
        |--------------------------------------------------------------------------
        | Logo
        |--------------------------------------------------------------------------
        */

        const logoText =
            sidebar.querySelector(
                '.sidebar-logo-text'
            );


        const logo =
            sidebar.querySelector(
                '#sidebar-logo'
            );


        if (logoText) {

            logoText.style.display =
                collapsed
                    ? 'none'
                    : '';

        }


        if (logo) {

            logo.style.width =
                collapsed
                    ? '100%'
                    : '';

            logo.style.justifyContent =
                collapsed
                    ? 'center'
                    : '';

        }


        /*
        |--------------------------------------------------------------------------
        | Section label
        |--------------------------------------------------------------------------
        */

        sidebar
            .querySelectorAll(
                '.sidebar-section-label'
            )
            .forEach(
                function (element) {

                    element.style.display =
                        collapsed
                            ? 'none'
                            : '';

                }
            );


        /*
        |--------------------------------------------------------------------------
        | Menu text
        |--------------------------------------------------------------------------
        */

        sidebar
            .querySelectorAll(
                '.sidebar-label'
            )
            .forEach(
                function (element) {

                    element.style.display =
                        collapsed
                            ? 'none'
                            : '';

                }
            );


        /*
        |--------------------------------------------------------------------------
        | Menu item
        |--------------------------------------------------------------------------
        */

        sidebar
            .querySelectorAll(
                '.sidebar-nav-item'
            )
            .forEach(
                function (element) {

                    element.style.justifyContent =
                        collapsed
                            ? 'center'
                            : '';

                    element.style.gap =
                        collapsed
                            ? '0'
                            : '';

                    element.style.paddingLeft =
                        collapsed
                            ? '0.5rem'
                            : '';

                    element.style.paddingRight =
                        collapsed
                            ? '0.5rem'
                            : '';

                }
            );


        /*
        |--------------------------------------------------------------------------
        | Header
        |--------------------------------------------------------------------------
        */

        headers.forEach(
            function (header) {

                header.style.left =
                    collapsed
                        ? '5rem'
                        : '16rem';

            }
        );


        /*
        |--------------------------------------------------------------------------
        | Main
        |--------------------------------------------------------------------------
        */

        mains.forEach(
            function (main) {

                main.style.marginLeft =
                    collapsed
                        ? '5rem'
                        : '16rem';

            }
        );


        /*
        |--------------------------------------------------------------------------
        | Button
        |--------------------------------------------------------------------------
        */

        toggle.innerHTML =
            hamburgerIcon;

        toggle.setAttribute(
            'aria-expanded',
            collapsed
                ? 'false'
                : 'true'
        );

        toggle.setAttribute(
            'aria-label',
            collapsed
                ? 'Buka sidebar'
                : 'Tutup sidebar'
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Mobile - Open
    |--------------------------------------------------------------------------
    */

    function openMobile() {

        sidebar.classList.remove(
            '-translate-x-full'
        );

        overlay.classList.remove(
            'hidden'
        );

        document.body.classList.add(
            'overflow-hidden'
        );


        toggle.innerHTML =
            closeIcon;

        toggle.setAttribute(
            'aria-expanded',
            'true'
        );

        toggle.setAttribute(
            'aria-label',
            'Tutup sidebar'
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Mobile - Close
    |--------------------------------------------------------------------------
    */

    function closeMobile() {

        sidebar.classList.add(
            '-translate-x-full'
        );

        overlay.classList.add(
            'hidden'
        );

        document.body.classList.remove(
            'overflow-hidden'
        );


        toggle.innerHTML =
            hamburgerIcon;

        toggle.setAttribute(
            'aria-expanded',
            'false'
        );

        toggle.setAttribute(
            'aria-label',
            'Buka sidebar'
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Responsive
    |--------------------------------------------------------------------------
    */

    function applyResponsive() {

        if (desktopQuery.matches) {

            applyDesktop();

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | Mobile / Tablet
        |--------------------------------------------------------------------------
        */

        sidebar.style.width = '';

        headers.forEach(
            function (header) {
                header.style.left = '';
            }
        );


        mains.forEach(
            function (main) {
                main.style.marginLeft = '';
            }
        );


        sidebar
            .querySelectorAll(
                '.sidebar-logo-text'
            )
            .forEach(
                function (element) {
                    element.style.display = '';
                }
            );


        sidebar
            .querySelectorAll(
                '.sidebar-section-label'
            )
            .forEach(
                function (element) {
                    element.style.display = '';
                }
            );


        sidebar
            .querySelectorAll(
                '.sidebar-label'
            )
            .forEach(
                function (element) {
                    element.style.display = '';
                }
            );


        sidebar
            .querySelectorAll(
                '.sidebar-nav-item'
            )
            .forEach(
                function (element) {

                    element.style.justifyContent =
                        '';

                    element.style.gap =
                        '';

                    element.style.paddingLeft =
                        '';

                    element.style.paddingRight =
                        '';

                }
            );


        closeMobile();

    }


    /*
    |--------------------------------------------------------------------------
    | Toggle
    |--------------------------------------------------------------------------
    */

    toggle.addEventListener(
        'click',
        function () {

            /*
            |--------------------------------------------------------------------------
            | Desktop
            |--------------------------------------------------------------------------
            */

            if (desktopQuery.matches) {

                collapsed =
                    !collapsed;


                localStorage.setItem(
                    STORAGE_KEY,
                    collapsed
                        ? 'true'
                        : 'false'
                );


                applyDesktop();

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | Mobile / Tablet
            |--------------------------------------------------------------------------
            */

            const isOpen =
                !sidebar.classList.contains(
                    '-translate-x-full'
                );


            if (isOpen) {

                closeMobile();

            } else {

                openMobile();

            }

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Overlay
    |--------------------------------------------------------------------------
    */

    overlay.addEventListener(
        'click',
        function () {

            closeMobile();

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Escape
    |--------------------------------------------------------------------------
    */

    document.addEventListener(
        'keydown',
        function (event) {

            if (
                event.key === 'Escape' &&
                !desktopQuery.matches
            ) {

                closeMobile();

            }

        }
    );


    /*
    |--------------------------------------------------------------------------
    | Klik menu mobile
    |--------------------------------------------------------------------------
    */

    sidebar
        .querySelectorAll('a')
        .forEach(
            function (link) {

                link.addEventListener(
                    'click',
                    function () {

                        if (
                            !desktopQuery.matches
                        ) {

                            closeMobile();

                        }

                    }
                );

            }
        );


    /*
    |--------------------------------------------------------------------------
    | Resize
    |--------------------------------------------------------------------------
    */

    if (
        typeof desktopQuery.addEventListener ===
        'function'
    ) {

        desktopQuery.addEventListener(
            'change',
            applyResponsive
        );

    } else {

        desktopQuery.addListener(
            applyResponsive
        );

    }

    /*
    |--------------------------------------------------------------------------
    | Initial state
    |--------------------------------------------------------------------------
    */

    applyResponsive();

});

// Detail Profil Mahasiswa

document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById(
        'profil-sidebar'
    );

    const contentArea = document.getElementById(
        'profil-content-area'
    );

    if (!sidebar || !contentArea) {
        return;
    }

    const menuItems = Array.from(
        sidebar.querySelectorAll(
            '.profil-menu-item'
        )
    );

    const sections = menuItems
        .map(item => {
            const id = item.dataset.section;
            const section =
                document.getElementById(id);

            return section
                ? {
                    item,
                    section
                }
                : null;
        })
        .filter(Boolean);

    if (!sections.length) {
        return;
    }

    /*
     * ==========================================
     * SIDEBAR POSITION
     * ==========================================
     */

    const SIDEBAR_TOP = 96;
    const SIDEBAR_GAP = 16;

    const updateSidebarPosition = () => {
        const areaRect =
            contentArea.getBoundingClientRect();

        const sidebarHeight =
            sidebar.offsetHeight;

        const sidebarWidth = 210;

        /*
         * Posisi kolom sidebar asli.
         * Ini digunakan supaya sidebar fixed
         * tetap berada tepat di kolom 210px.
         */
        const sidebarColumn =
            sidebar.parentElement;

        const columnRect =
            sidebarColumn.getBoundingClientRect();

        /*
         * ======================================
         * SEBELUM AREA PROFIL
         * ======================================
         *
         * Sidebar tetap berada di posisi awal.
         */
        if (
            areaRect.top >
            SIDEBAR_TOP
        ) {
            sidebar.style.position =
                'absolute';

            sidebar.style.top = '0';
            sidebar.style.left = '0';
            sidebar.style.width =
                `${sidebarWidth}px`;

            return;
        }

        /*
         * ======================================
         * BATAS BAWAH AREA PROFIL
         * ======================================
         *
         * Sidebar tidak boleh melewati
         * batas bawah contentArea.
         */
        const maxTop =
            areaRect.bottom -
            sidebarHeight -
            SIDEBAR_GAP;

        /*
         * ======================================
         * AREA PROFIL SUDAH SELESAI
         * ======================================
         *
         * Sidebar berhenti tepat sebelum
         * batas bawah contentArea.
         */
        if (
            maxTop <= SIDEBAR_TOP
        ) {
            sidebar.style.position =
                'absolute';

            sidebar.style.top =
                `${Math.max(
                    0,
                    contentArea.offsetHeight -
                    sidebarHeight -
                    SIDEBAR_GAP
                )}px`;

            sidebar.style.left = '0';

            sidebar.style.width =
                `${sidebarWidth}px`;

            return;
        }

        /*
         * ======================================
         * SIDEBAR MENGIKUTI VIEWPORT
         * ======================================
         */

        sidebar.style.position =
            'fixed';

        sidebar.style.top =
            `${SIDEBAR_TOP}px`;

        sidebar.style.left =
            `${columnRect.left}px`;

        sidebar.style.width =
            `${sidebarWidth}px`;
    };

    /*
     * Jalankan pertama kali.
     */
    updateSidebarPosition();

    /*
     * Update ketika scroll.
     */
    window.addEventListener(
        'scroll',
        updateSidebarPosition,
        {
            passive: true
        }
    );

    /*
     * Update ketika ukuran layar berubah.
     */
    window.addEventListener(
        'resize',
        updateSidebarPosition
    );

    /*
     * ==========================================
     * ACTIVE MENU
     * ==========================================
     */

    const setActive = activeItem => {
        menuItems.forEach(item => {
            item.classList.remove(
                'bg-blue-50',
                'text-blue-600',
                'font-medium',
                'border-l-4',
                'border-blue-600'
            );

            item.classList.add(
                'text-slate-700',
                'hover:bg-slate-50'
            );
        });

        activeItem.classList.remove(
            'text-slate-700',
            'hover:bg-slate-50'
        );

        activeItem.classList.add(
            'bg-blue-50',
            'text-blue-600',
            'font-medium',
            'border-l-4',
            'border-blue-600'
        );
    };

    /*
     * ==========================================
     * DETEKSI SECTION AKTIF
     * ==========================================
     */

    const observer =
        new IntersectionObserver(
            entries => {
                const visibleSections =
                    entries
                        .filter(
                            entry =>
                                entry.isIntersecting
                        )
                        .sort(
                            (a, b) =>
                                a.boundingClientRect.top -
                                b.boundingClientRect.top
                        );

                if (
                    !visibleSections.length
                ) {
                    return;
                }

                const activeSection =
                    visibleSections[0].target;

                const active =
                    sections.find(
                        item =>
                            item.section ===
                            activeSection
                    );

                if (active) {
                    setActive(
                        active.item
                    );
                }
            },
            {
                root: null,
                rootMargin:
                    '-120px 0px -55% 0px',
                threshold: 0
            }
        );

    sections.forEach(
        ({ section }) => {
            observer.observe(section);
        }
    );

    /*
     * ==========================================
     * CLICK MENU
     * ==========================================
     */

    menuItems.forEach(item => {
        item.addEventListener(
            'click',
            () => {
                setActive(item);
            }
        );
    });
});

// Landing Page - Mahasiswa Filter

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById(
        'mahasiswa-filter-form'
    );

    if (!form) {
        return;
    }

    const searchInput = document.getElementById(
        'mahasiswa-search'
    );

    const filterSelects = form.querySelectorAll(
        'select[name="jurusan"], ' +
        'select[name="prodi"], ' +
        'select[name="angkatan"]'
    );

    const keahlianToggle =
        document.getElementById(
            'keahlian-toggle'
        );

    const keahlianMenu =
        document.getElementById(
            'keahlian-menu'
        );

    const keahlianSearch =
        document.getElementById(
            'keahlian-search'
        );

    const keahlianLabel =
        document.getElementById(
            'keahlian-label'
        );

    const keahlianArrow =
        document.getElementById(
            'keahlian-arrow'
        );

    const keahlianOptions =
        form.querySelectorAll(
            '.keahlian-option'
        );

    const keahlianCheckboxes =
        form.querySelectorAll(
            '.keahlian-checkbox'
        );

    const keahlianEmpty =
        document.getElementById(
            'keahlian-empty'
        );

    let searchTimer;

    /*
     * ==========================================
     * RESET FILTER SAAT REFRESH
     * ==========================================
     */

    const navigation =
        performance.getEntriesByType(
            'navigation'
        )[0];

    if (
        navigation &&
        navigation.type === 'reload' &&
        window.location.search !== ''
    ) {
        window.location.replace(
            window.location.pathname
        );

        return;
    }

    /*
     * ==========================================
     * SEARCH NAMA MAHASISWA
     * ==========================================
     */

    if (searchInput) {
        searchInput.addEventListener(
            'input',
            () => {
                clearTimeout(searchTimer);

                searchTimer = setTimeout(
                    () => {
                        form.submit();
                    },
                    400
                );
            }
        );
    }

    /*
     * ==========================================
     * FILTER JURUSAN / PRODI / ANGKATAN
     * ==========================================
     */

    filterSelects.forEach(
        select => {
            select.addEventListener(
                'change',
                () => {
                    form.submit();
                }
            );
        }
    );

    /*
     * ==========================================
     * KEAHLIAN - BUKA / TUTUP DROPDOWN
     * ==========================================
     */

    if (
        keahlianToggle &&
        keahlianMenu
    ) {
        keahlianToggle.addEventListener(
            'click',
            event => {
                event.stopPropagation();

                const isHidden =
                    keahlianMenu.classList.contains(
                        'hidden'
                    );

                keahlianMenu.classList.toggle(
                    'hidden'
                );

                if (keahlianArrow) {
                    keahlianArrow.classList.toggle(
                        'rotate-180',
                        isHidden
                    );
                }

                /*
                 * Fokus otomatis ke search
                 * ketika dropdown dibuka.
                 */
                if (
                    isHidden &&
                    keahlianSearch
                ) {
                    setTimeout(() => {
                        keahlianSearch.focus();
                    }, 50);
                }
            }
        );
    }

    /*
     * ==========================================
     * SEARCH DI DALAM KEAHLIAN
     * ==========================================
     */

    if (keahlianSearch) {
        keahlianSearch.addEventListener(
            'input',
            () => {
                const keyword =
                    keahlianSearch.value
                        .trim()
                        .toLowerCase();

                let visibleCount = 0;

                keahlianOptions.forEach(
                    option => {
                        const skill =
                            option.dataset.skill || '';

                        const cocok =
                            skill.includes(
                                keyword
                            );

                        option.classList.toggle(
                            'hidden',
                            !cocok
                        );

                        if (cocok) {
                            visibleCount++;
                        }
                    }
                );

                if (keahlianEmpty) {
                    keahlianEmpty.classList.toggle(
                        'hidden',
                        visibleCount !== 0
                    );
                }
            }
        );
    }

    /*
     * ==========================================
     * UPDATE JUMLAH KEAHLIAN DIPILIH
     * ==========================================
     */

    const updateKeahlianLabel = () => {
        if (!keahlianLabel) {
            return;
        }

        const selected =
            form.querySelectorAll(
                '.keahlian-checkbox:checked'
            );

        if (selected.length === 0) {
            keahlianLabel.textContent =
                'Semua Keahlian';

            return;
        }

        keahlianLabel.textContent =
            `${selected.length} keahlian dipilih`;
    };

    /*
     * ==========================================
     * TUTUP DROPDOWN SAAT KLIK DI LUAR
     * ==========================================
     */

    document.addEventListener(
        'click',
        event => {
            if (
                !keahlianMenu ||
                !keahlianToggle
            ) {
                return;
            }

            if (
                !keahlianMenu.contains(
                    event.target
                ) &&
                !keahlianToggle.contains(
                    event.target
                )
            ) {
                keahlianMenu.classList.add(
                    'hidden'
                );

                if (keahlianArrow) {
                    keahlianArrow.classList.remove(
                        'rotate-180'
                    );
                }
            }
        }
    );
});

// Dashboard Mahasiswa

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById(
        'pengajuan-filter-form'
    );

    if (!form) {
        return;
    }

    const searchInput = document.getElementById(
        'pengajuan-search'
    );

    const statusSelect = document.getElementById(
        'pengajuan-status'
    );

    const sortSelect = document.getElementById(
        'pengajuan-sort'
    );

    let searchTimer;

    /*
     * ==========================================
     * RESET FILTER SAAT BROWSER DI-REFRESH
     * ==========================================
     */

    const navigation = performance.getEntriesByType(
        'navigation'
    )[0];

    if (
        navigation &&
        navigation.type === 'reload' &&
        window.location.search !== ''
    ) {
        window.location.replace(
            window.location.pathname
        );

        return;
    }

    /*
     * ==========================================
     * SEARCH REALTIME
     * ==========================================
     */

    if (searchInput) {
        searchInput.addEventListener('input', () => {
            clearTimeout(searchTimer);

            searchTimer = setTimeout(() => {
                form.submit();
            }, 400);
        });
    }

    /*
     * ==========================================
     * STATUS REALTIME
     * ==========================================
     */

    if (statusSelect) {
        statusSelect.addEventListener('change', () => {
            form.submit();
        });
    }

    /*
     * ==========================================
     * SORTING REALTIME
     * ==========================================
     */

    if (sortSelect) {
        sortSelect.addEventListener('change', () => {
            form.submit();
        });
    }
});

// Dashboard Mahasiswa - Formasi Magang Filter

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById(
        'formasi-filter-form'
    );

    if (!form) {
        return;
    }

    const navigation =
        performance.getEntriesByType(
            'navigation'
        )[0];

    const isReload =
        navigation &&
        navigation.type === 'reload';

    const params = new URLSearchParams(
        window.location.search
    );

    const hasFilter =
        params.has('q') ||
        params.has('lokasi') ||
        params.has('durasi') ||
        params.has('status') ||
        params.has('page');

    /*
     * F5 / browser refresh:
     * kembali ke halaman tanpa filter.
     */
    if (isReload && hasFilter) {
        window.location.replace(
            window.location.pathname
        );

        return;
    }

    const search = form.querySelector(
        'input[name="q"]'
    );

    const selects = form.querySelectorAll(
        'select'
    );

    let timer = null;

    const submitFilter = () => {
        form.requestSubmit();
    };

    if (search) {
        search.addEventListener(
            'input',
            () => {
                clearTimeout(timer);

                timer = setTimeout(
                    submitFilter,
                    400
                );
            }
        );
    }

    selects.forEach(select => {
        select.addEventListener(
            'change',
            submitFilter
        );
    });
});

// Dashboard Mahasiswa Portofolio

document.addEventListener('DOMContentLoaded', function () {

    const form = document.getElementById(
        'portfolio-filter-form'
    );

    const search = document.getElementById(
        'portfolio-search'
    );

    const tahun = document.getElementById(
        'portfolio-tahun'
    );

    const status = document.getElementById(
        'portfolio-status'
    );

    const teknologi = document.getElementById(
        'portfolio-teknologi'
    );

    if (
        !form ||
        !search ||
        !tahun ||
        !status ||
        !teknologi
    ) {
        return;
    }

    let timer;

    /*
    |--------------------------------------------------------------------------
    | Submit Filter
    |--------------------------------------------------------------------------
    */

    function submitFilter() {

        /*
        | Hapus field kosong supaya URL tidak menjadi:
        |
        | ?q=asd&tahun=&status=&teknologi=
        */

        const fields = [
            tahun,
            status,
            teknologi
        ];

        fields.forEach(function (field) {

            if (field.value === '') {
                field.removeAttribute('name');
            }

        });

        if (search.value.trim() === '') {
            search.removeAttribute('name');
        }

        form.submit();
    }

    /*
    |--------------------------------------------------------------------------
    | Search
    |--------------------------------------------------------------------------
    */

    search.addEventListener(
        'input',
        function () {

            clearTimeout(timer);

            timer = setTimeout(
                function () {
                    submitFilter();
                },
                300
            );
        }
    );

    /*
    |--------------------------------------------------------------------------
    | Select Filter
    |--------------------------------------------------------------------------
    */

    tahun.addEventListener(
        'change',
        submitFilter
    );

    status.addEventListener(
        'change',
        submitFilter
    );

    teknologi.addEventListener(
        'change',
        submitFilter
    );

    const params = new URLSearchParams(
        window.location.search
    );

    const hasPortfolioFilter =
        params.has('q') ||
        params.has('tahun') ||
        params.has('status') ||
        params.has('teknologi') ||
        params.has('page');

    if (hasPortfolioFilter) {

        const cleanUrl =
            window.location.origin +
            window.location.pathname;

        window.history.replaceState(
            {},
            document.title,
            cleanUrl
        );
    }

});

function togglePortfolioMenu(menuId) {

    const menu = document.getElementById(menuId);

    if (!menu) {
        return;
    }

    document
        .querySelectorAll('[id^="menu-"]')
        .forEach(function (item) {

            if (item.id !== menuId) {
                item.classList.add('hidden');
            }

        });

    menu.classList.toggle('hidden');
}

function openDeletePortfolioModal(modalId, menuId) {

    const modal = document.getElementById(modalId);

    const menu = document.getElementById(menuId);

    if (menu) {
        menu.classList.add('hidden');
    }

    if (modal) {
        modal.showModal();
    }
}


function closeDeletePortfolioModal(modalId) {

    const modal = document.getElementById(modalId);

    if (modal) {
        modal.close();
    }
}


document.addEventListener('click', function (event) {

    if (
        !event.target.closest('[id^="menu-"]') &&
        !event.target.closest('button[onclick^="togglePortfolioMenu"]')
    ) {

        document
            .querySelectorAll('[id^="menu-"]')
            .forEach(function (menu) {

                menu.classList.add('hidden');

            });

    }

});

// END Dahsboard Mahasiswa Portofolio

// Dashboard Mahasiswa Sertifikat

(function () {

    /*
    |--------------------------------------------------------------------------
    | Reset filter saat browser di-refresh
    |--------------------------------------------------------------------------
    */

    const navigationEntry = performance.getEntriesByType(
        'navigation'
    )[0];

    const isReload =
        navigationEntry &&
        navigationEntry.type === 'reload';

    const currentUrl = new URL(
        window.location.href
    );

    const hasQuery =
        currentUrl.searchParams.toString() !== '';

    if (isReload && hasQuery) {

        currentUrl.search = '';

        window.location.replace(
            currentUrl.toString()
        );

        return;
    }


    /*
    |--------------------------------------------------------------------------
    | Filter
    |--------------------------------------------------------------------------
    */

    const form = document.getElementById(
        'sertifikatFilterForm'
    );

    if (!form) {
        return;
    }

    const search = document.getElementById(
        'sertifikat-search'
    );

    const tahun = document.getElementById(
        'sertifikat-tahun'
    );

    const status = document.getElementById(
        'sertifikat-status'
    );

    let searchTimer = null;


    function submitFilter() {

        const url = new URL(
            form.action,
            window.location.origin
        );

        const formData = new FormData(form);

        formData.forEach(function (value, key) {

            value = String(value).trim();

            if (value !== '') {

                url.searchParams.set(
                    key,
                    value
                );

            }

        });

        /*
        |--------------------------------------------------------------------------
        | Setiap filter baru selalu kembali ke halaman 1
        |--------------------------------------------------------------------------
        */

        url.searchParams.delete('page');

        window.location.href = url.toString();
    }


    /*
    |--------------------------------------------------------------------------
    | Search
    |--------------------------------------------------------------------------
    */

    if (search) {

        search.addEventListener(
            'input',
            function () {

                clearTimeout(searchTimer);

                searchTimer = setTimeout(
                    submitFilter,
                    300
                );

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Tahun
    |--------------------------------------------------------------------------
    */

    if (tahun) {

        tahun.addEventListener(
            'change',
            submitFilter
        );

    }


    /*
    |--------------------------------------------------------------------------
    | Status
    |--------------------------------------------------------------------------
    */

    if (status) {

        status.addEventListener(
            'change',
            submitFilter
        );

    }

})();