let text = document.getElementById('text');
let onda01 = document.getElementById('img02');
let onda02 = document.getElementById('img03');
let coral01 = document.getElementById('img01');


window.addEventListener('scroll', () => {
    let value = window.scrollY;

    text.style.marginTop = value * 2.5 + 'px';
    img03.style.left = value * 2.5 + 'px';
    img02.style.left = value * -2.5 + 'px';
    img01.style.top = value * 1 + 'px'; 
    
});