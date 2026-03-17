// Tour 
const allTournsDiv = document.getElementById('alltourns');
const allTournsGroup = allTournsDiv.innerHTML.split('<br class="ignore">');
const allTournsIndv = [] 
    allTournsGroup.forEach(trn => {
        allTournsIndv.push(trn.split("<br>"))
    });

const indvTourn = document.getElementsByName('tour');
const infoDiv = document.getElementById('info');
const myId = parseInt(document.getElementById('myid').innerHTML);
const selTour = document.getElementById('selection').innerHTML;
let currScore = '';
let winner = '';
let statusColor = '';
let editBtn = '';
let typeTour = '';
let nPlayers = [0, 0];

/* 0 - id; ?><br>                  CHECK
1 - title; ?><br>                  CHECK
2 - type; ?><br>                   CHECK
3 - organizer; ?><br>              CHECK
4 - description; ?><br>            CHECK
5 - date_start; ?><br>             CHECK
6 - date_end; ?><br>               
7 - date_creation; ?><br>          CHECK
8 - current_score; ?><br>          CHECK
9 - status; ?><br>                 CHECK
10 - players; ?><br>               CHECK
11 - picture; ?><br>               
12 - game; ?><br>                  CHECK
13 - region; ?>                    CHECK
14 - winnder; ?>                   CHECK
15 - username; ?> ignore">         CHECK
*/

const gameList = [
    '-',
    'Call of Duty: Warzone',
    'Overwatch 2',
    'Valorant',
    'Fortnite',
    'League of Legens',
    'EAFC24'
]

for (let i = 0; i < indvTourn.length; i++) {

    nPlayers = allTournsIndv[i][10].split(',')
    if (nPlayers[0] == undefined || nPlayers[1] == undefined){
        nPlayers = ['-', '-'];
    }

    if (parseInt(allTournsIndv[i][2]) == 0) { // Tipo, casual
        typeTour = 'Casual'
        if (allTournsIndv[i][11] == '\n') {
            allTournsIndv[i][11] = `../assets/icons/tour_casual.png`;
        } else {
            false;
        }


    } else if (parseInt(allTournsIndv[i][2]) == 1) { // Oficial
        typeTour = 'Oficial'
        if (allTournsIndv[i][11] == '\n') {
            allTournsIndv[i][11] = `../assets/icons/tour_official.png`;
        } else {
            false;
        }
    } else {
        typeTour = 'Sem classificação.'
        if (allTournsIndv[i][11] == '\n') {
            allTournsIndv[i][11] = `../assets/icons/tour_casual.png`;
        } else {
            false;
        }

    }

    if (allTournsIndv[i][14] == '\n') {
        allTournsIndv[i][14] = 'Não declarado.';
    }
        
    // console.log(allTournsIndv[i][1] + " Tipo: " + allTournsIndv[i][2]);
    
    // console.log(allTournsIndv[i][1] + " Placar: " + allTournsIndv[i][8]);


    if (allTournsIndv[i][8] == '\n') {
        allTournsIndv[i][8] = 'Sem Placar';
    } else {
        false
    }


    
    // console.log(allTournsIndv[i][1] + " Status: " + allTournsIndv[i][9]); 


    

    if (parseInt(allTournsIndv[i][3]) == myId) { // Id
        editBtn = `<a href="editTour.php?id=${allTournsIndv[i][0]}">
        <div class="inp btn config"><strong style="font-size: x-large;">Editar</strong></div></a>`;
    } else {
        editBtn = '';
    }
    // console.log('Id: ' + allTournsIndv[i][3]);
    // console.log('My Id: ' + myId);
    



    if (parseInt(allTournsIndv[i][9]) == 0) { 
        winner = '';
        statusColor = 'rgb(231, 217, 24)';
        // console.log(allTournsIndv[i][1] + " Status: Preparação");
        currScore = '';

    } else if (parseInt(allTournsIndv[i][9]) == 1) { 
        winner = '';
        currScore = `<p style="background-color: var(--shade2-a); width: max-content; margin: -.8vh"><strong>Placar: </strong>${allTournsIndv[i][8]}</p></h1>`;
        // console.log(allTournsIndv[i][1] + " Status: Andamento");
        statusColor = 'rgb(0, 255, 153)'; 

    } else if (parseInt(allTournsIndv[i][9]) == 2) { 
        winner = `<li><strong>Vencedor: </strong> ${allTournsIndv[i][14]}</li></ul>`;
        currScore = `<p style="background-color: var(--shade2-a); width: max-content; margin: -.8vh"><strong>Placar: </strong>${allTournsIndv[i][8]}</p></h1>`;
        // console.log(allTournsIndv[i][1] + " Status: Finalizado");
        winner = `<li><strong>Vencedor: </strong> ${allTournsIndv[i][14]}</li>`;
        statusColor = 'var(--mag)';

    } else {
        winner = '';
        currScore = '';
    }


    let newInfo = `
    <div style="display: flex; flex-direction: row; align-items: flex-start; gap: 1vw; justify-content: space-between; width: 100%">
    <div style="display: flex; flex-direction: column">

        <div style="display: flex; flex-direction: row; align-items: center; gap: .4vw">
        <h1 style="min-width: 4vw; max-width: 20vw; overflow-wrap: break-word; padding-left: 1vw; padding-right: 1vw;">${allTournsIndv[i][1]}
        </h1>
            
        <br>
        <div class="box" style="width: 4vw; height: 4vw;">
        <div class="tourstatus" style="width: 2vw; height: 2vw; background: ${statusColor}"></div></div>
        </div>

            <div style="display: flex; flex-direction: row; gap: 1vw; margin-left: .1vw">
                <p style="background-color: var(--shade1); width: max-content;"><strong>Organizador: <span class="at">@ </span><a href="user.php?uid=${allTournsIndv[i][3]}">${allTournsIndv[i][15]}</a></strong></p></h1>
                <p style="background-color: var(--shade1); width: max-content;"><strong>Jogo: ${gameList[parseInt(allTournsIndv[i][12])]}</a></strong></p></h1>
            </div>
            
            
        </div>
        ${editBtn}
    </div>

    <div class="divi" style="margin-bottom: 1vh;"></div>
    ${currScore}
    <div class="infomini">
        <ul>
            <li><strong>Região: </strong>${allTournsIndv[i][13]}</li>
            <li><strong>Tipo: </strong>${typeTour}</li>
            <li><strong>Data de Início: </strong> ${allTournsIndv[i][5]}</li>
            <li><strong>N° Jogadores: </strong>${nPlayers[0]}/${nPlayers[1]}</li>
            <li><strong>Data de Finalização: </strong> ${allTournsIndv[i][6]}</li>
            <li><strong>Data de Criação: </strong> ${allTournsIndv[i][7]}</li>
            ${winner}
        </ul>
    </div>   

    <div class="divi"></div>
    
    <div class="lowerinfo">
        <div>
            <img src="${allTournsIndv[i][11]}" alt="Imagem do Torneio">
        </div>
        <h1 style="width: 26vw; overflow-wrap: break-word; padding-left: 1vw; padding-right: 1vw;">Sobre o Torneio 
        </h1>
        <p style="text-align: justify;">${allTournsIndv[i][4]}
    </div>
    `;



    indvTourn[i].addEventListener('click', (e) => {
        infoDiv.innerHTML = newInfo;
    })
    
}

