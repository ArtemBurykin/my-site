export const gaConversions = function () {
    document.addEventListener('analyticsEventOccurred', (e) => {
        const location = window.location.href;
        window.gtag('event', e.detail.name, {location});
    });
};
