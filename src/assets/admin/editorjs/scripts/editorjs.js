import EditorJS from '@editorjs/editorjs';
import Image from './editorjs-blocks/image/image';
import Header from '@editorjs/header';
import List from '@editorjs/list';
import editorjsCodecup from '@calumk/editorjs-codecup';

/**
 * The storage of all initialized editorjs instances.
 * @type {EditorJS[]} the key - the holder id, the value - the instance of EditorJs
 */
window.editorsJs = [];

/**
 * @param {string} holderId the id of the DOM element to create an editor instance for
 * @param {string} data    the field data
 */
window.initEditorjs = (holderId, data) => {
    const configEditorjs = (holderId, data) => {
        const config = {};

        config.holder = holderId;
        config.data = data;
        config.tools = {
            header: Header,
            list: List,
            image: {
                class: Image,
                config: {
                    field: 'file',
                    endpoints: {
                        byFile: '/editorjs/uploadFile',
                    },
                },
            },
            code : editorjsCodecup
        };

        const saveChanges = async function(holderId) {
            const editorHolder = document.getElementById(holderId);
            const editorInput = document.getElementById(
                editorHolder.getAttribute('data-input-id')
            );
            const editor = window.editorsJs[holderId];

            const savePromise = editor.save().then((outputData) => {
                editorInput.value = JSON.stringify(outputData);
            });

            await savePromise;
        };

        config.onChange = async function () {
            await saveChanges(config.holder);
        };

        return config;
    };

    const config = configEditorjs(holderId, data);
    window.editorsJs[holderId] = new EditorJS(config);
};
