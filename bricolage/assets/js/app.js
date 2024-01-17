import '../css/app.scss';
import {Dropdown} from 'bootstrap';
import {Tooltip, Toast, Popover} from 'bootstrap';
require('@fortawesome/fontawesome-free/css/all.min.css');
require('@fortawesome/fontawesome-free/js/all.js');

document.addEventListener('DOMContentLoaded', function() {
    new App();
    var problemTypeField = document.getElementById('dispute_problemType');
    var blogField = document.getElementById('blog-field');
    var projectField = document.getElementById('project-field');
    var commentField = document.getElementById('comment-field');
    var orderField = document.getElementById('order-field');

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

class App {
    // colorSchemeLocalStorageKey;

    // constructor() {
    //     this.colorSchemeLocalStorageKey = 'blog/colorScheme';

    //     this.createColorSchemeSelector();
    //     this.enableDropdowns();
    // }

    // createColorSchemeSelector() {
    //     if (null === document.querySelector('.dropdown-appearance')) {
    //         return;
    //     }

    //     const currentScheme = localStorage.getItem(this.colorSchemeLocalStorageKey) || 'auto';
    //     const colorSchemeSelectors = document.querySelectorAll('.dropdown-appearance a[data-color-scheme]');
    //     const activeColorSchemeSelector = document.querySelector(`.dropdown-appearance a[data-color-scheme="${currentScheme}"]`);

    //     colorSchemeSelectors.forEach((selector) => { selector.classList.remove('active') });
    //     activeColorSchemeSelector.classList.add('active');

    //     colorSchemeSelectors.forEach((selector) => {
    //         selector.addEventListener('click', () => {
    //             const selectedColorScheme = selector.getAttribute('data-color-scheme');
    //             const resolvedColorScheme = 'auto' === selectedColorScheme
    //                 ? matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'
    //                 : selectedColorScheme;

    //             document.body.classList.remove('light-scheme', 'dark-scheme');
    //             document.body.classList.add('light' === resolvedColorScheme ? 'light-scheme' : 'dark-scheme');
    //             document.body.style.colorScheme = resolvedColorScheme;
    //             localStorage.setItem(this.colorSchemeLocalStorageKey, selectedColorScheme);

    //             colorSchemeSelectors.forEach((otherSelector) => { otherSelector.classList.remove('active') });
    //             selector.classList.add('active');
    //         });
    //     });
    // }

    // enableDropdowns() {
    //     const dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
    //     dropdownElementList.map(function (dropdownToggleEl) {
    //         return new Dropdown(dropdownToggleEl);
    //     });
    // }
}