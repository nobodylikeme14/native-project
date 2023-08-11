window.onload = function() {
    //AOS Init
    AOS.init({
        duration: 800,
        once: true
    });
    //Scroll to Top
    window.addEventListener('scroll', function() {
        if (window.scrollY > 100) {
            document.querySelector('.scroll-to-top').style.display = 'block';
        } else {
            document.querySelector('.scroll-to-top').style.display = 'none';
        }
    });
    document.querySelector('.scroll-to-top').addEventListener('click', function() {
        var scrollOptions = {
            top: 0,
            behavior: 'smooth'
        };
        window.scrollTo(scrollOptions);
        return false;
    });
    //Remove AOS delay on mobile
    if (window.innerWidth <= 576) {
        var elements = document.querySelectorAll('[data-aos-delay]');
        for (var i = 0; i < elements.length; i++) {
            elements[i].removeAttribute('data-aos-delay');
        }
    }
}