import './contacts.page.scss';
import {contactForm} from '../../common/scripts/contact-form';
import {fileInput} from '../../common/scripts/file-input';

fileInput('contact-form__file-input');
contactForm('contact-form', 'contact-form');
