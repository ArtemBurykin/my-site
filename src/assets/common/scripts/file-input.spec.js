/**
 *  @jest-environment jsdom
 */
import {fileInput} from './file-input.js';

describe('fileInput', () => {

    beforeEach(() => {
        document.body.innerHTML = `
                <label for="file" class="file-input__lbl">Выберите файл</label>
                <input class="file-input" type="file" id="file" name="file"/>
            `;

        fileInput('file-input', 'file-input__lbl');
    });

    afterEach(() => {
        document.body.innerHTML = '';
    });

    test('should change the value of the label', () => {
        const uploadedFile = new File([], 'test.jpeg', {
            type: 'image/jpeg'
        });

        const eventWithFile = new CustomEvent('change', { bubbles: true });
        Object.defineProperty(eventWithFile, 'target', {
            value: { files: [uploadedFile] },
            enumerable: true
        });
        document.querySelector('.file-input').dispatchEvent(eventWithFile);

        const lbl = document.querySelector('.file-input__lbl');

        expect(lbl.textContent).toBe('test.jpeg');

        const emptyEvent = new CustomEvent('change', { bubbles: true });
        Object.defineProperty(emptyEvent, 'target', {
            value: { files: [] },
            enumerable: true
        });
        document.querySelector('.file-input').dispatchEvent(emptyEvent);

        expect(lbl.textContent).toBe('Выберите файл');
    });
});
