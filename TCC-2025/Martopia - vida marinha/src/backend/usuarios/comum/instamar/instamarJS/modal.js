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