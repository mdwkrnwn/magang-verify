document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('profil-sidebar');

    if (!sidebar) {
        return;
    }

    const menuItems = Array.from(
        sidebar.querySelectorAll('.profil-menu-item')
    );

    const sections = menuItems
        .map(item => {
            const id = item.dataset.section;
            const section = document.getElementById(id);

            return section ? { item, section } : null;
        })
        .filter(Boolean);

    if (!sections.length) {
        return;
    }


    /*
     * ==========================================
     * 1. MENU AKTIF MENGIKUTI SECTION
     * ==========================================
     */

    const setActive = (activeItem) => {
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
     * 2. SECTION YANG TERLIHAT = MENU AKTIF
     * ==========================================
     */

    const observer = new IntersectionObserver(
        entries => {
            const visibleSections = entries
                .filter(entry => entry.isIntersecting)
                .sort(
                    (a, b) =>
                        a.boundingClientRect.top -
                        b.boundingClientRect.top
                );

            if (!visibleSections.length) {
                return;
            }

            const activeSection = visibleSections[0].target;

            const active = sections.find(
                item => item.section === activeSection
            );

            if (!active) {
                return;
            }

            setActive(active.item);

            /*
             * Saat menu aktif berubah,
             * menu sidebar ikut bergeser supaya
             * item aktif selalu terlihat.
             */
            active.item.scrollIntoView({
                behavior: 'smooth',
                block: 'nearest',
                inline: 'center'
            });
        },
        {
            root: null,

            /*
             * Area aktif sedikit di bawah navbar.
             */
            rootMargin: '-120px 0px -55% 0px',

            threshold: 0
        }
    );


    sections.forEach(({ section }) => {
        observer.observe(section);
    });


    /*
     * ==========================================
     * 3. KETIKA MENU DIKLIK
     * ==========================================
     */

    menuItems.forEach(item => {

        item.addEventListener('click', () => {

            setActive(item);

            /*
             * Sidebar ikut menggeser item yang
             * diklik agar tetap terlihat.
             */
            item.scrollIntoView({
                behavior: 'smooth',
                block: 'nearest',
                inline: 'center'
            });

        });

    });

});