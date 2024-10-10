import {contactForm} from '../../common/scripts/contact-form';
import './index.page.scss';
import {fileInput} from '../../common/scripts/file-input';
import {popup} from '../../common/scripts/popup';
import {imagePreview} from './image-preview';

fileInput('contact-form__file-input');
contactForm('contact-form', 'contact-form');

popup('image-popup');
imagePreview('preview-trigger', 'image-preview');