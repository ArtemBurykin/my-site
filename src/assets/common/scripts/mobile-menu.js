export function mobileMenu(toggleBase, mobileMenuClass) {
    const showBtn = document.querySelector(`#${toggleBase}-show`);
    const closeBtn = document.querySelector(`#${toggleBase}-close`);

    const mobileMenu = document.querySelector(`.${mobileMenuClass}`);

    showBtn.addEventListener('click', () => {
        mobileMenu.classList.remove(`${mobileMenuClass}--hidden`);
    });

    closeBtn.addEventListener('click', () => {
        mobileMenu.classList.add(`${mobileMenuClass}--hidden`);
    });
}
