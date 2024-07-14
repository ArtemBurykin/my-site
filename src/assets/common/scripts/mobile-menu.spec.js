/**
 *  @jest-environment jsdom
 */
import {mobileMenu} from './mobile-menu';

describe('mobileMenu', () => {
    beforeEach(() => {
        document.body.innerHTML = `
            <body>
                <div class="header__menu header__menu--mobile">
                    <img class="header__menu-mobile-icon" id="mobile-menu-show">
                    <nav class="header__menu-popup header__menu-popup--hidden">
                        <div class="header__menu-popup-close" id="mobile-menu-close"></div>
                    </nav>
                </div>
            </body>
            `;

        mobileMenu('mobile-menu', 'header__menu-popup');
    });

    afterEach(() => {
        document.body.innerHTML = '';
    });

    test('should show and hide the menu', () => {
        document.getElementById('mobile-menu-show').click();
        expect(document.querySelector('.header__menu-popup--hidden')).toBeNull();

        document.getElementById('mobile-menu-close').click();
        expect(document.querySelector('.header__menu-popup--hidden')).not.toBeNull();
    });
});
