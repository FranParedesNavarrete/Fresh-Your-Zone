import './bootstrap';

 // Funcion para que la ventana se refresque cada 10s
 function refreshWindow() {
    setTimeout(() => {
        location.reload();
    }, 10000);
}
//refreshWindow();

document.addEventListener('DOMContentLoaded', function() {
    changeDarkmodeClass();
});

function changeDarkmodeClass() {
    let darkmodeBtn = document.querySelector('.darkmodeBtn');
    let darkmodeIcon = document.getElementById('darkmodeIcon');

    let logoFZY = document.querySelector('.logo-fzy');

    if(localStorage.getItem('darkmode') === 'true') {
        document.body.classList.remove('lightmode');
        document.body.classList.add('darkmode');

        darkmodeIcon.classList.remove('bi-moon-fill');
        darkmodeIcon.classList.add('bi-sun-fill', 'text-dark');
        darkmodeBtn.style.backgroundColor = '#d4cdc5';

        logoFZY.src = '/assets/images/logo/fzy-logo-light.png';
    }else {
        document.body.classList.remove('darkmode');
        document.body.classList.add('lightmode');

        darkmodeIcon.classList.remove('bi-sun-fill', 'text-dark');
        darkmodeIcon.classList.add('bi-moon-fill');
        darkmodeBtn.style.backgroundColor = '#262525';

        logoFZY.src = '/assets/images/logo/fzy-logo-dark.png';
    }
}
function darkmodeTogle() {
    let currentMode = localStorage.getItem('darkmode');
    let newValue = (currentMode === 'true') ? 'false' : 'true';
    localStorage.setItem('darkmode', newValue);

    changeDarkmodeClass();
}
