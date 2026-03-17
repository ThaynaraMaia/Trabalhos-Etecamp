function toggleLike(button) {
    const icon = button.querySelector('i');
    const isLiked = button.classList.contains('liked');

    if (isLiked) {
        button.classList.remove('liked');
        icon.classList.remove('bi-heart-fill');
        icon.classList.add('bi-heart');
    } else {
        button.classList.add('liked');
        icon.classList.remove('bi-heart');
        icon.classList.add('bi-heart-fill');
    }
}


function toggleCaption() {
    const caption = document.getElementById('caption-text');
    const button = document.getElementById('show-more-btn');

    if (caption.classList.contains('collapsed')) {
        caption.classList.remove('collapsed');
        button.textContent = 'menos';
    } else {
        caption.classList.add('collapsed');
        button.textContent = 'mais';
    }
}


document.querySelectorAll('.action-btn').forEach(btn => {
    btn.addEventListener('mouseenter', () => {
        btn.style.transform = 'scale(1.05)';
    });
    btn.addEventListener('mouseleave', () => {
        btn.style.transform = 'scale(1)';
    });
});

 const postText = document.getElementById('post-text');
    const showMoreBtn = document.getElementById('show-more-btn');
    const likesCountEl = document.getElementById('likes-count');
    let likesCount = 0;

    function toggleText() {
      if (postText.classList.contains('collapsed')) {
        postText.classList.remove('collapsed');
        showMoreBtn.textContent = 'menos';
      } else {
        postText.classList.add('collapsed');
        showMoreBtn.textContent = 'mais';
      }
    }

    // function toggleLike(button) {
    //   const icon = button.querySelector('i');
    //   const isLiked = button.classList.contains('liked');

    //   if (isLiked) {
    //     button.classList.remove('liked');
    //     icon.classList.replace('bi-heart-fill', 'bi-heart');
    //     likesCount--;
    //   } else {
    //     button.classList.add('liked');
    //     icon.classList.replace('bi-heart', 'bi-heart-fill');
    //     likesCount++;
    //   }
    //   likesCountEl.textContent = `${likesCount} ${likesCount === 1 ? 'curtida' : 'curtidas'}`;
    // }
    
// Função para curtir/descurtir postagem
document.addEventListener('DOMContentLoaded', function() {
    const botoesCurtir = document.querySelectorAll('.btn-curtir');
    
    botoesCurtir.forEach(botao => {
        botao.addEventListener('click', function() {
            const idPostagem = this.getAttribute('data-id');
            
            if (userId === 0) {
                alert('Você precisa estar logado para curtir postagens!');
                return;
            }
            
            curtirPostagem(userId, idPostagem, this);
        });
    });
});

function curtirPostagem(idUsuario, idPostagem, botao) {
    // Mostrar loading
    const iconeOriginal = botao.querySelector('i').className;
    const textoOriginal = botao.querySelector('span').textContent;
    
    botao.querySelector('i').className = 'bi bi-arrow-repeat';
    botao.querySelector('span').textContent = 'Processando...';
    botao.disabled = true;
    
    // ✅ CAMINHO CORRIGIDO - sobe um nível para sair da pasta instamarJS
    fetch('./curtir.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `id_usuario=${idUsuario}&id_postagem=${idPostagem}`
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Erro na requisição');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Atualiza contagem de curtidas
            const elementoCurtidas = document.getElementById(`curtidas-${idPostagem}`);
            if (elementoCurtidas) {
                elementoCurtidas.textContent = `${data.total_curtidas} curtidas`;
            }
            
            // Atualiza aparência do botão
            if (data.status === 'curtido') {
                botao.classList.add('ativo');
                botao.querySelector('i').className = 'bi bi-heart-fill';
                botao.querySelector('span').textContent = 'Descurtir';
            } else {
                botao.classList.remove('ativo');
                botao.querySelector('i').className = 'bi bi-heart';
                botao.querySelector('span').textContent = 'Curtir';
            }
        } else {
            alert('Erro: ' + data.message);
            // Restaurar estado original em caso de erro
            botao.querySelector('i').className = iconeOriginal;
            botao.querySelector('span').textContent = textoOriginal;
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro ao conectar com o servidor');
        // Restaurar estado original em caso de erro
        botao.querySelector('i').className = iconeOriginal;
        botao.querySelector('span').textContent = textoOriginal;
    })
    .finally(() => {
        botao.disabled = false;
    });
}

