function createBubble(containerId) {
  const container = document.getElementById(containerId);
  const maxBubbles = 30; 

  if (container.children.length >= maxBubbles) {
    return; 
  }

  const bubble = document.createElement("div");
  bubble.classList.add("bubble");
  const size = Math.random() * 30 + 10 + "px";
  bubble.style.width = size;
  bubble.style.height = size;
  bubble.style.left = Math.random() * window.innerWidth + "px";
  bubble.style.bottom = "-" + size;
  bubble.style.animationDuration = Math.random() * 10 + 5 + "s";

  container.appendChild(bubble);

  setTimeout(() => {
    bubble.remove();
  }, 15000);
}

setInterval(() => {
  createBubble('ocean-bottom');
}, 300);