window.onload = function () {

    /* Dropdown Language */    
    var dropdown = document.querySelector('.dropdown');
    dropdown.addEventListener('click', function (event) {
        event.stopPropagation();
        dropdown.classList.toggle('is-active');
    });

    /* Close button tips/quote */
    document.querySelector(".close-quote").addEventListener("click", function () {
        document.querySelector("#figure-header").classList.add("opacity-0");
    })

    /* Navbar transition when scroll */
    window.addEventListener('scroll', function(e) {
        if ( (window.scrollY) > (window.innerHeight - document.getElementsByClassName('navbar')[0].offsetHeight)) {
            console.log('Passer background en sombre');
            document.getElementsByClassName('navbar')[0].classList.add("scrolled");
        } else {
            document.getElementsByClassName('navbar')[0].classList.remove("scrolled");
        }
    })

}