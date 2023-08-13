/**
 *  @jest-environment jsdom
 */
import {contactForm} from './contact-form.js';

describe('contactForm', () => {
    const apiUrl = '/api/contact-us';
    const csrfToken = 'a_secret_token';
    const formId = 'form-id';
    const baseClass = 'contact-form';
    const errorClass = `${baseClass}__field--error`;

    const fillFields = ({email, theme, message}) => {
        const emailField = document.querySelector(`.${baseClass}__field[name="email"]`);
        emailField.value = email;
        emailField.dispatchEvent(new Event('change'));

        const themeField = document.querySelector(`.${baseClass}__field[name="theme"]`);
        themeField.value = theme;
        themeField.dispatchEvent(new Event('change'));

        const messageField = document.querySelector(`.${baseClass}__field[name="message"]`);
        messageField.value = message;
        messageField.dispatchEvent(new Event('change'));
    };

    beforeEach(() => {
        global.fetch = jest.fn();

        document.body.innerHTML = `
            <div class="${baseClass} form-class" id="${formId}">
                <h2 class="contact-form__header">Order the tour or ask a question</h2>

                <input type="hidden" name="_token" value="${csrfToken}"/>

                <label for="email" class="contact-form__label">Your email:</label>
                <input class="${baseClass}__field" type="email" id="email" name="email"/>

                <label for="theme" class="contact-form__label">Theme</label>
                <input class="${baseClass}__field" type="text" id="theme" name="theme"/>

                <label for="message" class="contact-form__label">Message</label>
                <textarea class="${baseClass}__field contact-form__field--textarea" id="message"
                          name="message" rows="4" cols="50"></textarea>

                <button class="contact-form__btn" id="form-id-submit">Send message</button>
                <p class="contact-form__status" id="form-id-status"></p>
            </div>
            `;

        contactForm(formId, baseClass);
    });

    afterEach(() => {
        document.body.innerHTML = '';
        fetch.mockRestore();
    });

    test('success: should send data to the backend', (done) => {
        global.fetch = jest.fn(() =>
            Promise.resolve({
                json: () => Promise.resolve(''),
                ok: true
            })
        );

        fillFields({ email: 'test@gmail.com', theme: 'a theme', message: 'a message' });

        document.querySelector(`#${formId}-submit`).click();

        expect(fetch).toHaveBeenCalledTimes(1);

        expect(fetch.mock.calls[0][0]).toBe(apiUrl);

        const options = fetch.mock.calls[0][1];
        expect(options.method).toBe('POST');

        const formData = options.body;
        expect(formData.get('email')).toBe('test@gmail.com');
        expect(formData.get('theme')).toBe('a theme');
        expect(formData.get('message')).toBe('a message');
        expect(formData.get('_token')).toBe(csrfToken);

        setTimeout(() => {
            expect(document.querySelector(`#${formId}-status`).innerText).toBe('The message is sent');

            expect(document.querySelector(`.${baseClass}__field[name="email"]`).value).toBe('');
            expect(document.querySelector(`.${baseClass}__field[name="theme"]`).value).toBe('');
            expect(document.querySelector(`.${baseClass}__field[name="message"]`).value).toBe('');
            expect(document.querySelector('input[name="_token"]').value).not.toBe('');

            done();
        });
    });

    test('error: should show the error', (done) => {
        global.fetch = jest.fn(() =>
            Promise.resolve({
                json: () => Promise.resolve({ message: 'The process failed' }),
                ok: false
            })
        );

        fillFields({ email: 'test@gmail.com', theme: 'a theme', message: 'a message' });

        document.querySelector(`#${formId}-submit`).click();

        expect(fetch).toHaveBeenCalledTimes(1);

        expect(fetch.mock.calls[0][0]).toBe(apiUrl);

        const options = fetch.mock.calls[0][1];
        expect(options.method).toBe('POST');

        const formData = options.body;
        expect(formData.get('email')).toBe('test@gmail.com');
        expect(formData.get('theme')).toBe('a theme');
        expect(formData.get('message')).toBe('a message');
        expect(formData.get('_token')).toBe(csrfToken);

        setTimeout(() => {
            expect(document.querySelector(`.${baseClass}__field[name="email"]`).value).toBe('test@gmail.com');
            expect(document.querySelector(`.${baseClass}__field[name="theme"]`).value).toBe('a theme');
            expect(document.querySelector(`.${baseClass}__field[name="message"]`).value).toBe('a message');

            expect(document.querySelector(`#${formId}-status`).innerText).toBe('The process failed');

            done();
        }, 0);
    });

    test('error: but not correct object, should show the general description', (done) => {
        global.fetch = jest.fn(() =>
            Promise.resolve({
                json: () => Promise.reject('Not correct JSON'),
                ok: false
            })
        );

        fillFields({ email: 'test@gmail.com', theme: 'a theme', message: 'a message' });

        document.querySelector(`#${formId}-submit`).click();

        setTimeout(() => {
            expect(document.querySelector(`#${formId}-status`).innerText).toBe('An error occurred');

            done();
        }, 0);
    });

    test('error: object without message, should show the general description', (done) => {
        global.fetch = jest.fn(() =>
            Promise.resolve({
                json: () => Promise.resolve({}),
                ok: false
            })
        );

        fillFields({ email: 'test@gmail.com', theme: 'a theme', message: 'a message' });

        document.querySelector(`#${formId}-submit`).click();

        setTimeout(() => {
            expect(document.querySelector(`#${formId}-status`).innerText).toBe('An error occurred');

            done();
        }, 0);
    });

    test('fields are empty: should show errors', (done) => {
        fillFields({ email: '', theme: '', message: '' });

        document.querySelector(`#${formId}-submit`).click();

        expect(fetch).toHaveBeenCalledTimes(0);

        setTimeout(() => {
            expect(document.querySelector(`#${formId}-status`).innerText).toBe('The form is invalid');

            expect(
                document.querySelector(`.${baseClass}__field[name="email"]`).classList.contains(errorClass)
            ).toBe(true);

            expect(
                document.querySelector(`.${baseClass}__field[name="theme"]`).classList.contains(errorClass)
            ).toBe(true);

            expect(
                document.querySelector(`.${baseClass}__field[name="message"]`).classList.contains(errorClass)
            ).toBe(true);

            done();
        }, 0);
    });

    test('the email is invalid: should show the error', (done) => {
        fillFields({ email: 'test', theme: 'test', message: 'test' });

        document.querySelector(`#${formId}-submit`).click();

        expect(fetch).toHaveBeenCalledTimes(0);

        setTimeout(() => {
            expect(document.querySelector(`#${formId}-status`).innerText).toBe('The form is invalid');

            expect(
                document.querySelector(`.${baseClass}__field[name="email"]`).classList.contains(errorClass)
            ).toBe(true);

            expect(
                document.querySelector(`.${baseClass}__field[name="theme"]`).classList.contains(errorClass)
            ).toBe(false);

            expect(
                document.querySelector(`.${baseClass}__field[name="message"]`).classList.contains(errorClass)
            ).toBe(false);

            done();
        }, 0);
    });

    test('the csrfToken is empty', (done) => {
        document.querySelector('input[name="_token"]').value = '';
        fillFields({ email: 'test', theme: 'test', message: 'test' });

        document.querySelector(`#${formId}-submit`).click();

        expect(fetch).toHaveBeenCalledTimes(0);

        setTimeout(() => {
            expect(document.querySelector(`#${formId}-status`).innerText).toBe('The form is invalid');

            done();
        }, 0);
    });

    test('success: loader', (done) => {
        global.fetch = jest.fn(() =>
            new Promise((res) => setTimeout(
                () => res({
                    json: () => Promise.resolve(''),
                    ok: true
                }),
                10
            ))
        );

        fillFields({ email: 'test@gmail.com', theme: 'a theme', message: 'a message' });

        document.querySelector(`#${formId}-submit`).click();

        setTimeout(() => {
            expect(document.querySelector(`#${formId}-status`).innerText).toBe('Sending...');

            setTimeout(() => {
                expect(document.querySelector(`#${formId}-status`).innerText).not.toBe('Sending...');

                done();
            }, 5);
        }, 5);
    });

    test('error: loader', (done) => {
        global.fetch = jest.fn(() =>
            new Promise((res) => setTimeout(
                () => res({
                    json: () => Promise.resolve({ message: 'An error' }),
                    ok: false
                }),
                10
            ))
        );

        fillFields({ email: 'test@gmail.com', theme: 'a theme', message: 'a message' });

        document.querySelector(`#${formId}-submit`).click();

        setTimeout(() => {
            expect(document.querySelector(`#${formId}-status`).innerText).toBe('Sending...');

            setTimeout(() => {
                expect(document.querySelector(`#${formId}-status`).innerText).not.toBe('Sending...');

                done();
            }, 5);
        }, 5);
    });

    test('if the form has not been submitted should not add error classes while changing values', () => {
        fillFields({ email: '', theme: '', message: '' });

        expect(
            document.querySelector(`.${baseClass}__field[name="email"]`).classList.contains(errorClass)
        ).toBe(false);

        expect(
            document.querySelector(`.${baseClass}__field[name="theme"]`).classList.contains(errorClass)
        ).toBe(false);

        expect(
            document.querySelector(`.${baseClass}__field[name="message"]`).classList.contains(errorClass)
        ).toBe(false);
    });

    test('if the form has been submitted should add error classes while changing values', (done) => {
        fillFields({ email: '', theme: '', message: '' });

        document.querySelector(`#${formId}-submit`).click();

        setTimeout(() => {
            expect(
                document.querySelector(`.${baseClass}__field[name="email"]`).classList.contains(errorClass)
            ).toBe(true);

            expect(
                document.querySelector(`.${baseClass}__field[name="theme"]`).classList.contains(errorClass)
            ).toBe(true);

            expect(
                document.querySelector(`.${baseClass}__field[name="message"]`).classList.contains(errorClass)
            ).toBe(true);

            fillFields({ email: 'test', theme: 'test', message: 'test' });

            expect(
                document.querySelector(`.${baseClass}__field[name="email"]`).classList.contains(errorClass)
            ).toBe(true);

            expect(
                document.querySelector(`.${baseClass}__field[name="theme"]`).classList.contains(errorClass)
            ).toBe(false);

            expect(
                document.querySelector(`.${baseClass}__field[name="message"]`).classList.contains(errorClass)
            ).toBe(false);

            fillFields({ email: 'test@gmail.com', theme: 'test', message: 'test' });

            expect(
                document.querySelector(`.${baseClass}__field[name="email"]`).classList.contains(errorClass)
            ).toBe(false);

            expect(
                document.querySelector(`.${baseClass}__field[name="theme"]`).classList.contains(errorClass)
            ).toBe(false);

            expect(
                document.querySelector(`.${baseClass}__field[name="message"]`).classList.contains(errorClass)
            ).toBe(false);

            done();
        });
    });
});