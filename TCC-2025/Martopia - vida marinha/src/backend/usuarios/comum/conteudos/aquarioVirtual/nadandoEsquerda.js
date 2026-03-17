// Array de IDs das imagens da esquerda (adicione mais aqui)
const leftImages = ['floatingImageE1', 'floatingImageE2'];

leftImages.forEach(imageId => {
    const image = document.getElementById(imageId);
    if (!image) return;

    window.addEventListener('scroll', () => {
        const scrollY = window.scrollY;

        const startScroll = 300;
        const stopScroll = 500;
        const hideScroll = 600;

        const startX = 400;  // Começa à esquerda (positivo para left: -400px no CSS)
        const startY = 50;
        const endX = 200;    // Centro-esquerda
        const endY = 150;

        if (scrollY < startScroll) {
            image.style.transform = `translate(-${startX}px, ${startY}px)`; // Ajustado para esquerda
            image.style.opacity = 0;
        } else if (scrollY >= startScroll && scrollY <= stopScroll) {
            const progress = (scrollY - startScroll) / (stopScroll - startScroll);
            const moveX = startX * (1 - progress); // Move para centro
            const moveY = startY + (endY - startY) * progress;
            image.style.transform = `translate(-${moveX}px, ${moveY}px)`;
            image.style.opacity = progress;
        } else if (scrollY > stopScroll && scrollY <= hideScroll) {
            image.style.transform = `translate(-${endX}px, ${endY}px)`;
            image.style.opacity = 1;
        } else if (scrollY > hideScroll) {
            const fade = Math.max(0, 1 - (scrollY - hideScroll) / 200);
            image.style.opacity = fade;
        }
    });
});
