export function themeToggle(toggleId, baseClass) {
    const markerId = `${toggleId}-marker`;
    const markerClass = `${baseClass}__marker`;

    const marker = document.querySelector(`#${markerId}`);

    const setMarkerToTheme = (theme) => {
        if (theme === 'dark') {
            marker.classList.add(`${markerClass}--dark`);
            marker.classList.remove(`${markerClass}--light`);
        } else {
            marker.classList.add(`${markerClass}--light`);
            marker.classList.remove(`${markerClass}--dark`);
        }
    }

    const init = () => {
        const themeFromLocalStorage  = window.localStorage.getItem('theme');

        if (themeFromLocalStorage) {
            setMarkerToTheme(themeFromLocalStorage);
        } else {
            const isDarkThemed = window.matchMedia("(prefers-color-scheme: dark)");
            const theme = isDarkThemed.matches ? 'dark' : 'light';
            setMarkerToTheme(theme);
        }
    }

    init();
}
