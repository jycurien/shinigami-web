const parallax= document.querySelector(".parallax");

window.addEventListener("scroll", function() {

    const scrolledHeight = window.pageYOffset,
        limit = parallax.offsetTop + parallax.offsetHeight;

    if(scrolledHeight > parallax.offsetTop && scrolledHeight <= limit) {
        parallax.style.backgroundPositionY =  (scrolledHeight - parallax.offsetTop) /1.5 + "px";

    } else {
        parallax.style.backgroundPositionY =  "0";
    }
});