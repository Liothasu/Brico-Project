import '../css/app.scss';
import {Dropdown} from 'bootstrap';
import {Tooltip, Toast, Popover} from 'bootstrap';
require('@fortawesome/fontawesome-free/css/all.min.css');
require('@fortawesome/fontawesome-free/js/all.js');

document.addEventListener('DOMContentLoaded', function() {
    const passwordField = document.querySelector('#registration_form_plainPassword_first');
    const rules = document.querySelectorAll('.password-rules li');

    // Password rules
    passwordField.addEventListener('input', function () {
        const password = passwordField.value;

        const containsUppercase = /[A-Z]/.test(password);
        const containsLowercase = /[a-z]/.test(password);
        const containsDigit = /\d/.test(password);
        const containsSpecialChar = /[^A-Za-z0-9]/.test(password);
        const isLengthValid = password.length >= 8;

        rules.forEach(function (rule) {
            const ruleName = rule.className;
            switch (ruleName) {
                case 'rule-length':
                    if (isLengthValid) {
                        rule.style.color = 'green';
                    } else {
                        rule.style.color = '';
                    }
                    break;
                case 'rule-uppercase':
                    if (containsUppercase) {
                        rule.style.color = 'green';
                    } else {
                        rule.style.color = '';
                    }
                    break;
                case 'rule-lowercase':
                    if (containsLowercase) {
                        rule.style.color = 'green';
                    } else {
                        rule.style.color = '';
                    }
                    break;
                case 'rule-digit':
                    if (containsDigit) {
                        rule.style.color = 'green';
                    } else {
                        rule.style.color = '';
                    }
                    break;
                case 'rule-special-char':
                    if (containsSpecialChar) {
                        rule.style.color = 'green';
                    } else {
                        rule.style.color = '';
                    }
                    break;
            }
        });
    });
});