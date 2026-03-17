// Buttons 
const pg1btn = document.querySelector('[PG1]');
const pg2btn = document.querySelector('[PG2]');
const pg3btn = document.querySelector('[PG3]');
const pg4btn = document.querySelector('[PG4]');
const pg5btn = document.querySelector('[PG5]');
const pg6btn = document.querySelector('[PG6]');
const buttons = [pg1btn, pg2btn, pg3btn, pg4btn, pg5btn, pg6btn];

// Content area
const contt = document.querySelectorAll('[CONTTITLE]');
const contp = document.querySelectorAll('[CONTP]');
const conti = document.querySelector('[CONTIMG]');
const contiextra = document.querySelectorAll('[CONTIMGEX]');

// Globals
let active = pg1btn;
active.setAttribute('class', 'Btn login pg on');

pg1btn.addEventListener('click', () => {
    active.setAttribute('class', 'Btn login pg');
    active = pg1btn;
    active.setAttribute('class', 'Btn login pg on');
    setCont(0)
});
pg2btn.addEventListener('click', () => {
    active.setAttribute('class', 'Btn login pg');
    active = pg2btn;
    active.setAttribute('class', 'Btn login pg on');
    setCont(2)
});
pg3btn.addEventListener('click', () => {
    active.setAttribute('class', 'Btn login pg');
    active = pg3btn;
    active.setAttribute('class', 'Btn login pg on');
    setCont(4)
});
pg4btn.addEventListener('click', () => {
    active.setAttribute('class', 'Btn login pg');
    active = pg4btn;
    active.setAttribute('class', 'Btn login pg on');
    setCont(6)
});
pg5btn.addEventListener('click', () => {
    active.setAttribute('class', 'Btn login pg');
    active = pg5btn;
    active.setAttribute('class', 'Btn login pg on');
    setCont(8)
});
pg6btn.addEventListener('click', () => {
    active.setAttribute('class', 'Btn login pg');
    active = pg6btn;
    active.setAttribute('class', 'Btn login pg on');
    setCont(10)
});

const contentTitles = [
    //1
    `Introdução`,                  
    //2
    `Apresentação da Empresa`,    
    //3
    `Visão Estratégica`,            
    //4
    `->`,         
    //5
    `Comunicação com o Público`,         
    //6
    `Conceito da Marca`,            
    //7
    `Tipografia`,       
    //8
    `Versão da Marca`,      
    //9
    ``,     
    //10
    ``,
    //11
    `Exemplo de aplicação:`,
    //12
    ``
]
const contentP = [
    //1
    `Para garantir que as marcas registradas da ParaGames sejam usadas de 
    forma consistente em todos os materiais de divulgação e comunicação, temos 
    algumas diretrizes que devem ser seguidas por todos os parceiros ao usar a 
    marca ParaGames.`,
    //2
    `A empresa ParaGames tem foco na distribuição de jogos. Criada em 
    02/04/2003 por Pablo Carvalho, a empresa vem crescendo nos últimos anos 
    devido ao crescimento no mercado de jogos digitais. Segue os contatos da 
    empresa: 
    Site: ParaGames.com.br
    Email: suporte.paragames@gmail.com
    Telefone: +55 932772181
    Responsáveis: Pablo Henrique Pinheiro de Carvalho e Kauã Lucio Rosa`,
    //3
    `O objetivo da empresa é fidelizar clientes focando em oferecer preços 
    acessíveis e um bom atendimento.`,
    //4
    `A empresa tenta se manter ativa em redes sociais, principalmente no X (Twitter), 
    para manter contato com o público e buscar ideias de como melhorar para 
    manter os clientes fiéis.`,
    //5
    `A empresa é bem avaliada no mercado regional, e busca reconhecimento no 
    mercado nacional.`,
    //6
    `O conceito da marca se resume a gerar uma boa experiência para o cliente, 
    pois assim os clientes se sentem satisfeitos e continuam solicitando os serviços 
    da empresa.`,
    //7
    `A fonte da marca é Swis721 BlkEx BT, com a quebra de linha mostrada abaixo. 
    O “Para” deve ser branco e as outras cores são indicadas na seção de cores.`,
    //8
    `A marca pode ser apresentada nas seguintes versões:`,
    //9
    ``,
    //10
    ``,
    //11
    ``,
    //12
    ``
]
const contentImgs = [
    `../assets/Para games horizontal.png`,
    ``,
    ``,
    ``,
    ``,
    ``,
    `../assets/cores.png`,
    ``,
    `../assets/id1.png`,
    ``,
    `../assets/print_paragames.png`
]
const contentImgsEx = [
    `../assets/v1.png`, 
    `../assets/v2.png`,
    `../assets/errado.png`,
    `../assets/fundos.png`,
    `../assets/id2.png`,
    `../assets/id3.png`,
]

function setCont(page) {
    contt[0].innerHTML = contentTitles[page];
    contt[1].innerHTML = contentTitles[page + 1];
    contp[0].innerHTML = contentP[page];
    contp[1].innerHTML = contentP[page + 1];
    conti.setAttribute('src', contentImgs[page]);
    switch (page) {
        case 0: conti.style.width = `20vw`;
        clearEx();
                 contiextra[0].setAttribute('src', ''); clearEx(); break;


        case 2: clearEx(); break;
        case 4: clearEx(); break;


        case 6: contiextra[0].setAttribute('src', contentImgsEx[0]);
                clearEx();
                contiextra[1].setAttribute('src', contentImgsEx[1]); 
                contiextra[2].setAttribute('src', contentImgsEx[2]);
                contiextra[3].setAttribute('src', contentImgsEx[3]);
        conti.style.width = `20vw`;


        case 8: contiextra[0].setAttribute('src', contentImgsEx[0]);
                 clearEx();
                 contiextra[1].setAttribute('src', contentImgsEx[4]); 
                 contiextra[2].setAttribute('src', contentImgsEx[5]); 
                 conti.style.width = `20vw`; break;


        case 10: conti.style.width = `90vw`;
                 clearEx(); break;
                 
                 
        default: conti.style.width = `20vw`;
                 clearEx();
                 contiextra[0].setAttribute('src', '');
                 contiextra[1].setAttribute('src', ''); break;
                }
}


function clearEx() {
    contiextra[0].setAttribute('src', '');
    contiextra[1].setAttribute('src', '');
    contiextra[2].setAttribute('src', '');
    contiextra[3].setAttribute('src', '');
}