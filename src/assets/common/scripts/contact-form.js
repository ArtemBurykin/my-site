/**
 * Script of a feedback form.
 *
 * @param {string} formId    the id of the form to add script to that element
 * @param {string} baseClass the form uses BEM based classes, here we set error classes etc, based on the baseClass
 */
export const contactForm = function(formId, baseClass) {
    const form = document.getElementById(formId);
    const submitBtn = form.querySelector(`#${formId}-submit`);
    const statusLabel = form.querySelector(`#${formId}-status`);

    const emitAnalyticsEvent = (name) => {
        const conversionEvent = new CustomEvent(
            'analyticsEventOccurred',
            {detail: {name}}
        );
        document.dispatchEvent(conversionEvent);
    };

    /**
     * We'll use it in order to set the listener to mark fields either valid or invalid only once after the form
     * has been submitted.
     */
    let hasFormBeenSubmitted = false;

    const isValueCorrectEmail = (value) => {
        const emailRx = /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,})+$/;
        return emailRx.test(value);
    };

    const validatorMap = {
        _token: (value) => value !== '',
        email: (value) => value !== '' && isValueCorrectEmail(value),
        theme: (value) => value !== '',
        message: (value) => value !== ''
    };

    const isFieldValid = (field) => {
        if (validatorMap[field.name]) {
            return validatorMap[field.name](field.value);
        }

        return true;
    };

    const toggleErrorClassIfFieldIsValid = (field) => {
        field.classList.toggle(`${baseClass}__field--error`, !isFieldValid(field));
    };

    const submitForm = async () => {
        emitAnalyticsEvent('contact_form_submitted');

        const allFormFields = Array.from(form.querySelectorAll('input, textarea'));

        const formData = new FormData();
        allFormFields.forEach((field) => {
            if (field.type !== 'file') {
                formData.append(field.name, field.value);
            } else if (field.files.length) {
                formData.append(field.name, field.files[0]);
            }
        });

        statusLabel.innerText = 'Отправка...';

        const res = await fetch('/api/contact-us', {
            method: 'POST',
            body: formData,
        });

        if (!res.ok) {
            const genericError = 'Произошла ошибка при отправке';

            res.json()
                .then((error) => {
                    statusLabel.innerText = error.message || genericError;
                }).catch(() => {
                    statusLabel.innerText = genericError;
                });
        } else {
            const dataFormFields = Array.from(form.querySelectorAll(`.${baseClass}__field`));
            dataFormFields.forEach((field) => field.value = '');
            form.querySelectorAll('input[type="file"]').forEach((f) => {
                f.value = null;
                f.dispatchEvent(new Event('change'));
            });

            statusLabel.innerText = 'Сообщение отправлено!';
        }

        setTimeout(() => statusLabel.innerText = '', 2000);
    };

    submitBtn.addEventListener('click', async () => {
        const fields = Array.from(form.querySelectorAll('input, textarea'));
        fields.forEach(toggleErrorClassIfFieldIsValid);

        if (!hasFormBeenSubmitted) {
            fields.forEach((field) => {
                field.addEventListener('change', () => toggleErrorClassIfFieldIsValid(field));
            });

            hasFormBeenSubmitted = true;
        }

        const isFormValid = fields.reduce((isFormValid, field) => isFormValid && isFieldValid(field), true);

        if (!isFormValid) {
            emitAnalyticsEvent('contact_form_invalid');

            statusLabel.innerText = 'Форма заполнена некорректно';
            return;
        }

        await submitForm();
    });
};

