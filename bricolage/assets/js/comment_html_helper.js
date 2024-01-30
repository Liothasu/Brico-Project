import TimeAgo from 'javascript-time-ago';
import fr from 'javascript-time-ago/locale/fr';

TimeAgo.addDefaultLocale(fr);

export function getCommentElement(comment, userId, fromEdit = false) {
    let html = fromEdit ? '' : '<div class="card mb-3">'

    const timeAgo = new TimeAgo('fr-FR');
    const createdAt = timeAgo.format(new Date(comment.createdAt));

    html += `<div class="comment-content ms-3">
                <h5 class="card-title" style="margin-bottom: 0 !important;">
                    <a class="text-decoration-none" href="/profile/show/${comment.username}">
                        <span class="comment-author">${comment.username}</span>
                    </a>
                </h5>
                <small>${createdAt}</small>
                <p class="card-text">${comment.content}</p>
            </div>`;

    html += `<div class="card-footer">
            <button type="button" class="btn btn-primary" id="show-reply-dialog-button" data-id="${comment.id}">Reply</button>`;

    if (userId && comment.userId === parseInt(userId)) {
        html += `<button type="button" class="btn btn-danger ms-2 float-end" id="delete-comment-button" data-id="${comment.id}">Delete</button>
            <button type="button" class="btn btn-info float-end" id="edit-comment-button" data-action="showEditArea" data-id="${comment.id}">Edit</button>`;
    }

    html += '</div>';

    if (!fromEdit) {
        html += '</div>';
    }

    return html;
}

/**
 *
 * @param {string} commentId
 * @returns {string}
 */
export function getReplyDialogElement(commentId) {
    return ` <form class="reply-form my-2" id="reply-dialog-${commentId}">
                <textarea class="form-control" placeholder="Add a reply..." id="answer-content" name="comment[content]" required></textarea>
                <input type="hidden" name="comment[id]" value="${commentId}">
                <div class="text-end" id="reply-action">
                <button type="submit" class="btn btn-primary mt-2" id="answer-button">Reply</button>
                <button type="button" class="btn btn-danger mt-2" id="hide-reply-dialog-button">Cancel</button>
                </div>
             </form>`;
}
