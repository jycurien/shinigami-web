document.addEventListener('DOMContentLoaded', () => {
    const counters = document.querySelectorAll('.counter-count');

    counters.forEach( counter => {
        const animate = () => {
            const value = +counter.dataset.count;
            const data = +counter.innerText;

            const time = value / 10;
            if (data < value) {
                counter.innerText = Math.ceil(data + time);
                setTimeout(animate, 50);
            } else {
                counter.innerText = value;
            }
        };

        animate();
    });
});