/**
 *  @jest-environment jsdom
 */
import {popup} from './popup';

describe('showPopup', () => {
    beforeEach(() => {
        document.body.innerHTML = `
            <body>
                <div class="popup-trigger" id="trigger" data-trigger-popup-id="popup">Open</div>
                <div class="popup" id="popup">
                    <div class="popup__overlay" id="overlay"></div>
                    <div class="popup__close" id="closeBtn"></div>
                </div>
                <div class="popup" id="other"></div>
            </body>
            `;

        popup('popup');
    });

    afterEach(() => {
        document.body.innerHTML = '';
    });

    test('should show the popup and hide it on the close btn click', () => {
        document.getElementById('trigger').click();
        expect(document.getElementById('popup').classList.contains('popup--open')).toBeTruthy();
        expect(document.getElementById('other').classList.contains('popup--open')).toBeFalsy();
        document.getElementById('closeBtn').click();
        expect(document.getElementById('popup').classList.contains('popup--open')).toBeFalsy();
    });

    test('should show the popup and hide it on the overlay click', () => {
        document.getElementById('trigger').click();
        expect(document.getElementById('popup').classList.contains('popup--open')).toBeTruthy();
        document.getElementById('overlay').click();
        expect(document.getElementById('popup').classList.contains('popup--open')).toBeFalsy();
    });
});
