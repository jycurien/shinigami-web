window.addEventListener('DOMContentLoaded', () => {
    const toggleBtn = document.querySelector('.toggle-sidebar');

    toggleBtn.addEventListener('click', () => {
        document.querySelector('.sidebar').classList.toggle('open');
    });
});