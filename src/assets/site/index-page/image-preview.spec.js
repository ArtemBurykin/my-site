/**
 *  @jest-environment jsdom
 */
import {imagePreview} from './image-preview';

describe('showPopup', () => {
    beforeEach(() => {
        document.body.innerHTML = `
            <body>
                <div class="image-trigger" id="trigger" data-image="/image.png">Show</div>
                <div id="preview"><img src="other.png"></div>
            </body>
            `;

        imagePreview('image-trigger', 'preview');
    });

    afterEach(() => {
        document.body.innerHTML = '';
    });

    test('should show the popup and hide it on the close btn click', () => {
        document.getElementById('trigger').click();
        const previewEl = document.getElementById('preview');
        expect(previewEl.children.length).toBe(1);
        expect(previewEl.children.item(0).getAttribute('src')).toBe('/image.png');
    });
});
