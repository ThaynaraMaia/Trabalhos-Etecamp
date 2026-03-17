// Abrir o modal de upload de fotos
document.getElementById('addPhotoBtn').onclick = function() {
    document.getElementById('photoModal').style.display = 'block';
}

// Fechar o modal
document.querySelector('.close').onclick = function() {
    document.getElementById('photoModal').style.display = 'none';
}

// Fechar o modal ao clicar fora
window.onclick = function(event) {
    if (event.target == document.getElementById('photoModal')) {
        document.getElementById('photoModal').style.display = 'none';
    }
}
