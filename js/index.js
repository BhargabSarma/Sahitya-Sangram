window.addEventListener('load', () => {
    const preloader = document.getElementById('preloader');
    if (preloader) {
        preloader.style.opacity = '0';
        setTimeout(() => preloader.style.display = 'none', 400);
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const toggle = document.getElementById('search-toggle');
    const box = document.getElementById('search-box');
    document.addEventListener('click', function(e) {
        if (toggle.contains(e.target)) {
            box.classList.toggle('active');
            setTimeout(() => {
                if (box.classList.contains('active')) {
                    box.querySelector('input').focus();
                }
            }, 200);
        } else if (!box.contains(e.target)) {
            box.classList.remove('active');
        }
    });
});