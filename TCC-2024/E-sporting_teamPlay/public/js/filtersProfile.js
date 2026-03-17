/* Games:
1 All 
2 COD
3 OW2
4 VAL
5 FOR
6 LOL 
7 FIF
*/

let tagDiv = [
`
    <div class="add-tag"><strong>`,

`
    </strong>`,

`
    </div>`
]


const Games = [
    {   
        currNum: 1,
        name: 'All',
        gameDiv: `
            <div class="post off">
            Nenhum jogo selecionado.
            </div>`,
        gameOptions: `
            <div style="opacity: 40%;"><br>
                <img src="../assets/arrow.png" style="transform: rotate(270deg); width: 5vw;" alt="Seta">
                <br><br><strong>Selecione um jogo da toolbar para customizá-lo para o seu perfil.</strong><br><br>
            <div>
        `
    },

    {   
        currNum: 2,
        name: 'Call of Duty: Warzone',
        gameDiv: `
            <div class="post">
                <img src="../assets/sideicons/cod_warzone_logo.png" alt="Call of Duty: Warzone">
                <div class="games"><strong style="color: var(--mag)">Sem informações atribuídas.</strong>
            </div></div>`,
        gameOptions: `
            <h1>Call of Duty: Warzone</h1>

                <label for="inputText" required="true"><strong>Nickname</strong></label>
                    <input type="text" id="inputText" name="inputText" required="true">
                <br><br>


                <label for="menu3"><strong>Rank</strong></label>
                <select id="menu1" name="menu1" required="true">
                    <option value="N/A">N/A</option>    
                    <option value="Bronze">Bronze</option>
                    <option value="Prata">Prata</option>
                    <option value="Ouro">Ouro</option>
                    <option value="Platina">Platina</option>
                    <option value="Diamante">Diamante</option>
                    <option value="Crimson">Crimson</option>
                    <option value="Iridescente">Iridescente</option>
                    <option value="Top 250">Top 250</option>

                </select>
                <br><br>
                <br>

                <button class="toolbutton" id="update" onclick="send()"><h1>Atualizar</h1></button>
            </div>
        `
    },

    {   
        currNum: 3,
        name: 'Overwatch 2',
        gameDiv: `
            <div class="post">
            <img src="../assets/sideicons/overwatch2_logo.png" alt="Overwatch 2">
            <div class="games"><strong style="color: var(--mag)">Sem informações atribuídas.</strong>
            </div></div>`,
        gameOptions: `
            <h1>Overwatch 2</h1>
                <label for="inputText"><strong>Nickname</strong></label>
                <input type="text" id="inputText" name="inputText" required="true">
                <br><br>

                <label for="menu1"><strong>Rank</strong></label>
                <select id="menu1" name="menu1" required="true">
                    <option value="N/A">N/A</option>
                    <option value="Bronze">Bronze</option>
                    <option value="Prata">Prata</option>
                    <option value="Ouro">Ouro</option>
                    <option value="Platina">Platina</option>
                    <option value="Diamante">Diamante</option>
                    <option value="Mestre">Mestre</option>
                    <option value="Grão-Mestre">Grão-Mestre</option>
                    <option value="Top 500">Top 500</option>
                </select>
                <br><br>

                <label><strong>Posição</strong></label><br><br>
                <input type="checkbox" id="check1" name="checkGroup" value="Tanque">
                <label for="check1">Tanque</label>
                <br>
                <input type="checkbox" id="check2" name="checkGroup" value="Dano">
                <label for="check2">Dano</label>
                <br>
                <input type="checkbox" id="check3" name="checkGroup" value="Suporte">
                <label for="check3">Suporte</label>
                <br><br>
                <br>

                <button class="toolbutton" id="update" onclick="send()"><h1>Atualizar</h1></button>
            </div>
        `
    },

    {   
        currNum: 4,
        name: 'Valorant',
        gameDiv: `
            <div class="post">
            <img src="../assets/sideicons/valorant_logo.png" alt="Valorant">
            <div class="games"><strong style="color: var(--mag)">Sem informações atribuídas.</strong>
            </div></div>`,
        gameOptions: `
        <h1>Valorant</h1>

                <label for="inputText"><strong>Nickname</strong></label>
                <input type="text" id="inputText" name="inputText" required="true">
                <br><br>


                <label for="menu1"><strong>Rank</strong></label>
                <select id="menu1" name="menu1" required="true">
                    <option value="N/A">N/A</option>
                    <option value="Ferro">Ferro</option>
                    <option value="Bronze">Bronze</option>
                    <option value="Prata">Prata</option>
                    <option value="Ouro">Ouro</option>
                    <option value="Platina">Platina</option>
                    <option value="Diamante">Diamante</option>
                    <option value="Ascendente">Ascendente</option>
                    <option value="Imortal">Imortal</option>
                    <option value="Radiante">Radiante</option>
                </select>
                <br><br>

                <label><strong>Funções</strong></label><br><br>
                <input type="checkbox" id="check1" name="checkGroup" value="Controller">
                <label for="check1">Controller</label>
                <br>
                <input type="checkbox" id="check2" name="checkGroup" value="Duelist">
                <label for="check2">Duelist</label>
                <br>
                <input type="checkbox" id="check3" name="checkGroup" value="Initiator">
                <label for="check3">Initiator</label>
                <br>
                <input type="checkbox" id="check4" name="checkGroup" value="Sentinel">
                <label for="check4">Sentinel</label>
                <br><br>
                <br>


                <button class="toolbutton" id="update" onclick="send()"><h1>Atualizar</h1></button>
            </div>
        `
    },

    {   
        currNum: 5,
        name: 'Fortnite',
        gameDiv: `
            <div class="post">
            <img src="../assets/sideicons/fortnite_logo.png" alt="Fortnite">
            <div class="games"><strong style="color: var(--mag)">Sem informações atribuídas.</strong>
            </div>
            </div>`,
        gameOptions: `
            <h1>Fortnite</h1>

            <label for="inputText"><strong>Nickname</strong></label>
                <input type="text" id="inputText" name="inputText" required="true">
            <br><br>

            <label for="menu1"><strong>Rank</strong></label>
            <select id="menu1" name="menu1" required="true">
                <option value="N/A">N/A</option>
                <option value="Bronze">Bronze</option>
                <option value="Prata">Prata</option>
                <option value="Ouro">Ouro</option>
                <option value="Platina">Platina</option>
                <option value="Diamante">Diamante</option>
                <option value="Elite">Elite</option>
                <option value="Lenda">Lenda</option>
                <option value="Surreal">Surreal</option>
            </select>
            <br><br> 

            <label><strong>Papel</strong></label><br><br>
                <input type="checkbox" id="check1" name="checkGroup" value="IGL">
                <label for="check1">IGL</label>
                <br>
                <input type="checkbox" id="check2" name="checkGroup" value="Fragger">
                <label for="check2">Fragger</label>
                <br>
                <input type="checkbox" id="check3" name="checkGroup" value="Support">
                <label for="check3">Support</label>
                <br><br>
                <br>
                <button class="toolbutton" id="pghome" onclick="send()"><h1>Atualizar</h1></button>
            </div>
        `
    },

    {   
        currNum: 6,
        name: 'League of Legends',
        gameDiv: `
            <div class="post">
            <img src="../assets/sideicons/lol_logo.png" alt="League of Legends">
            <div class="games"><strong style="color: var(--mag)">Sem informações atribuídas.</strong>
            </div></div>`,
        gameOptions: `
            <h1>League of Legends</h1>

                <label for="inputText"><strong>Nickname</strong></label>
                <input type="text" id="inputText" name="inputText" required="true">
                <br><br>


                <label for="menu1"><strong>Rank</strong></label>
                <select id="menu1" name="menu1" required="true">
                    <option value="N/A">N/A</option>
                    <option value="Ferro">Ferro</option>
                    <option value="Bronze">Bronze</option>
                    <option value="Prata">Prata</option>
                    <option value="Ouro">Ouro</option>
                    <option value="Platina">Platina</option>
                    <option value="Esmeralda">Esmeralda</option>
                    <option value="Diamante">Diamante</option>
                    <option value="Mestre">Mestre</option>
                    <option value="Grão-Mestre">Grão-Mestre</option>
                    <option value="Challenger">Challenger</option>
                </select>
                <br><br> 


                <label><strong>Papel</strong></label><br><br>
                    <input type="checkbox" id="check1" name="checkGroup" value="Top">
                    <label for="check1">Top</label>
                    <br>
                    <input type="checkbox" id="check2" name="checkGroup" value="Jungle">
                    <label for="check2">Jungle</label>
                    <br>
                    <input type="checkbox" id="check3" name="checkGroup" value="Mid">
                    <label for="check2">Mid</label>
                    <br>
                    <input type="checkbox" id="check4" name="checkGroup" value="ADC">
                    <label for="check2">ADC</label>
                    <br>
                    <input type="checkbox" id="check5" name="checkGroup" value="Suporte">
                    <label for="check3">Suporte</label>
                    <br><br>
                    <br>


                <button class="toolbutton" id="update" onclick="send()"><h1>Atualizar</h1></button>
            </div>
        `
    },

    {   
        currNum: 7,
        name: 'EA FC24',
        gameDiv: `
            <div class="post">
            <img src="../assets/sideicons/eafc24_logo.png" alt="EA FC24">
            <div class="games"><strong style="color: var(--mag)">Sem informações atribuídas.</strong>
            </div></div>`,
        gameOptions: `
            <h1>EA FC24</h1>
            <label for="inputText"><strong>Nickname</strong></label>
            <input type="text" id="inputText" name="inputText" required="true">
            <br><br>


                <label for="menu1"><strong>Divisão</strong></label>
                <select id="menu1" name="menu1" required="true">
                    <option value="Divisão 1">Divisão 1</option>
                    <option value="Divisão 2">Divisão 2</option>
                    <option value="Divisão 3">Divisão 3</option>
                    <option value="Divisão 4">Divisão 4</option>
                    <option value="Divisão 5">Divisão 5</option>
                    <option value="Divisão 6">Divisão 6</option>
                    <option value="Divisão 7">Divisão 7</option>
                    <option value="Divisão 8">Divisão 8</option>
                    <option value="Divisão 9">Divisão 9</option>
                    <option value="Divisão 10">Divisão 10</option>
                </select>
                <br><br> 

                <label><strong>Posições</strong></label><br><br>
                    <input type="checkbox" id="check1" name="checkGroup" value="GK">
                    <label for="check1">Goleiro (GK)</label>
                    <br>
                    <input type="checkbox" id="check2" name="checkGroup" value="CB">
                    <label for="check2">Zagueiro Central (CB)</label>
                    <br>
                    <input type="checkbox" id="check3" name="checkGroup" value="RB">
                    <label for="check2">Lateral-Direito (RB)</label>
                    <br>
                    <input type="checkbox" id="check4" name="checkGroup" value="LB">
                    <label for="check2">Lateral-Esquerdo (LB)</label>
                    <br>
                    <input type="checkbox" id="check5" name="checkGroup" value="CDM">
                    <label for="check3">Volante (CDM)</label>
                    <br>
                    <input type="checkbox" id="check6" name="checkGroup" value="CM">
                    <label for="check3">Meio-Campo Central (CM)</label>
                    <br>
                    <input type="checkbox" id="check7" name="checkGroup" value="CAM">
                    <label for="check3">Meio-Campo Ofensivo (CAM)</label>
                    <br>
                    <input type="checkbox" id="check8" name="checkGroup" value="RM/RW">
                    <label for="check3">Ponta-Direita (RM/RW)</label>
                    <br>
                    <input type="checkbox" id="check9" name="checkGroup" value="LM/LW">
                    <label for="check3">Ponta-Esquerda (LM/LW)</label>
                    <br>
                    <input type="checkbox" id="check10" name="checkGroup" value="CF">
                    <label for="check3">Segundo Atacante (CF)</label>
                    <br>
                    <input type="checkbox" id="check11" name="checkGroup" value="ST">
                    <label for="check3">Centroavante (ST)</label>
                    
                    <br>
                    <br>

            <button class="toolbutton" id="update" onclick="send()"><h1>Atualizar</h1></button>
        </div>
        `
    }]


