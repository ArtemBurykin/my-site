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

    const agreeWithStorageOfPersonalData = () => {
        const agreementChbx = document.getElementById('check');
        agreementChbx.checked = true;
    }

    const fillFields = ({email, message, telegram = ''}) => {
        const emailField = document.querySelector(`.${baseClass}__field[name="email"]`);
        emailField.value = email;
        emailField.dispatchEvent(new Event('change'));

        const messageField = document.querySelector(`.${baseClass}__field[name="message"]`);
        messageField.value = message;
        messageField.dispatchEvent(new Event('change'));

        const telegramField = document.querySelector(`.${baseClass}__field[name="telegram"]`);
        telegramField.value = telegram;
        telegramField.dispatchEvent(new Event('change'));
    };

    beforeEach(() => {
        global.fetch = jest.fn();

        document.body.innerHTML = `
            <div class="${baseClass} form-class" id="${formId}">
                <h2 class="contact-form__header">Order the tour or ask a question</h2>

                <input type="hidden" name="_token" value="${csrfToken}"/>

                <label for="email" class="contact-form__label">Your email:</label>
                <input class="${baseClass}__field" type="email" id="email" name="email"/>
                
                <label for="telegram" class="contact-form__label">Your tg:</label>
                <input class="${baseClass}__field" type="text" id="telegram" name="telegram"/>

                <label for="message" class="contact-form__label">Message</label>
                <textarea class="${baseClass}__field contact-form__field--textarea" id="message"
                          name="message" rows="4" cols="50"></textarea>
                
                <input type="checkbox" name="check" value="agree" id="check">
                <label for="data-check" class="contact-form__label">Я согласен</label>

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

        fillFields({ email: 'test@gmail.com', message: 'a message', telegram: '@some'});
        agreeWithStorageOfPersonalData();

        document.querySelector(`#${formId}-submit`).click();

        expect(fetch).toHaveBeenCalledTimes(1);

        expect(fetch.mock.calls[0][0]).toBe(apiUrl);

        const options = fetch.mock.calls[0][1];
        expect(options.method).toBe('POST');

        const formData = options.body;
        expect(formData.get('email')).toBe('test@gmail.com');
        expect(formData.get('message')).toBe('a message');
        expect(formData.get('telegram')).toBe('@some');
        expect(formData.get('_token')).toBe(csrfToken);

        setTimeout(() => {
            expect(document.querySelector(`#${formId}-status`).innerText).toBe('Сообщение отправлено!');

            expect(document.querySelector(`.${baseClass}__field[name="email"]`).value).toBe('');
            expect(document.querySelector(`.${baseClass}__field[name="message"]`).value).toBe('');
            expect(document.querySelector(`.${baseClass}__field[name="telegram"]`).value).toBe('');
            expect(document.querySelector('input[name="_token"]').value).not.toBe('');

            done();
        });
    });

    test('success: email is empty, but telegram is filled', () => {
        global.fetch = jest.fn(() =>
            Promise.resolve({
                json: () => Promise.resolve(''),
                ok: true
            })
        );

        fillFields({ email: '', message: 'a message', telegram: '@some'});
        agreeWithStorageOfPersonalData();

        document.querySelector(`#${formId}-submit`).click();

        expect(fetch).toHaveBeenCalledTimes(1);
    });

    test('success: email is not empty, but telegram is', () => {
        global.fetch = jest.fn(() =>
            Promise.resolve({
                json: () => Promise.resolve(''),
                ok: true
            })
        );

        fillFields({ email: 'test@test.com', message: 'a message', telegram: ''});
        agreeWithStorageOfPersonalData();

        document.querySelector(`#${formId}-submit`).click();

        expect(fetch).toHaveBeenCalledTimes(1);
    });

    test('error: personal data check is off', (done) => {
        global.fetch = jest.fn(() =>
            Promise.resolve({
                json: () => Promise.resolve(''),
                ok: true
            })
        );

        fillFields({ email: '', message: 'a message', telegram: '@some'});

        document.querySelector(`#${formId}-submit`).click();

        expect(fetch).toHaveBeenCalledTimes(0);

        setTimeout(() => {
            expect(document.querySelector(`.${baseClass}__field[name="telegram"]`).value).toBe('@some');
            expect(document.querySelector(`.${baseClass}__field[name="message"]`).value).toBe('a message');

            expect(document.querySelector(`#${formId}-status`).innerHTML)
                .toContain('Необходимо согласие на обработку данных');

            done();
        }, 0);
    });

    test('error: should show the error', (done) => {
        global.fetch = jest.fn(() =>
            Promise.resolve({
                json: () => Promise.resolve({ message: 'The process failed' }),
                ok: false
            })
        );

        fillFields({ email: 'test@gmail.com', message: 'a message' });
        agreeWithStorageOfPersonalData();

        document.querySelector(`#${formId}-submit`).click();

        expect(fetch).toHaveBeenCalledTimes(1);

        expect(fetch.mock.calls[0][0]).toBe(apiUrl);

        const options = fetch.mock.calls[0][1];
        expect(options.method).toBe('POST');

        const formData = options.body;
        expect(formData.get('email')).toBe('test@gmail.com');
        expect(formData.get('message')).toBe('a message');
        expect(formData.get('_token')).toBe(csrfToken);

        setTimeout(() => {
            expect(document.querySelector(`.${baseClass}__field[name="email"]`).value).toBe('test@gmail.com');
            expect(document.querySelector(`.${baseClass}__field[name="message"]`).value).toBe('a message');

            expect(document.querySelector(`#${formId}-status`).innerText).toBe('The process failed');

            done();
        }, 0);
    });

    test('error: error promise is rejected, should show the general description', (done) => {
        global.fetch = jest.fn(() =>
            Promise.resolve({
                json: () => Promise.reject('Not correct JSON'),
                ok: false
            })
        );

        fillFields({ email: 'test@gmail.com', message: 'a message' });
        agreeWithStorageOfPersonalData();

        document.querySelector(`#${formId}-submit`).click();

        setTimeout(() => {
            expect(document.querySelector(`#${formId}-status`).innerText).toBe('Произошла ошибка при отправке');

            done();
        }, 0);
    });

    test('error: the error is not specified, should show the general description', (done) => {
        global.fetch = jest.fn(() =>
            Promise.resolve({
                json: () => Promise.resolve({}),
                ok: false
            })
        );

        fillFields({ email: 'test@gmail.com', message: 'a message' });
        agreeWithStorageOfPersonalData();

        document.querySelector(`#${formId}-submit`).click();

        setTimeout(() => {
            expect(document.querySelector(`#${formId}-status`).innerText).toBe('Произошла ошибка при отправке');

            done();
        }, 0);
    });

    test('all fields are empty: should show errors', (done) => {
        fillFields({ email: '', message: '', telegram: ''});

        document.querySelector(`#${formId}-submit`).click();

        expect(fetch).toHaveBeenCalledTimes(0);

        setTimeout(() => {
            expect(document.querySelector(`#${formId}-status`).innerHTML)
                .toContain('Укажите, пожалуйста, или email или telegram');

            expect(
                document.querySelector(`.${baseClass}__field[name="email"]`).classList.contains(errorClass)
            ).toBe(true);

            expect(
                document.querySelector(`.${baseClass}__field[name="message"]`).classList.contains(errorClass)
            ).toBe(true);

            expect(
                document.querySelector(`.${baseClass}__field[name="telegram"]`).classList.contains(errorClass)
            ).toBe(true);

            done();
        }, 0);
    });

    test('the email is invalid: should show the error', (done) => {
        fillFields({ email: 'test', message: 'test' });

        document.querySelector(`#${formId}-submit`).click();

        expect(fetch).toHaveBeenCalledTimes(0);

        setTimeout(() => {
            expect(document.querySelector(`#${formId}-status`).innerHTML)
                .toContain('Указан некорректный email');

            expect(
                document.querySelector(`.${baseClass}__field[name="email"]`).classList.contains(errorClass)
            ).toBe(true);

            expect(
                document.querySelector(`.${baseClass}__field[name="message"]`).classList.contains(errorClass)
            ).toBe(false);

            done();
        }, 0);
    });

    test('the csrfToken is empty', (done) => {
        document.querySelector('input[name="_token"]').value = '';
        fillFields({ email: 'test', message: 'test' });

        document.querySelector(`#${formId}-submit`).click();

        expect(fetch).toHaveBeenCalledTimes(0);

        setTimeout(() => {
            expect(document.querySelector(`#${formId}-status`).innerHTML).toContain('Форма заполнена некорректно');

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

        fillFields({ email: 'test@gmail.com', message: 'a message' });
        agreeWithStorageOfPersonalData();

        document.querySelector(`#${formId}-submit`).click();

        setTimeout(() => {
            expect(document.querySelector(`#${formId}-status`).innerText).toBe('Отправка...');

            setTimeout(() => {
                expect(document.querySelector(`#${formId}-status`).innerText).not.toBe('Отправка...');

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

        fillFields({ email: 'test@gmail.com', message: 'a message' });
        agreeWithStorageOfPersonalData();

        document.querySelector(`#${formId}-submit`).click();

        setTimeout(() => {
            expect(document.querySelector(`#${formId}-status`).innerText).toBe('Отправка...');

            setTimeout(() => {
                expect(document.querySelector(`#${formId}-status`).innerText).not.toBe('Отправка...');

                done();
            }, 5);
        }, 5);
    });

    test('if the form has not been submitted should not add error classes while changing values', () => {
        fillFields({ email: '', message: '' });

        expect(
            document.querySelector(`.${baseClass}__field[name="email"]`).classList.contains(errorClass)
        ).toBe(false);

        expect(
            document.querySelector(`.${baseClass}__field[name="telegram"]`).classList.contains(errorClass)
        ).toBe(false);

        expect(
            document.querySelector(`.${baseClass}__field[name="message"]`).classList.contains(errorClass)
        ).toBe(false);
    });

    test('if the form has been submitted should add error classes while changing values', (done) => {
        fillFields({ email: '', message: '', telegram: '' });
        agreeWithStorageOfPersonalData();

        document.querySelector(`#${formId}-submit`).click();

        setTimeout(() => {
            expect(
                document.querySelector(`.${baseClass}__field[name="email"]`).classList.contains(errorClass)
            ).toBe(true);

            expect(
                document.querySelector(`.${baseClass}__field[name="telegram"]`).classList.contains(errorClass)
            ).toBe(true);

            expect(
                document.querySelector(`.${baseClass}__field[name="message"]`).classList.contains(errorClass)
            ).toBe(true);

            fillFields({ email: 'test', message: 'test' });

            expect(
                document.querySelector(`.${baseClass}__field[name="email"]`).classList.contains(errorClass)
            ).toBe(true);

            expect(
                document.querySelector(`.${baseClass}__field[name="telegram"]`).classList.contains(errorClass)
            ).toBe(false);

            expect(
                document.querySelector(`.${baseClass}__field[name="message"]`).classList.contains(errorClass)
            ).toBe(false);

            fillFields({ email: '', message: 'test', telegram: '@some' });

            expect(
                document.querySelector(`.${baseClass}__field[name="email"]`).classList.contains(errorClass)
            ).toBe(false);

            expect(
                document.querySelector(`.${baseClass}__field[name="telegram"]`).classList.contains(errorClass)
            ).toBe(false);

            expect(
                document.querySelector(`.${baseClass}__field[name="message"]`).classList.contains(errorClass)
            ).toBe(false);

            fillFields({ email: 'test@gmail.com', message: 'test' });

            expect(
                document.querySelector(`.${baseClass}__field[name="email"]`).classList.contains(errorClass)
            ).toBe(false);

            expect(
                document.querySelector(`.${baseClass}__field[name="telegram"]`).classList.contains(errorClass)
            ).toBe(false);

            expect(
                document.querySelector(`.${baseClass}__field[name="message"]`).classList.contains(errorClass)
            ).toBe(false);

            done();
        });
    });
});