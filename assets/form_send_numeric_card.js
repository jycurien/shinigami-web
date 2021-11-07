document.addEventListener("DOMContentLoaded", function() {
    const modal = document.getElementById('send_numeric_card_modal');
    modal.addEventListener('show.bs.modal', event => {
        const button = event.relatedTarget;
        modal.querySelector('#js-card-number').value =  button.getAttribute('data-bs-card-number');
    })
});