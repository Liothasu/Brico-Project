import '../css/app.scss';
import {Dropdown} from 'bootstrap';
import {Tooltip, Toast, Popover} from 'bootstrap';
require('@fortawesome/fontawesome-free/css/all.min.css');
require('@fortawesome/fontawesome-free/js/all.js');

document.addEventListener('DOMContentLoaded', function() {
    const passwordField = document.querySelector('#registration_form_plainPassword_first');
    const rules = document.querySelectorAll('.password-rules li');

    // Dispute form
    var problemTypeField = document.getElementById('dispute_problemType');
    var blogField = document.getElementById('blog-field');
    var projectField = document.getElementById('project-field');
    var commentField = document.getElementById('comment-field');
    var orderField = document.getElementById('order-field');

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

    function updateFieldVisibility() {
        if (problemTypeField.value === 'Blog') {
            blogField.style.display = 'block';
            projectField.style.display = 'none';
            commentField.style.display = 'none';
            orderField.style.display = 'none';
        } else if (problemTypeField.value === 'Project') {
            projectField.style.display = 'block';
            blogField.style.display = 'none';
            commentField.style.display = 'none';
            orderField.style.display = 'none';
        } else if (problemTypeField.value === 'Comment') {
            commentField.style.display = 'block';
            blogField.style.display = 'none';
            projectField.style.display = 'none';
            orderField.style.display = 'none';
        } else if (problemTypeField.value === 'Order') {
            orderField.style.display = 'block';
            blogField.style.display = 'none';
            projectField.style.display = 'none';
            commentField.style.display = 'none';
        } else {
            blogField.style.display = 'none';
            projectField.style.display = 'none';
            commentField.style.display = 'none';
            orderField.style.display = 'none';
        }
    }

    updateFieldVisibility();

    problemTypeField.addEventListener('change', function () {
        updateFieldVisibility();
    });
});