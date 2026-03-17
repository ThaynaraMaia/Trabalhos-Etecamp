// Filters 
const filters = document.getElementsByClassName('sideicon');
const info = document.getElementsByClassName('ad')[0];
const orderBySel = document.getElementById('menu1');

let orderBy = document.getElementById('order').innerHTML;
const selGame = document.getElementById('sel').innerHTML;

const data = [
    [
        '../assets/Logo_CorBW.png',
        'TeamPlay',
        'TeamPlay é uma rede social focada para o público gamer, especialmente competitivo, onde há uma facilidade em encontrar colegas de equipe e torneios amadores, explore os jogos suportados pela TeamPlay, onde campeões ascendem!.'
    ],

    [
        '../assets/sideicons/cod_warzone_logo.png',
        'Call of Duty: Warzone',
        'Call of Duty: Warzone é um jogo eletrônico free-to-play do gênero battle royale desenvolvido pela Infinity Ward e Raven Software e publicado pela Activision.'
    ],

    [
        '../assets/sideicons/overwatch2_logo.png',
        'Overwatch 2',
        'Overwatch 2 é um jogo eletrônico multijogador de tiro em primeira pessoa publicado e distribuído pela Blizzard Entertainment. A Blizzard Entertainment anunciou Overwatch 2 durante a BlizzCon 2019..'
    ],

    [
        '../assets/sideicons/valorant_logo.png',
        'Valorant',
        'Valorant é um jogo eletrônico multijogador gratuito para jogar de tiro em primeira pessoa desenvolvido e publicado pela Riot Games..'
    ],

    [
        '../assets/sideicons/fortnite_logo.png',
        'Fortnite',
        'Fortnite é um jogo eletrônico multijogador online revelado originalmente em 2011, desenvolvido pela Epic Games e lançado como diferentes modos de jogo que compartilham a mesma jogabilidade e motor gráfico de jogo.'
    ],

    [
        '../assets/sideicons/lol_logo.png',
        'League of Legends',
        'League of Legends é um jogo eletrônico do gênero multiplayer online battle arena desenvolvido e publicado pela Riot Games. Foi lançado em outubro de 2009 para Microsoft Windows e em março de 2013 para macOS.'
    ],

    [
        '../assets/sideicons/eafc24_logo.png',
        'EAFC24',
        'EA Sports FC 24 é um videojogo de futebol desenvolvido pela EA Canadá e EA Roménia, e publicado pela EA Sports. Este jogo marca o início da série EA Sports FC após a conclusão da parceria da EA com a FIFA, sendo o 31º título lançado da franquia ao todo.'
    ]
]

// let lastFilter = 1;
let currentFilter = parseInt(selGame);
console.log(currentFilter);

// currentFilter = currentFilter == '\n' ? 0 : currentFilter;
orderBy = orderBy.split('\n')[1];


if (orderBy != '') {
    if (orderBy != '\n') {
        orderBySel.value = orderBy;
        console.log('Sel');
    } else {
        orderBySel.value = 'byDate';
        console.log('Sel2');
    }
}


if (currentFilter > 0) {
    filters[currentFilter+1].setAttribute('class', `sideicon j${currentFilter} filtered`);
    
    let img = data[currentFilter][0]
    let title = data[currentFilter][1]
    let p = data[currentFilter][2]
    
    info.innerHTML = `
    <img src="${img}">
    <h1>${title}</h1>
    <p>${p}</p>
    `;
} else {
    filters[1].setAttribute('class', `sideicon jc filtered`) ;
}


let url = window.location.href;
url = window.location.href.split(0,
    window.location.href[window.location.href.indexOf('php') + 3]);
    
    




console.log(window.location.href);
console.log(url);
// console.log(window.location.href);
orderBySel.addEventListener('change', () => {

    window.location.href = url + "?order=" + orderBySel.value;
    
})


function showHide(elm) {
    if (elm.children[0].children[1].children[3].style.webkitLineClamp == 'unset') {
        elm.children[0].children[1].children[3].style.webkitLineClamp = '3';
        elm.children[0].children[1].children[3].setAttribute('title', 'Clique para expandir');
    } else {
        elm.children[0].children[1].children[3].style.webkitLineClamp = 'unset';
        elm.children[0].children[1].children[3].setAttribute('title', 'Clique para retrair');
    }
    console.log(elm.children[0].children[1].children[3].style.webkitLineClamp);
    console.log(elm.children[0].children[1].children[3].attributes[0]);
}

// console.log(filters[8]);
// filters[lastFilter].setAttribute('class', `sideicon j${lastFilter} filtered`)
// console.log(parseInt(selGame));


// filters[i].setAttribute('class', `sideicon j${i-1} filtered`) 


// for (let i = 1; i < filters.length; i++) {
    
    // filters[i].addEventListener('click', (e) => {
        // lastFilter = currentFilter;
        // console.log(`Before:\nC: ${currentFilter}\nL: ${lastFilter}`);
        // console.log(e)
        
        // e.target.setAttribute('class', `sideicon j${i-1} filtered`) 

        // currentFilter = selGame == '\n' ? i : parseInt(selGame);
        // currentFilter = parseInt(selGame);

        // filters[i].setAttribute('class', `sideicon j${i} filtered`) 
        
        
        // filters[lastFilter].setAttribute('class', `sideicon j${lastFilter}`) 
        // if (currentFilter == lastFilter) {
        //     currentFilter = 1;
        //     filters[1].setAttribute('class', `sideicon j1 filtered`) 
        // } 



    // })
    
// }

