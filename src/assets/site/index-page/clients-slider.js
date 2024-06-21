export function clientsSlider(blockClass) {
    const arrowRight = document.querySelector(`.${blockClass}__arrow--right`);
    const arrowLeft = document.querySelector(`.${blockClass}__arrow--left`);
    const wrapper = document.querySelector(`.${blockClass}__wrapper`);

    const moveStep = 100;

    const calculateScrollLeftMax = (el) => {
        return el.scrollWidth - el.clientWidth;
    };

    const render = () => {
        wrapper.scrollLeft = scrollPos;
    };

    // state
    let scrollPos = 0;
    let scrollLeftMax = calculateScrollLeftMax(wrapper);

    window.addEventListener('resize', () => {
        scrollLeftMax = calculateScrollLeftMax(wrapper);
        scrollPos = 0;

        render();
    });

    arrowRight.addEventListener('click', () => {
        if (scrollPos + moveStep > scrollLeftMax) {
            scrollPos = scrollLeftMax;
        } else {
            scrollPos += moveStep;
        }

        render();
    });

    arrowLeft.addEventListener('click', () => {
        if (scrollPos - moveStep < 0) {
            scrollPos = 0;
        } else {
            scrollPos -= moveStep;
        }

        render();
    });

    wrapper.addEventListener('scroll', () => {
        scrollPos = wrapper.scrollLeft;
    });
}
