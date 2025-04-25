document.addEventListener('DOMContentLoaded', function() {
    // Get modal elements
    const modal = document.getElementById('commentModal');
    const commentText = document.getElementById('commentText');
    const closeBtn = document.getElementById('closeModal');
    const closeModalButton = document.getElementById('closeModalButton');
    const commentButtons = document.querySelectorAll('.comment-icon');

    // Show modal when clicking on a comment icon
    commentButtons.forEach(button => {
        button.addEventListener('click', function() {
            const comment = this.getAttribute('data-comment');
            commentText.textContent = comment;
            modal.classList.remove('hidden');
        });
    });

    // Close modal functions
    function closeModal() {
        modal.classList.add('hidden');
    }

    closeBtn.addEventListener('click', closeModal);
    closeModalButton.addEventListener('click', closeModal);

    // Close modal when clicking outside
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeModal();
        }
    });

    // Close modal with ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeModal();
        }
    });
});
