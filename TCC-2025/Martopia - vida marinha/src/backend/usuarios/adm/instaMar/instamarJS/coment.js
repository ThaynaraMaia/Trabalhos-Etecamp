// const commentsData = [
//     {
//         nome: "Maria Silva",
//         foto: "../../../../frontend/public/img/tartaruga.jpg",
//         texto: "Adorei essa postagem!"
//     },
//     {
//         nome: "João Pereira",
//         foto: "../../../../frontend/public/img/fotoperfil.png",
//         texto: "Muito interessante, obrigado por compartilhar."
//     }
// ];


// function renderComments() {
//     const commentsList = document.getElementById('comments-list');
//     commentsList.innerHTML = '';

//     commentsData.forEach(comment => {
//         const commentItem = document.createElement('div');
//         commentItem.classList.add('comment-item');

//         commentItem.innerHTML = `
//             <img src="${comment.foto}" alt="Foto de perfil de ${comment.nome}">
//             <div>
//                 <div class="comment-username">${comment.nome}</div>
//                 <div class="comment-text">${comment.texto}</div>
//             </div>
//         `;

//         commentsList.appendChild(commentItem);
//     });
// }

// function abrirModal() {
//     renderComments();
//     openModal('comments-modal');
// }


// document.getElementById('post-comment-btn').addEventListener('click', () => {
//     const input = document.getElementById('new-comment-input');
//     const texto = input.value.trim();

//     if (texto === '') {
//         alert('Por favor, escreva um comentário antes de postar.');
//         return;
//     }

//     const novoComentario = {
//         nome: "<?php echo addslashes($_SESSION['nome']); ?>",
//         foto: "<?php echo addslashes($foto); ?>",
//         texto: texto
//     };

//     commentsData.push(novoComentario);
//     renderComments();
//     input.value = '';
// });



// Abrir modal e carregar comentários
function abrirModal(idPostagem) {
    document.getElementById('post-id-comentario').value = idPostagem;
    carregarComentarios(idPostagem);
    openModal('comentarios-modal');
}

// Carregar comentários do banco
function carregarComentarios(idPostagem) {
    fetch(`./carregarComent.php?id_postagem=${idPostagem}`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('lista-comentarios').innerHTML = html;
        })
        .catch(err => console.error("Erro ao carregar comentários:", err));
}

// Enviar novo comentário
document.getElementById('btn-enviar-comentario').addEventListener('click', () => {
    const idPostagem = document.getElementById('post-id-comentario').value;
    const texto = document.getElementById('texto-comentario').value.trim();

    if (texto === '') {
        alert('Por favor, escreva um comentário.');
        return;
    }

    const formData = new FormData();
    formData.append('id_postagem', idPostagem);
    formData.append('texto', texto);

    fetch('./novoComent.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(result => {
        if (result.trim() === "ok") {
            document.getElementById('texto-comentario').value = '';
            carregarComentarios(idPostagem); // recarregar lista
        } else {
            alert("Erro ao salvar comentário.");
        }
    })
    .catch(err => console.error("Erro ao enviar comentário:", err));
});
