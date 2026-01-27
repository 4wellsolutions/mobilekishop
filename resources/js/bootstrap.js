import _ from 'lodash';
window._ = _;

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

import $ from 'jquery';
window.jQuery = window.$ = $;

// import * as Popper from '@popperjs/core';
// window.Popper = Popper;

import 'bootstrap/dist/js/bootstrap.bundle.min.js';

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