let saveForm = document.getElementById("save");
let phpGames = document.getElementById("holder");
let favGame = Number(document.getElementById("fav").innerHTML) + 1;


function send() {
    console.log("Actual current:", currentFilter);
    
    switch (currentFilter) {
        case 2:
            console.log('- CODW');
            console.log('Nickname:', document.getElementById('inputText').value); // Nick
            console.log('Rank:', document.getElementById('menu1').value); // Rank
            break;

        case 3:
            console.log('- Overwatch');
            console.log('Nickname:', document.getElementById('inputText').value); // Nick
            console.log('Rank:', document.getElementById('menu1').value); // Rank
            console.log('Checkbox 1:', document.getElementById('check1').checked ? document.getElementById('check1').value : false);
            console.log('Checkbox 2:', document.getElementById('check2').checked ? document.getElementById('check2').value : false);
            console.log('Checkbox 3:', document.getElementById('check3').checked ? document.getElementById('check3').value : false);
            break;

        case 4:
            console.log('- Valorant');
            console.log('Nickname:', document.getElementById('inputText').value); // Nick
            console.log('Rank:', document.getElementById('menu1').value); // Rank
            console.log('Checkbox 1:', document.getElementById('check1').checked ? document.getElementById('check1').value : false);
            console.log('Checkbox 2:', document.getElementById('check2').checked ? document.getElementById('check2').value : false);
            console.log('Checkbox 3:', document.getElementById('check3').checked ? document.getElementById('check3').value : false);
            console.log('Checkbox 4:', document.getElementById('check4').checked ? document.getElementById('check4').value : false);
            break;

        case 5: 
            console.log('- Fortnite');
            console.log('Nickname:', document.getElementById('inputText').value); // Nick
            console.log('Rank:', document.getElementById('menu1').value); // Rank
            console.log('Checkbox 1:', document.getElementById('check1').checked ? document.getElementById('check1').value : false);
            console.log('Checkbox 2:', document.getElementById('check2').checked ? document.getElementById('check2').value : false);
            console.log('Checkbox 3:', document.getElementById('check3').checked ? document.getElementById('check3').value : false);
            break;


        case 6: 
            console.log('- League of Legends');
            console.log('Nickname:', document.getElementById('inputText').value); // Nick
            console.log('Rank:', document.getElementById('menu1').value); // Rank
            console.log('Checkbox 1:', document.getElementById('check1').checked ? document.getElementById('check1').value : false);
            console.log('Checkbox 2:', document.getElementById('check2').checked ? document.getElementById('check2').value : false);
            console.log('Checkbox 3:', document.getElementById('check3').checked ? document.getElementById('check3').value : false);
            console.log('Checkbox 4:', document.getElementById('check4').checked ? document.getElementById('check4').value : false);
            console.log('Checkbox 5:', document.getElementById('check5').checked ? document.getElementById('check5').value : false);
            break;

        case 7: 
            console.log('- EA FC24');
            console.log('Nickname:', document.getElementById('inputText').value); // Nick
            console.log('Checkbox 1:', document.getElementById('check1').checked ? document.getElementById('check1').value : false);
            console.log('Checkbox 2:', document.getElementById('check2').checked ? document.getElementById('check2').value : false);
            console.log('Checkbox 3:', document.getElementById('check3').checked ? document.getElementById('check3').value : false);
            console.log('Checkbox 4:', document.getElementById('check4').checked ? document.getElementById('check4').value : false);
            console.log('Checkbox 5:', document.getElementById('check5').checked ? document.getElementById('check5').value : false);
            console.log('Checkbox 6:', document.getElementById('check6').checked ? document.getElementById('check6').value : false);
            console.log('Checkbox 7:', document.getElementById('check7').checked ? document.getElementById('check7').value : false);
            console.log('Checkbox 8:', document.getElementById('check8').checked ? document.getElementById('check8').value : false);
            console.log('Checkbox 9:', document.getElementById('check9').checked ? document.getElementById('check9').value : false);
            console.log('Checkbox 10:', document.getElementById('check10').checked ? document.getElementById('check10').value : false);
            break;

        default:
            console.log('Defaulted'); break;
    }
    addToList(currentFilter);
    
    let gamesDiv = document.getElementsByClassName('games');
    gamesDiv[0].innerHTML = ""; // Clear
    
    updateTags(currentFilter);
    saveForm.setAttribute("action", `../../backend/php/scripts/sendGame.php?Games=${JSON.stringify(myGames)}`) // Atualiza link
}

