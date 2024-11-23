import {mobileMenu} from '../common/scripts/mobile-menu';
import './common.scss';
import {popup} from '../common/scripts/popup';
import {contactForm} from '../common/scripts/contact-form';
import {analyticsConversions} from '../common/scripts/analytics-conversions';

mobileMenu('mobile-menu', 'header__menu-popup');
popup('contact-form-popup');
contactForm('contact-popup', 'contact-form');

window.analitycsConvs = analyticsConversions;
