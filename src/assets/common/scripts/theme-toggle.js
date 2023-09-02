// IMHERE: fix the case when there few theme togglers on the page
export function themeToggle(baseClass) {
    const markerClass = `${baseClass}__marker`;

    let currentTheme = null;

    const markers = document.querySelectorAll(`.${markerClass}`);

    const setMarkerToTheme = (theme) => {
        markers.forEach((marker) => {
            if (theme === 'dark') {
                marker.classList.add(`${markerClass}--dark`);
                marker.classList.remove(`${markerClass}--light`);
            } else {
                marker.classList.add(`${markerClass}--light`);
                marker.classList.remove(`${markerClass}--dark`);
            }
        });
    };

    const setTheme = (theme, setInStore) => {
        currentTheme = theme;

        if (setInStore) {
            window.localStorage.setItem('theme', theme);
        }

        const body = document.querySelector('body');

        if (theme === 'dark') {
            body.classList.add('dark-theme');
        } else {
            body.classList.remove('dark-theme');
        }

        setMarkerToTheme(theme);
    };

    const toggleTheme = () => {
        const toggledTheme = currentTheme === 'dark' ? 'light' : 'dark';
        setTheme(toggledTheme, true);
    };

    const init = () => {
        const themeFromLocalStorage  = window.localStorage.getItem('theme');

        let themeToSet;
        if (themeFromLocalStorage) {
            themeToSet = themeFromLocalStorage;
        } else {
            const isDarkThemed = window.matchMedia('(prefers-color-scheme: dark)');
            themeToSet = isDarkThemed.matches ? 'dark' : 'light';
        }

        setTheme(themeToSet, false);

        const toggles = document.querySelectorAll(`.${baseClass}`);
        toggles.forEach((toggle) => {
            toggle.addEventListener('click', toggleTheme);
        });
    };

    init();
}
