/**
 * The script enhances behavior of a label for the file input field. It allows to show a filename in it.
 *
 * @param inputClass
 */
export const fileInput = function(inputClass) {
    const fileInputs = document.querySelectorAll(`.${inputClass}`);
    fileInputs.forEach((input) => {
        input.addEventListener('change', (e) => {
            const inputName = input.name;
            const lbl = document.querySelector(`label[for="${inputName}"]`);
            const files = e.target.files;

            if (files.length > 0) {
                lbl.textContent = files[0].name;
            } else {
                lbl.textContent = 'Выберите файл';
            }
        });
    });
};
