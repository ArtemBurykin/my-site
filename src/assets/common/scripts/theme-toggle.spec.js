/**
 *  @jest-environment jsdom
 */
import {themeToggle} from "./theme-toggle";

describe('themeToggle', () => {
    beforeEach(() => {
        document.body.innerHTML = `
            <body>
                <div class="header__theme-toggle theme-toggle__container">
                    <div class="theme-toggle" id="theme-toggle">
                        <div id="theme-toggle-marker"
                            class="theme-toggle__marker"
                        ></div>
                    </div>
                </div>
            </body>
            `;
    });

    afterEach(() => {
        document.body.innerHTML = '';
    });

    describe('should set the initial marker position', () => {
        test('theme not in local storage: system is light', () => {
            window.matchMedia = jest.fn(() => ({ matches: false }));

            themeToggle('theme-toggle', 'theme-toggle');

            expect(document.querySelector('.theme-toggle__marker--light')).not.toBeNull();
            expect(document.querySelector('.theme-toggle__marker--dark')).toBeNull();

            expect(window.matchMedia).toBeCalledWith("(prefers-color-scheme: dark)");
            expect(window.matchMedia).toBeCalledTimes(1);

            window.matchMedia.mockRestore();
        });

        test('theme not in local storage: system is dark', () => {
            window.matchMedia = jest.fn(() => ({ matches: true }));

            themeToggle('theme-toggle', 'theme-toggle');

            expect(document.querySelector('.theme-toggle__marker--dark')).not.toBeNull();
            expect(document.querySelector('.theme-toggle__marker--light')).toBeNull();

            expect(window.matchMedia).toBeCalledWith("(prefers-color-scheme: dark)");
            expect(window.matchMedia).toBeCalledTimes(1);

            window.matchMedia.mockRestore();
        });

        test('theme in local storage is light', () => {
            window.matchMedia = jest.fn(() => ({ matches: true }));
            window.localStorage.setItem('theme', 'light');

            themeToggle('theme-toggle', 'theme-toggle');

            expect(document.querySelector('.theme-toggle__marker--light')).not.toBeNull();
            expect(document.querySelector('.theme-toggle__marker--dark')).toBeNull();

            window.matchMedia.mockRestore();
        });

        test('theme in local storage is dark', () => {
            window.matchMedia = jest.fn(() => ({ matches: false }));
            window.localStorage.setItem('theme', 'dark');

            themeToggle('theme-toggle', 'theme-toggle');

            expect(document.querySelector('.theme-toggle__marker--dark')).not.toBeNull();
            expect(document.querySelector('.theme-toggle__marker--light')).toBeNull();

            window.matchMedia.mockRestore();
        });
    });

    describe('should toggle the theme', () => {
        test('dark theme', () => {

        });

        test('light theme', () => {

        });
    })
});
