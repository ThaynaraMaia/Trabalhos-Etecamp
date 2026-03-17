const floatingImage = document.getElementById('floatingImage');

window.addEventListener('scroll', () => {
    const scrollY = window.scrollY;

    const maxMoveX = 70; 
    const maxMoveY = 80; 

    // Movimento proporcional ao scroll, limitado
    let moveX = scrollY * 0.2;
    let moveY = scrollY * 5;

    if (moveX > maxMoveX) moveX = maxMoveX;
    if (moveY > maxMoveY) moveY = maxMoveY;

    floatingImage.style.transform = `translate(-${moveX}px, ${moveY}px)`;
});
