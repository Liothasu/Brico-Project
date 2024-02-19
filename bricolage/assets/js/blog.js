import {$} from "./functions/dom";

export class Blog {
    id;

    constructor() {
        this.id = $('.blog-data').dataset.id;
    }

    /**
     * @returns {Promise<Array>}
     */
    async fetchComments() {
        const response = await fetch(`/ajax/blogs/${this.id}/comments`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            method: 'GET'
        });

        return await response.json();
    }

    getData() {
        return JSON.parse($('.blog-data').dataset['content']);
    }
}