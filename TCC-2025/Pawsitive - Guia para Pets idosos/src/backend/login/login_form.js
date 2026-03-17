let card = document.querySelector(".card");
let loginBtn = document.querySelector(".loginBtn");
let cadastroBtn = document.querySelector(".cadastroBtn");

loginBtn.onclick = ()=> {
    card.classList.remove("cadastroActive");
    card.classList.add("loginActive");
}

cadastroBtn.onclick = ()=> {
    card.classList.remove("loginActive");
    card.classList.add("cadastroActive");
}