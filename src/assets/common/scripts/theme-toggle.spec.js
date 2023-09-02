/**
 *  @jest-environment jsdom
 */
import {themeToggle} from './theme-toggle';

describe('themeToggle', () => {
    beforeEach(() => {
        window.matchMedia = jest.fn();

        document.body.innerHTML = `
            <body>
                <div class="header__theme-toggle theme-toggle__container">
                    <div class="theme-toggle" id="laptop-toggle">
                        <div
                            class="theme-toggle__marker"
                        ></div>
                    </div>
                </div>
                
                <div class="header__theme-toggle theme-toggle__container theme-toggle__container--mobile">
                    <div class="theme-toggle" id="mobile-toggle">
                        <div
                            class="theme-toggle__marker"
                        ></div>
                    </div>
                </div>
            </body>
            `;
    });

    afterEach(() => {
        window.matchMedia.mockRestore();
        window.localStorage.clear();
        document.body.innerHTML = '';
    });

    describe('should set the initial marker position', () => {
        test('theme not in local storage: system is light', () => {
            window.matchMedia = jest.fn(() => ({ matches: false }));

            themeToggle('theme-toggle');

            const markers = document.querySelectorAll('.theme-toggle__marker');

            expect(markers.length).toBe(2);

            markers.forEach((marker) => {
                expect(marker.classList.contains('theme-toggle__marker--dark')).toBe(false);
                expect(marker.classList.contains('theme-toggle__marker--light')).toBe(true);
            });

            expect(document.querySelector('body').classList.contains('dark-theme')).toBe(false);

            expect(window.matchMedia).toBeCalledWith('(prefers-color-scheme: dark)');
            expect(window.matchMedia).toBeCalledTimes(1);
        });

        test('theme not in local storage: system is dark', () => {
            window.matchMedia = jest.fn(() => ({ matches: true }));

            themeToggle('theme-toggle');

            const markers = document.querySelectorAll('.theme-toggle__marker');

            expect(markers.length).toBe(2);

            markers.forEach((marker) => {
                expect(marker.classList.contains('theme-toggle__marker--dark')).toBe(true);
                expect(marker.classList.contains('theme-toggle__marker--light')).toBe(false);
            });

            expect(document.querySelector('body').classList.contains('dark-theme')).toBe(true);

            expect(window.matchMedia).toBeCalledWith('(prefers-color-scheme: dark)');
            expect(window.matchMedia).toBeCalledTimes(1);
        });

        test('theme in local storage is light', () => {
            window.matchMedia = jest.fn(() => ({ matches: true }));
            window.localStorage.setItem('theme', 'light');

            themeToggle('theme-toggle');

            const markers = document.querySelectorAll('.theme-toggle__marker');

            expect(markers.length).toBe(2);

            markers.forEach((marker) => {
                expect(marker.classList.contains('theme-toggle__marker--dark')).toBe(false);
                expect(marker.classList.contains('theme-toggle__marker--light')).toBe(true);
            });

            expect(document.querySelector('body').classList.contains('dark-theme')).toBe(false);
        });

        test('theme in local storage is dark', () => {
            window.matchMedia = jest.fn(() => ({ matches: false }));
            window.localStorage.setItem('theme', 'dark');

            themeToggle('theme-toggle');

            expect(document.querySelector('.theme-toggle__marker--dark')).not.toBeNull();
            expect(document.querySelector('.theme-toggle__marker--light')).toBeNull();
            expect(document.querySelector('body').classList.contains('dark-theme')).toBe(true);
        });
    });

    describe('should toggle the theme', () => {
        test('id=laptop-toggle: dark theme', () => {
            window.matchMedia = jest.fn(() => ({ matches: false }));
            themeToggle('theme-toggle');

            document.querySelector('#laptop-toggle').click();
            expect(window.localStorage.getItem('theme')).toBe('dark');

            const markers = document.querySelectorAll('.theme-toggle__marker');

            expect(markers.length).toBe(2);

            markers.forEach((marker) => {
                expect(marker.classList.contains('theme-toggle__marker--dark')).toBe(true);
                expect(marker.classList.contains('theme-toggle__marker--light')).toBe(false);
            });

            expect(document.querySelector('body').classList.contains('dark-theme')).toBe(true);
        });

        test('id=laptop-toggle: light theme', () => {
            window.matchMedia = jest.fn(() => ({ matches: true }));
            themeToggle('theme-toggle');

            document.querySelector('#laptop-toggle').click();
            expect(window.localStorage.getItem('theme')).toBe('light');

            const markers = document.querySelectorAll('.theme-toggle__marker');

            expect(markers.length).toBe(2);

            markers.forEach((marker) => {
                expect(marker.classList.contains('theme-toggle__marker--light')).toBe(true);
                expect(marker.classList.contains('theme-toggle__marker--dark')).toBe(false);
            });

            expect(document.querySelector('body').classList.contains('dark-theme')).toBe(false);
        });

        test('id=mobile-toggle: dark theme', () => {
            window.matchMedia = jest.fn(() => ({ matches: false }));
            themeToggle('theme-toggle');

            document.querySelector('#mobile-toggle').click();
            expect(window.localStorage.getItem('theme')).toBe('dark');

            const markers = document.querySelectorAll('.theme-toggle__marker');

            expect(markers.length).toBe(2);

            markers.forEach((marker) => {
                expect(marker.classList.contains('theme-toggle__marker--dark')).toBe(true);
                expect(marker.classList.contains('theme-toggle__marker--light')).toBe(false);
            });

            expect(document.querySelector('body').classList.contains('dark-theme')).toBe(true);
        });

        test('id=mobile-toggle: light theme', () => {
            window.matchMedia = jest.fn(() => ({ matches: true }));
            themeToggle('theme-toggle');

            document.querySelector('#mobile-toggle').click();
            expect(window.localStorage.getItem('theme')).toBe('light');

            const markers = document.querySelectorAll('.theme-toggle__marker');

            expect(markers.length).toBe(2);

            markers.forEach((marker) => {
                expect(marker.classList.contains('theme-toggle__marker--light')).toBe(true);
                expect(marker.classList.contains('theme-toggle__marker--dark')).toBe(false);
            });

            expect(document.querySelector('body').classList.contains('dark-theme')).toBe(false);
        });
    });
});
