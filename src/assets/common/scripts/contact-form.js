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

    const emailField = form.querySelector('input[name="email"]');
    const tgField = form.querySelector('input[name="telegram"]');

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

    let formValidityMap = {
        email: false,
        telegram: false,
        message: false,
        _token: false,
    };

    const validatorMap = {
        _token: (value) => value !== '',
        message: (value) => value !== ''
    };

    const isValueCorrectEmail = (value) => {
        const emailRx = /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,})+$/;
        return emailRx.test(value);
    };

    const validateForm = () => {
        const fields = Array.from(form.querySelectorAll('input, textarea'));

        const fieldValidationMap = fields
            .filter(field => !!validatorMap[field.name])
            .reduce((fieldsMap, field) => {
                fieldsMap[field.name] = validatorMap[field.name](field.value);
                return fieldsMap;
            }, {});

        formValidityMap = Object.assign({}, formValidityMap, fieldValidationMap);

        const errors = [];

        if (tgField.value !== '' || emailField.value !== '') {
            formValidityMap.telegram = true;
            formValidityMap.email = true;
        } else {
            formValidityMap.email = false;
            formValidityMap.telegram = false;
            errors.push('Укажите, пожалуйста, или email или telegram');
        }

        if (emailField.value !== '' && !isValueCorrectEmail(emailField.value)) {
            formValidityMap.email = false;
            errors.push('Указан некорректный email');
        }

        const isNotValid = Object.keys(formValidityMap).some(k => formValidityMap[k] !== true);
        if (isNotValid) {
            errors.push('Форма заполнена некорректно');
        }

        return errors;
    };

    const toggleErrorClassIfFieldIsValid = (field) => {
        field.classList.toggle(`${baseClass}__field--error`, !formValidityMap[field.name]);
    };

    const submitForm = async () => {
        emitAnalyticsEvent('contact_form_submitted');

        const allFormFields = Array.from(form.querySelectorAll('input, textarea'));

        const formData = new FormData();
        allFormFields.forEach((field) => {
            formData.append(field.name, field.value);
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

            statusLabel.innerText = 'Сообщение отправлено!';
        }

        setTimeout(() => statusLabel.innerText = '', 2000);
    };

    submitBtn.addEventListener('click', async () => {
        const errors = validateForm();
        const fields = Array.from(form.querySelectorAll('input, textarea'));
        fields.forEach(toggleErrorClassIfFieldIsValid);

        if (!hasFormBeenSubmitted) {
            fields.forEach((field) => {
                field.addEventListener('change', () => {
                    const errs = validateForm();
                    const allFormFields = Array.from(form.querySelectorAll('input, textarea'));
                    allFormFields.forEach(f => toggleErrorClassIfFieldIsValid(f));
                    statusLabel.innerHTML = errs.join('<br/>');
                });
            });

            hasFormBeenSubmitted = true;
        }

        if (errors.length > 0) {
            emitAnalyticsEvent('contact_form_invalid');

            statusLabel.innerHTML = errors.join('<br/>');
            return;
        }

        await submitForm();
    });
};

