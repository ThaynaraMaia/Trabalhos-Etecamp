document.getElementById('addPhotoBtn').addEventListener('click', function() {
    document.getElementById('fileToUpload').click(); // Simula o clique no input file
});

document.getElementById('fileToUpload').addEventListener('change', function() {
    document.getElementById('uploadForm').submit(); // Submete o formulário automaticamente após escolher a imagem
});