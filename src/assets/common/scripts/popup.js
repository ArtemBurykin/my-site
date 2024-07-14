export const popup = function(popupId) {
    const popupCls = 'popup';
    const triggers = document.querySelectorAll(`[data-trigger-popup-id="${popupId}"]`);
    const popup = document.getElementById(popupId);

    triggers.forEach(el => {
        let isPopupShown = false;

        const render = () => {
            popup.classList.toggle(`${popupCls}--open`, isPopupShown);
        }

        const closePopup = (e) => {
            if (e.target !== e.currentTarget) {
               return;
            }

            isPopupShown = false;
            render();
        }

        const overlay = popup.querySelector(`.${popupCls}__overlay`);
        overlay.addEventListener('click', closePopup);

        const closeBtn = popup.querySelector(`.${popupCls}__close`);
        closeBtn.addEventListener('click', closePopup);

        el.addEventListener('click', () => {
            isPopupShown = true;
            render();
        });
    });
};
