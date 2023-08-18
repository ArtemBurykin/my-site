export function themeToggle(toggleId, baseClass) {
    const markerId = `${toggleId}-marker`;
    const markerClass = `${baseClass}__marker`;

    let currentTheme = null;

    const marker = document.querySelector(`#${markerId}`);

    const setMarkerToTheme = (theme) => {
        if (theme === 'dark') {
            marker.classList.add(`${markerClass}--dark`);
            marker.classList.remove(`${markerClass}--light`);
        } else {
            marker.classList.add(`${markerClass}--light`);
            marker.classList.remove(`${markerClass}--dark`);
        }
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

        document.querySelector(`#${toggleId}`).addEventListener('click', toggleTheme);
    };

    init();
}
