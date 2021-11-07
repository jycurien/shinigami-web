document.addEventListener("DOMContentLoaded", function() {
    const modal = document.getElementById('delete_article_modal');
    modal.addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget;
        const modal_valid_href = button.getAttribute('data-bs-modal_valid_href');
        modal.querySelector('.js-modal-valid-btn').href = modal_valid_href;
    })
});