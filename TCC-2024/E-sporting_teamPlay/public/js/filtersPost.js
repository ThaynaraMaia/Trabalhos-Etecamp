/* Games:
1 All 
2 COD
3 OW2
4 VAL
5 FOR
6 LOL 
7 FIF
*/

let postOption = document.getElementById("menu1");
let typeDiv = document.getElementById("type");
let isMine = document.getElementById("uid");
let isTour = false;


// Filters 
const filters = document.getElementsByClassName('sideicon');
let lastFilter = 1;
let currentFilter = 1;
const con1 = document.getElementsByClassName('con1')[0]
let selGame = document.getElementById('menu2')

// console.log(filters[8]); // DG
// filters[lastFilter].setAttribute('class', `sideicon j${lastFilter} filtered`)


for (let i = 1; i < filters.length; i++) {

    filters[i].addEventListener('click', (e) => {
    if (isTour) {
        if (i == 1) {
            console.log('Nada ve');
        }
        else {
            currentFilter = i;

            filters[i].setAttribute('class', `sideicon j${i} filtered`) // Acende            
            filters[lastFilter].setAttribute('class', `sideicon j${lastFilter}`) // Apaga
            if (currentFilter == lastFilter) {
                currentFilter = 1;
                filters[1].setAttribute('class', `sideicon j1 filtered`) // Acende 
            } 
        }
        console.log(`C: ${currentFilter} L: ${lastFilter}`);

        selGame.value = currentFilter - 1;
    }
    })
    
}





function makeGameDiv(curr) {
    // con1.innerHTML = '<h1>Meus Jogos</h1>' + Games[curr].gameDiv;
    con1.innerHTML = Games[curr].gameDiv;
}

postDiv = `
        <div style="display: flex; gap: 1vw; justify-content:center; align-items: center;">
            <label for="title"><strong>Título</strong></label>
            <input type="text" id="title" name="title" required placeholder="Título da postagem" class="tinps"><br>

        </div><br>
            <label for="title"><strong>Jogo</strong></label><br>
            <select id="menu2" name="game" required="true" class="inp" style="background: var(--black); border: none; height: fit-content; font-size: medium; width: 20vw">
                    <option value="0">----</option>
                    <option value="1">Call of Duty: Warzone</option>
                    <option value="2">Overwatch 2</option>
                    <option value="3">Valorant</option>
                    <option value="4">Fortnite</option>
                    <option value="5">League of Legends</option>
                    <option value="6">EAFC24</option>
                </select><br><br>

        <!-- </div> -->
        <label for="desc"><strong>Descrição</strong></label><br>
        <textarea id="desc" name="desc" required placeholder="Conteúdo"></textarea><br>


        
        <label for="image"><strong>Imagem</strong></label><br>
        <div id="imageDropArea" class="drop-area">
            <input type="file" id="image" name="image" accept="image/*">
            <br><br><span>Arraste a imagem aqui ou clique para selecionar</span>
            <img id="imagePreview" class="hidden" alt="Pré-visualização da Imagem">
        </div> 
        <br><br>
    </div>
`;

tourDiv = `
        <div style="display: flex; gap: 1vw; justify-content:center; align-items: center;">
            <label for="title"><strong>Título</strong></label>
            <input type="text" id="title" name="title" required placeholder="Título da postagem" class="tinps"><br>

        </div><br>
            <label for="title"><strong>Jogo</strong></label><br>
            <select id="menu2" name="game" required="true" class="inp" style="background: var(--black); border: none; height: fit-content; font-size: medium; width: 20vw">
                    <option value="0">----</option>
                    <option value="1">Call of Duty: Warzone</option>
                    <option value="2">Overwatch 2</option>
                    <option value="3">Valorant</option>
                    <option value="4">Fortnite</option>
                    <option value="5">League of Legends</option>
                    <option value="6">EAFC24</option>
                </select><br><br>

                
        <label for="menu3"><strong>Tipo</strong></label><br>
            <select id="menu3" name="typeTour" class="inp" style="background: var(--black); border: none; height: fit-content; font-size: medium; width: 20vw">
                <option value="0">Casual</option>
                <option value="1">Oficial</option>
            </select><br><br>

        <label for="desc"><strong>Descrição</strong></label><br>
        <textarea id="desc" name="desc" required placeholder="Conteúdo"></textarea><br>

        <label for="number"><strong>Número de Jogadores</strong></label><br>
        <input type="number" id="number" name="number" required placeholder="Número" style="width: 8vw" class="tinps"><br>


        
        <label for="image"><strong>Imagem</strong></label><br>
        <div id="imageDropArea" class="drop-area">
            <input type="file" id="image" name="image" accept="image/*">
            <br><br><span>Arraste a imagem aqui ou clique para selecionar</span>
            <img id="imagePreview" class="hidden" alt="Pré-visualização da Imagem">
        </div>
        <br><br>

        <label for="dates"><strong>Data Início</strong></label>
        <input type="date" id="dates" name="dates" required placeholder="Título da postagem" class="inp" style="height: fit-content; font-size: large"><br><br><br>
        <label for="datee"><strong>Data Fim</strong></label>
        <input type="date" id="datee" name="datee" required placeholder="Título da postagem" class="inp" style="height: fit-content; font-size: large"><br><br><br>

`;

postOption.addEventListener("change", () => {
    typeDiv.innerHTML = '';
    if (postOption.value == 'post') {
        isTour = false
        typeDiv.innerHTML = postDiv;
        selGame = document.getElementById('menu2')
    } else {
        isTour = true
        typeDiv.innerHTML = tourDiv;
        selGame = document.getElementById('menu2')
    }
})