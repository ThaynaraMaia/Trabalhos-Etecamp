// Array de IDs das imagens da direita
const rightImages = ['floatingImage1', 'floatingImage2'];

rightImages.forEach(imageId => {
    const image = document.getElementById(imageId);
    if (!image) return;

    window.addEventListener('scroll', () => {
        const scrollY = window.scrollY;

        // Parâmetros: anima mais cedo e vai para o centro
        const startScroll = 100; // Começa animação mais cedo
        const stopScroll = 300;  // Para no centro
        const hideScroll = 500;  // Fade out depois

        // Movimento: de canto direito (translateX 0) para centro (translateX -50% da tela + ajuste pela largura da img)
        const screenWidth = window.innerWidth;
        const imageWidth = 220; // Largura da imagem
        const startX = 0; // Começa no canto (right:0)
        const endX = -(screenWidth / 2 - imageWidth / 2); // Move para centro da tela
        const startY = 0; // Sem movimento vertical inicial
        const endY = 0;   // Mantém Y fixo (só horizontal)

        if (scrollY < startScroll) {
            // Antes: invisível no canto
            image.style.transform = `translate(${startX}px, ${startY}px)`;
            image.style.opacity = 0;
        } else if (scrollY >= startScroll && scrollY <= stopScroll) {
            // Anima para centro + opacidade
            const progress = (scrollY - startScroll) / (stopScroll - startScroll);
            const moveX = startX + (endX - startX) * progress;
            const moveY = startY + (endY - startY) * progress;
            image.style.transform = `translate(${moveX}px, ${moveY}px)`;
            image.style.opacity = Math.min(progress, 1);
        } else if (scrollY > stopScroll && scrollY <= hideScroll) {
            // Parada no centro, visível
            image.style.transform = `translate(${endX}px, ${endY}px)`;
            image.style.opacity = 1;
        } else if (scrollY > hideScroll) {
            // Fade out gradual
            const fade = Math.max(0, 1 - (scrollY - hideScroll) / 200);
            image.style.opacity = fade;
            image.style.transform = `translate(${endX}px, ${endY}px)`; // Mantém posição
        }
    });
});
