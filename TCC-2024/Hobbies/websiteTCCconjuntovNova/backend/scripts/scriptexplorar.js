document.addEventListener('DOMContentLoaded', () => {
    const likeBtn = document.querySelector('.like-btn');
    const commentBtn = document.querySelector('.comment-btn');
    const likeCount = document.querySelector('.like-count');
    const commentCount = document.querySelector('.comment-count');
    const commentsSection = document.querySelector('.comments-section');
    const commentsList = document.querySelector('.comments-list');
    const commentInput = document.querySelector('.comment-input');
    const submitComment = document.querySelector('.submit-comment');

    let likes = 0;
    let comments = 0;

    likeBtn.addEventListener('click', () => {
        likes++;
        likeCount.textContent = `${likes} curtidas`;
    });

    commentBtn.addEventListener('click', () => {
        commentsSection.style.display = commentsSection.style.display === 'none' ? 'block' : 'none';
    });

    submitComment.addEventListener('click', () => {
        const commentText = commentInput.value.trim();
        if (commentText) {
            const commentDiv = document.createElement('div');
            commentDiv.classList.add('comment');
            commentDiv.textContent = commentText;
            commentsList.appendChild(commentDiv);
            commentInput.value = '';
            comments++;
            commentCount.textContent = `${comments} comentários`;
        }
    });
});