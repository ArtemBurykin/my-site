import {themeToggle} from '../common/scripts/theme-toggle';
import {stickySideNav} from '../common/scripts/sticky-side-nav';
import {mobileMenu} from '../common/scripts/mobile-menu';
import './common.scss';
import {gaConversions} from '../common/scripts/ga-conversions';

themeToggle('theme-toggle', 'theme-toggle');
stickySideNav();
mobileMenu('mobile-menu', 'header__menu-popup');
gaConversions();
