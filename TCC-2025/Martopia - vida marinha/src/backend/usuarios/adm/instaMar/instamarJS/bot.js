const fabBtn = document.getElementById("bot-btn");
const fabOptions = document.querySelector(".bot-options");

fabBtn.addEventListener("click", () => {
    fabOptions.classList.toggle("show");
    fabBtn.classList.toggle("open");

    if (fabBtn.classList.contains("open")) {
        fabBtn.style.transform = "rotate(45deg)";
    } else {
        fabBtn.style.transform = "rotate(0deg)";
    }
});