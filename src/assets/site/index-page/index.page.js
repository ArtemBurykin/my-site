import {contactForm} from '../../common/scripts/contact-form';
import './index.page.scss';
import {addScrollOnOfferCtaClick} from './scroll-on-offer-cta';
import {fileInput} from '../../common/scripts/file-input';
import {clientsSlider} from './clients-slider';

fileInput('contact-form__file-input');
contactForm('contact-form', 'contact-form');
clientsSlider('clients');

addScrollOnOfferCtaClick();
