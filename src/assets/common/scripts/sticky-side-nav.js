export function stickySideNav() {
    const breakpointWidth = 1200;
    const stickyScrollTop = 600;
    const minimalDocumentHeight = 1400;
    const rootEl = document.querySelector('html');
    const sideNav = document.querySelector('.side-nav');

    const toggleFixedClass = () => {
        const scrollPosition = rootEl.scrollTop;

        if (scrollPosition > stickyScrollTop) {
            sideNav.classList.add('side-nav--fixed');
        } else {
            sideNav.classList.remove('side-nav--fixed');
        }
    };

    if (document.querySelector('body').clientHeight < minimalDocumentHeight) {
        sideNav.classList.add('side-nav--hidden');
    }

    if (window.innerWidth >= breakpointWidth) {
        toggleFixedClass();
        window.addEventListener('scroll', toggleFixedClass);
    }

    window.addEventListener('resize', () => {
        if (window.innerWidth >= breakpointWidth) {
            toggleFixedClass();
            window.addEventListener('scroll', toggleFixedClass);
        } else {
            window.removeEventListener('scroll', toggleFixedClass);
        }
    });
}