function resetGame() {
    let gamesDiv = document.getElementsByClassName('games');
    gamesDiv[0].innerHTML = ""; // Clear
    myGames = [];
    updateTags(currentFilter);
    saveForm.setAttribute("action", `../../backend/php/scripts/sendGame.php?Games=[]`) // Atualiza link
    console.log(myGames);
    
}


// Filters 
const filters = document.getElementsByClassName('sideicon');
let lastFilter = 1;
let currentFilter = 1;
const con1 = document.getElementsByClassName('con1')[0]
const con2 = document.getElementsByClassName('con2')[0]
let myGames = []

// console.log(filters[8]); // DG
filters[lastFilter].setAttribute('class', `sideicon j${lastFilter} filtered`)


// renewOptions(currentFilter-1);

for (let i = 1; i < filters.length; i++) {

    filters[i].addEventListener('click', (e) => {
        myGames = eval(phpGames.innerHTML);
        lastFilter = currentFilter;
        // console.log(`Before:- C: ${currentFilter}- L: ${lastFilter}`);
        console.log(e)

        // e.target.setAttribute('class', `sideicon j${i-1} filtered`) 
        // filters[i].setAttribute('class', `sideicon j${i-1} filtered`) 
        filters[i].setAttribute('class', `sideicon j${i} filtered`) 
        
        currentFilter = i;
        
            filters[lastFilter].setAttribute('class', `sideicon j${lastFilter}`) 
            if (currentFilter == lastFilter) {
                currentFilter = 1;
                filters[1].setAttribute('class', `sideicon j1 filtered`) 
            } 
        // console.log(`Now:- C: ${currentFilter}- L: ${lastFilter}`);

        renewOptions(currentFilter-1);
        makeGameDiv(currentFilter-1);
        updateTags(currentFilter);
        
        console.log('Filter: ', currentFilter-1, '- ', Games[currentFilter-1].name);
    })
}
filters[favGame].click()


