// // Ranking Quiz
//         const tabelaQuiz = document.getElementById("rankingQuiz");
//         let rankingQuiz = JSON.parse(localStorage.getItem("ranking")) || [];

//         rankingQuiz.sort((a,b) => {
//             if(b.acertos === a.acertos){
//                 return a.tempo - b.tempo;
//             }
//             return b.acertos - a.acertos;
//         });

//         rankingQuiz.forEach((jogador, index) => {
//             const min = Math.floor(jogador.tempo/60);
//             const sec = jogador.tempo % 60;
//             const tempoFormatado = `${min}:${sec.toString().padStart(2,"0")}`;
//             const row = document.createElement("tr");
//             row.innerHTML = `
//                 <td>${index+1}</td>
//                 <td>${jogador.nome}</td>
//                 <td>${jogador.acertos}</td>
//                 <td>${tempoFormatado}</td>
//             `;
//             tabelaQuiz.appendChild(row);
//         });

//         // Ranking Memória
//         const tabelaMemoria = document.getElementById("rankingMemoria");
//         let rankingMemoria = JSON.parse(localStorage.getItem("rankingMemoria")) || [];

//         rankingMemoria.sort((a,b) => a.tempo - b.tempo);

//         rankingMemoria.forEach((jogador, index) => {
//             let tempo = jogador.tempo;
//             if(typeof tempo === "number"){
//                 const min = Math.floor(tempo / 60);
//                 const sec = tempo % 60;
//                 tempo = `${min}:${sec.toString().padStart(2,"0")}`;
//             }
//             const row = document.createElement("tr");
//             row.innerHTML = `
//                 <td>${index+1}</td>
//                 <td>${jogador.nome}</td>
//                 <td>${tempo}</td>
//             `;
//             tabelaMemoria.appendChild(row);
//         });


//     rankingQuiz.slice(0, 10).forEach((jogador, index) => {
//     const min = Math.floor(jogador.tempo / 60);
//     const sec = jogador.tempo % 60;
//     const tempoFormatado = `${min}:${sec.toString().padStart(2, "0")}`;
//     const row = document.createElement("tr");
//     row.innerHTML = `
//         <td>${index + 1}</td>
//         <td>${jogador.nome}</td>
//         <td>${jogador.acertos}</td>
//         <td>${tempoFormatado}</td>
//     `;
//     tabelaQuiz.appendChild(row);
// });


// rankingMemoria.slice(0, 10).forEach((jogador, index) => {
//     let tempo = jogador.tempo;
//     if (typeof tempo === "number") {
//         const min = Math.floor(tempo / 60);
//         const sec = tempo % 60;
//         tempo = `${min}:${sec.toString().padStart(2, "0")}`;
//     }
//     const row = document.createElement("tr");
//     row.innerHTML = `
//         <td>${index + 1}</td>
//         <td>${jogador.nome}</td>
//         <td>${tempo}</td>
//     `;
//     tabelaMemoria.appendChild(row);
// });


