import {$} from "./functions/dom";
import Checklist from '@editorjs/checklist';
import EditorJS from '@editorjs/editorjs';
import Embed from '@editorjs/embed';
import Header from '@editorjs/header';
import List from '@editorjs/list';

document.addEventListener('DOMContentLoaded', async () => {
    const blog = new Blog();
    if ($('.blog-data').dataset['isAuthor']) {
        await blog.initializeEditor();
    } else {
        blog.initializeContent();
    }
})

export class Blog {
    id;

    /** @var {EditorJS} */
    editor;

    /** @var {boolean} */
    isPatching = false;

    constructor() {
        this.id = $('.blog-data').dataset.id;
    }

    initializeContent() {
        const data = this.getData();
        const blogContent = $('#blog-content');

        data.blocks.forEach(block => {
            switch (block.type) {
                case 'checklist':
                    blogContent.append(this.handleChecklistBlock(block.data));
                    break;
                case 'embed':
                    blogContent.append(this.handleEmbedBlock(block.data));
                    break;
                case 'header':
                    blogContent.append(this.handleHeaderBlock(block.data))
                    break;
                case 'list':
                    blogContent.append(this.handleListBlock(block.data));
                    break;
                case 'paragraph':
                    blogContent.append(this.handleParagraphBlock(block.data));
                    break;
            }
        });
    }

    async initializeEditor() {
        this.editor = await new EditorJS({
            data: this.getData(),
            holder: 'blog-content',
            onChange: async () => {
                if (!this.isPatching) {
                    await this.patchBlog();
                }
            },
            tools: {
                checklist: Checklist,
                embed: {
                    class: Embed,
                    config: {
                        services: {
                            youtube: true
                        }
                    },
                    inlineToolbar: true
                },
                header: Header,
                list: List
            }
        });

        await this.editor.isReady;

        $('.codex-editor__redactor').style.removeProperty('padding-bottom');
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

    handleChecklistBlock(data) {
        const ul = document.createElement('ul');
        ul.classList.add('list-group', 'list-group-flush')

        data.items.forEach(item => {
            const li = document.createElement('li');
            li.classList.add('list-group-item');

            const div = document.createElement('div');
            div.classList.add('form-check');

            const input = document.createElement('input');
            input.classList.add('form-check-input');
            input.checked = item.checked;
            input.type = 'checkbox';

            const label = document.createElement('label');
            label.classList.add('form-check-label');
            label.innerText = item.text;

            div.append(input);
            div.append(label);
            li.appendChild(div);

            ul.append(li);
        })

        return ul;
    }

    handleEmbedBlock(data) {
        const iframe = document.createElement('iframe');
        iframe.height = data.height;
        iframe.src = data.embed
        iframe.width = data.width;

        return iframe;
    }

    handleHeaderBlock(data) {
        const header = document.createElement(`h${data.level}`);
        header.innerText = data.text;

        return header;
    }

    handleListBlock(data) {
        const tagName = 'ordered' === data.type ? 'ol' : 'ul';
        const list = document.createElement(tagName);

        data.items.forEach(item => {
            const li = document.createElement('li');
            li.innerText = item;

            list.append(li);
        });

        return list;
    }

    handleParagraphBlock(data) {
        const paragraph = document.createElement('p');
        paragraph.innerHTML = data.text;

        return paragraph;
    }

    async patchBlog() {
        this.isPatching = true;

        await fetch(`/api/blogs/${this.id}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/merge-patch+json'
            },
            body: JSON.stringify({ content: await this.editor.save() })
        });

        this.isPatching = false;
    }
}