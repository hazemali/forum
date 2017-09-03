window._ = require('lodash');

/**
 * We'll load jQuery and the Bootstrap jQuery plugin which provides support
 * for JavaScript based Bootstrap features such as modals and tabs. This
 * code may be modified to fit the specific needs of your application.
 */

try {
    window.$ = window.jQuery = require('jquery');

    require('bootstrap-sass');
} catch (e) {
}

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-CSRF-TOKEN'] = window.app.csrfToken;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

window.Vue = require('vue');

/**
 * @type {moment|((inp?:moment.MomentInput, format?:moment.MomentFormatSpecification, language?:string, strict?:boolean)=>moment.Moment)|((inp?:moment.MomentInput, format?:moment.MomentFormatSpecification, strict?:boolean)=>moment.Moment)}
 */
var moment = require('moment');


window.Vue.prototype.authorize = function (model, id) {

    if (window.app.signedIn == false) return;

    var policies = JSON.parse(window.app.policies);

    policies = policies.filter(function (value) {
        if (value['policy_type'] == model && value['policy_id'] == id) {
            return value;
        }
    });

    if (policies.length == 1) {
        return true;
    }


};


window.events = new Vue();
window.flash = function (message) {
    window.events.$emit('flash', message);
}

window.flashError = function (message) {
    window.events.$emit('flashError', message);
}
