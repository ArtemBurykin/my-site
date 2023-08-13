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
        return validatorMap[field.name](field.value);
    };

    const toggleErrorClassIfFieldIsValid = (field) => {
        field.classList.toggle(`${baseClass}__field--error`, !isFieldValid(field));
    };

    const submitForm = async () => {
        const allFormFields = Array.from(form.querySelectorAll('input, textarea'));

        const formData = new FormData();
        allFormFields.forEach((field) => {
            formData.append(field.name, field.value);
        });

        statusLabel.innerText = 'Sending...';

        const res = await fetch('/api/contact-us', {
            method: 'POST',
            body: formData,
        });

        if (!res.ok) {
            const genericError = 'An error occurred';

            res.json()
                .then((error) => {
                    statusLabel.innerText = error.message || genericError;
                }).catch(() => {
                    statusLabel.innerText = genericError;
                });
        } else {
            const dataFormFields = Array.from(form.querySelectorAll(`.${baseClass}__field`));
            dataFormFields.forEach((field) => field.value = '');
            statusLabel.innerText = 'The message is sent';
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
            statusLabel.innerText = 'The form is invalid';
            return;
        }

        await submitForm();
    });
};

