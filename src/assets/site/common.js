import {contactForm} from '../common/scripts/contact-form';
import {themeToggle} from '../common/scripts/theme-toggle';
import {stickySideNav} from '../common/scripts/sticky-side-nav';
import {mobileMenu} from "../common/scripts/mobile-menu";

contactForm('contact-form', 'contact-form');
themeToggle('theme-toggle', 'theme-toggle');
stickySideNav();
mobileMenu('mobile-menu', 'header__menu-popup');

