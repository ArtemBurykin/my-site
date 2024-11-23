export const analyticsConversions = function (counterId) {
    document.addEventListener('analyticsEventOccurred', (e) => {
        window.ym(counterId, 'reachGoal', e.detail.name);
    });
};
