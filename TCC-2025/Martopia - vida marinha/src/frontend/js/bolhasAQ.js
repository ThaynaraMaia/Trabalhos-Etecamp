function createBubbles() {
    const bubblesContainer = document.getElementById('bubbles');
    const bubbleCount = 20; 

    for (let i = 0; i < bubbleCount; i++) {
        const bubble = document.createElement('div');
        bubble.className = 'bubble';

        const size = Math.random() * 60 + 10;
        bubble.style.width = `${size}px`;
        bubble.style.height = `${size}px`;

        bubble.style.left = `${Math.random() * 100}%`;

        const duration = Math.random() * 12 + 8;
        bubble.style.animationDuration = `${duration}s`;

        bubble.style.animationDelay = `${Math.random() * 5}s`;

        bubblesContainer.appendChild(bubble);
    }
}


window.addEventListener('load', createBubbles);

setInterval(() => {
    const bubblesContainer = document.getElementById('bubbles');
    bubblesContainer.innerHTML = '';
    createBubbles();
}, 1200000); 