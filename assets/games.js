const playGifDiv = document.createElement('div');
playGifDiv.id = 'play-gif';
const playGif = document.createElement('img');
playGif.src = '/picture/play.gif';
playGifDiv.appendChild(playGif);

window.addEventListener('DOMContentLoaded', () => {
    const playGameBtns = document.querySelectorAll('.play-game');

    for (playGameBtn of playGameBtns) {
        playGameBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const link = this.href;
            document.querySelector('table').insertAdjacentElement('afterend', playGifDiv);
            playGifDiv.classList.add('active');
            setTimeout(function() {
                window.location.href = link;
            }, 2500);
        })
    }
});