function renewOptions(curr) {
    con2.innerHTML = Games[curr].gameOptions;
}


function updateTags(curr) {
    myGames.forEach(inst => { // pra cada instancia de um jogo 
    console.log('Jogo: ', inst.name); // debug
        
    if (inst.idCurr == curr) { // se a inst existir 
        let gamesDiv = document.getElementsByClassName('games');
        gamesDiv[0].innerHTML = ''; 

        gamesDiv[0].innerHTML += `${tagDiv[0]} Nickname: ${tagDiv[1]} ${inst.data[0]} ${tagDiv[2]}` // Nick
        
        gamesDiv[0].innerHTML += `${tagDiv[0]} Rank: ${tagDiv[1]} ${inst.data[1]} ${tagDiv[2]}` // 


        if (inst.data[2]) {
            inst.data[2].forEach(atr => {
                if (atr) {
                    gamesDiv[0].innerHTML += `${tagDiv[0]} Funções: ${tagDiv[1]} ${atr} ${tagDiv[2]}` // 
                }
            });
        }
    }
})  
}


function addToList(curr) {
let data = []

switch (curr) {
    case 2: // CODW
        data = []
        break;


    case 3: // Fort
    case 5: // Ow
        data = [
            // document.getElementById('menu1').value, // Rank
            [ // Posições
                document.getElementById('check1').checked ? document.getElementById('check1').value : false,
                document.getElementById('check2').checked ? document.getElementById('check2').value : false,
                document.getElementById('check3').checked ? document.getElementById('check3').value : false
            ]
        ]
        break;


    case 4: // Val
        data = [
            // document.getElementById('menu1').value, // Rank
            [ // Posições
                document.getElementById('check1').checked ? document.getElementById('check1').value : false,
                document.getElementById('check2').checked ? document.getElementById('check2').value : false,
                document.getElementById('check3').checked ? document.getElementById('check3').value : false,
                document.getElementById('check4').checked ? document.getElementById('check4').value : false
            ]
        ]
        break;


    case 6: // LOL
        data = [
            // document.getElementById('menu1').value, // Rank
            [ // Posições
                document.getElementById('check1').checked ? document.getElementById('check1').value : false,
                document.getElementById('check2').checked ? document.getElementById('check2').value : false,
                document.getElementById('check3').checked ? document.getElementById('check3').value : false,
                document.getElementById('check4').checked ? document.getElementById('check4').value : false,
                document.getElementById('check5').checked ? document.getElementById('check5').value : false
            ]
        ]
        break;


    case 7: // EAFC
        data = [
            [ // Posições
                document.getElementById('check1').checked ? document.getElementById('check1').value : false,
                document.getElementById('check2').checked ? document.getElementById('check2').value : false,
                document.getElementById('check3').checked ? document.getElementById('check3').value : false,
                document.getElementById('check4').checked ? document.getElementById('check4').value : false,
                document.getElementById('check5').checked ? document.getElementById('check5').value : false,
                document.getElementById('check6').checked ? document.getElementById('check6').value : false,
                document.getElementById('check7').checked ? document.getElementById('check7').value : false,
                document.getElementById('check8').checked ? document.getElementById('check8').value : false,
                document.getElementById('check9').checked ? document.getElementById('check9').value : false,
                document.getElementById('check10').checked ? document.getElementById('check10').value : false,
                document.getElementById('check11').checked ? document.getElementById('check11').value : false
            ]
        ]
        break;

    default: data = false; break;
}

//                                                  name                                      rank
data.unshift(document.getElementById('inputText').value, document.getElementById('menu1').value); // Nick

let newAdd = {
    idCurr: Games[curr-1].currNum,
    name: Games[curr-1].name,
    data: data
    }    

if (myGames.length > 0) { // se tiverem jogos
    let found = false
    myGames.forEach(inst => { // pra cada instancia de um jogo 
        console.log('Jogo: ', inst.name); // debug
        
        console.log('Jogo id: ', inst.idCurr, '\nNewadd id: ', newAdd.idCurr); // debug
        if (inst.idCurr == newAdd.idCurr) { // se a inst ja existir 
            console.log('Dados new: ', newAdd.data); 
            inst.data = []; // limpa
            console.log('Dados Jogo', inst.data, 'Zerado'); 
            inst.data = newAdd.data; // atualiza
            console.log('Jogo', inst.name, 'Atualizado'); 
            console.log('Dados Jogo', inst.data, 'Atualiz'); 
            lastEditedGame = inst.idCurr;
            return found = true;
        }else {
            console.log('ou seja não bate'); 
        }
    })
    found ? console.log("Não manda") : myGames.push(newAdd)
} else {
    console.log('Aqui é pra dar push'); //
    myGames.push(newAdd)
    lastEditedGame = newAdd.idCurr;
}
console.log('fim. ', myGames); //

//     console.log('Mygames é maior que 0');
//     console.log('Curr: ', curr);
    
//     console.log('Mygames ultimo id:', myGames[myGames.length-1].idCurr);
//     console.log('Newadd id:', newAdd.idCurr);


//     if (myGames[myGames.length-1].idCurr == newAdd.idCurr) {
//         console.log('É o mesmo id');
        
//         myGames[myGames.length-1].data = newAdd.data;
//         console.log('Jogo', myGames[myGames.length-1].name, 'Atualizado');

//     }else {
//         myGames.push(newAdd)
// }}else { 
//         
// }

// 

}

// const gDivs = [g1Div, g2Div, g3Div, g4Div, g5Div, g6Div, g7Div]

function makeGameDiv(curr) {
    // con1.innerHTML = '<h1>Meus Jogos</h1>' + Games[curr].gameDiv;
    con1.innerHTML = Games[curr].gameDiv;
}


