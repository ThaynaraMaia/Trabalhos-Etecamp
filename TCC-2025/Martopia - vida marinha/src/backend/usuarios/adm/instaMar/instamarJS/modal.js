const botBtn = document.getElementById('bot-btn');
const botOptions = document.getElementById('bot-options');
let optionsVisible = false;

botBtn.addEventListener('click', () => {
    optionsVisible = !optionsVisible;
    if (optionsVisible) {
        botOptions.classList.add('show');
        botBtn.style.transform = 'rotate(45deg)';
    } else {
        botOptions.classList.remove('show');
        botBtn.style.transform = 'rotate(0deg)';
    }
});


document.getElementById('camera-btn').addEventListener('click', () => {
    openModal('camera-modal');
});

document.getElementById('text-btn').addEventListener('click', () => {
    openModal('text-modal');
});

function openModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.classList.add('show');
    document.body.style.overflow = 'hidden';
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    modal.classList.remove('show');
    document.body.style.overflow = '';

 
    if (modalId === 'camera-modal') {
        resetCameraForm();
    } else if (modalId === 'text-modal') {
        resetTextForm();
    }
}

function resetCameraForm() {
    document.getElementById('file-input').value = '';
    document.getElementById('photo-title').value = '';
    document.getElementById('photo-description').value = '';
    document.getElementById('preview-container').style.display = 'none';
    document.getElementById('image-previews').innerHTML = '';
}

function resetTextForm() {
    document.getElementById('text-title').value = '';
    document.getElementById('text-content').value = '';
    document.getElementById('text-category').value = '';
    document.getElementById('text-publish').checked = false;
}

function handleFileSelect(event) {
    const files = event.target.files;
    const previewContainer = document.getElementById('preview-container');
    const imagePreviews = document.getElementById('image-previews');

    imagePreviews.innerHTML = '';

    if (files.length > 0) {
        previewContainer.style.display = 'block';

        Array.from(files).forEach(file => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'preview-image';
                    img.style.margin = '5px';
                    imagePreviews.appendChild(img);
                };
                reader.readAsDataURL(file);
            }
        });
    } else {
        previewContainer.style.display = 'none';
    }
}

function submitPhotos() {
    const files = document.getElementById('file-input').files;
    const title = document.getElementById('photo-title').value;
    const description = document.getElementById('photo-description').value;

    if (files.length === 0) {
        alert('Por favor, selecione pelo menos uma foto.');
        return;
    }

    if (!title.trim()) {
        alert('Por favor, adicione uma legenda para suas fotos.');
        return;
    }
    
    alert(`Fotos enviadas com sucesso!\nTítulo: ${title}\nQuantidade: ${files.length} foto(s)`);
    closeModal('camera-modal');
}

function submitText() {
    const title = document.getElementById('text-title').value;
    const content = document.getElementById('text-content').value;

    // const publishNow = document.getElementById('text-publish').checked;

    if (!title.trim()) {
        alert('Por favor, adicione um título para seu texto.');
        return;
    }

    if (!content.trim()) {
        alert('Por favor, adicione o conteúdo do texto.');
        return;
    }


    // const status = publishNow ? 'publicado' : 'salvo como rascunho';
    // alert(`Texto ${status} com sucesso!\nTítulo: ${title}\nCategoria: ${category}`);
    // closeModal('text-modal');
}


document.addEventListener('click', (e) => {
    if (e.target.classList.contains('modal')) {
        closeModal(e.target.id);
    }
});


document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        const modals = document.querySelectorAll('.modal.show');
        modals.forEach(modal => closeModal(modal.id));
    }
});