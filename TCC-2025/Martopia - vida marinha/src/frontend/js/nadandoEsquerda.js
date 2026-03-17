

  const floatingImageE = document.getElementById('floatingImageE');

window.addEventListener('scroll', () => {
    const scrollY = window.scrollY;

    // Limites máximos para o movimento (em px)
    const maxMoveX = 100; // máximo 50px para esquerda
    const maxMoveY = 100; // máximo 30px para baixo


    let moveX = scrollY * 0.2;
    let moveY = scrollY * 5;

    if (moveX > maxMoveX) moveX = maxMoveX;
    if (moveY > maxMoveY) moveY = maxMoveY;

    floatingImageE.style.transform = `translate(${moveX}px, ${moveY}px)`;
});