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