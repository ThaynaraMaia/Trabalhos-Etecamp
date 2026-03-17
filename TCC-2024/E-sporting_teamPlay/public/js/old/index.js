// PHP auth
let logged = (document.querySelector('[auth]')).innerHTML == '1' ? true : false; 
const dlwdiv = document.querySelector('[dlw]');
const limiter = document.querySelector('[limiter]');
const placeholder = document.querySelector('[PH]');
const placeholderText = document.querySelector('[PHt]');
// logged = true; // Debug


const dlDisabled = 
`<div class="Btn download" style="opacity: 30%;">
<img src="../assets/download.png" class="image download">
<div class="light two"></div>
</div>
`;

const dlEnabled = 
`<a href="../assets/Manual_ParaGames.pdf" download="Manual da Empresa ParaGames">
<div class="Btn download">
    <img src="../assets/download.png" class="image download">
    <div class="light two"></div>
</div>
</a>
`;

const divlimiter = 
`<div class="blur"></div>
<div class="limit">
    Faça <a href="indexLogin.php">Login</a> para ler mais.</p>
</div>
`;

const phHrefLogin = `../html/indexLogin.php`;
const phHrefLogout = `../../backend/php/scripts/logout.php`;

if (!logged) {
    // Limit scrolling
    document.addEventListener('scroll', () => {
        if (window.scrollY > 300) {
            // console.log('scrolled'); // Debug
            scrollTo(0, 299);
        }
    });
    
    // Disable download
    dlwdiv.innerHTML = dlDisabled;

    // Add 'Read more' element
    limiter.innerHTML = divlimiter;

    // Set "Login" to "Logout"
    placeholder.setAttribute('href', phHrefLogin);
    placeholderText.innerHTML = "Login";
    placeholderText.style.left = "6vw";
} 
else {
    document.removeEventListener('scroll', () => {
        if (window.scrollY > 1400) {
            console.log('scrolled');
            scrollTo(0, 1399);
        }
    });
    dlwdiv.innerHTML = dlEnabled;
    limiter.innerHTML = ``;
    placeholder.setAttribute('href', phHrefLogout);
    placeholderText.innerHTML = "Logout";
    placeholderText.style.left = "5vw";
};



