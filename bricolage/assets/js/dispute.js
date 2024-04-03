import '../css/app.scss';
import {Dropdown} from 'bootstrap';
import {Tooltip, Toast, Popover} from 'bootstrap';
require('@fortawesome/fontawesome-free/css/all.min.css');
require('@fortawesome/fontawesome-free/js/all.js');

document.addEventListener('DOMContentLoaded', function() {
    const problemTypeField = document.getElementById('dispute_problemType');
    const blogField = document.getElementById('blog-field');
    const projectField = document.getElementById('project-field');
    const commentField = document.getElementById('comment-field');
    const orderField = document.getElementById('order-field');

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