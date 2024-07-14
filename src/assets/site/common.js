import {mobileMenu} from '../common/scripts/mobile-menu';
import './common.scss';
import {gaConversions} from '../common/scripts/ga-conversions';
import {popup} from "../common/scripts/popup";
import {contactForm} from "../common/scripts/contact-form";

mobileMenu('mobile-menu', 'header__menu-popup');
popup('contact-form-popup');
gaConversions();
contactForm('contact-popup', 'contact-form');
