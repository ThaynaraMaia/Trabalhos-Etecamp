
  const fileInput = document.getElementById('file-input');
  const imagePreviews = document.getElementById('image-previews');

  fileInput.addEventListener('change', function () {
    imagePreviews.innerHTML = ''; 

    const files = this.files;
    if (files.length === 0) {
      return;
    }

    const file = files[0];
   
    if (!file.type.startsWith('image/')) {
      imagePreviews.innerHTML = '<p>Por favor, selecione um arquivo de imagem válido.</p>';
      return;
    }

    const reader = new FileReader();

    reader.onload = function (e) {
      const img = document.createElement('img');
      img.src = e.target.result;
      img.style.maxWidth = '300px';
      img.style.maxHeight = '200px';
      img.style.borderRadius = '10px';
      img.style.boxShadow = '0 0 10px rgba(0,0,0,0.2)';
      imagePreviews.appendChild(img);
    };

    reader.readAsDataURL(file);
  });

