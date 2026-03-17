window.efeitoScroll = ScrollReveal({reset:false}) // reset false para rodar só uma vez

// TITULOS DO SITE
efeitoScroll.reveal('.titulo', {
    duration: 2000,
    distance: '90px'
})

// CARDS
efeitoScroll.reveal('.efeito-cards1', {
    duration: 2000,
    distance: '90px', 
    delay: 300
})
efeitoScroll.reveal('.efeito-cards2', {
    duration: 2000,
    distance: '90px', 
    delay: 700
})
efeitoScroll.reveal('.efeito-cards3', {
    duration: 2000,
    distance: '90px', 
    delay: 1300
})

// BIOLOGOS
efeitoScroll.reveal('.efeito-imagem', {
    duration: 2000,
    distance: '90px', 
    delay: 300, 
    origin: 'right'
})

efeitoScroll.reveal('.efeito-texto', {
    duration: 2000,
    distance: '90px', 
    delay: 700, 
    origin: 'left'
})
