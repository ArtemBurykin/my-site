export const imagePreview = (triggerClass, containerId) => {
    document.querySelectorAll(`.${triggerClass}`).forEach(el => {
        el.addEventListener('click', () => {
            const imageContainer = document.getElementById(containerId);
            imageContainer.innerHTML = '';

            const imageSrc = el.dataset.image;
            const image = document.createElement('img');
            image.setAttribute('src', imageSrc);

            imageContainer.appendChild(image);
        });
    });
};