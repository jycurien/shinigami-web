document.addEventListener('DOMContentLoaded', () => {
    const imageInputRows = document.querySelectorAll('.js-draggable-image');
    if (imageInputRows.length > 0) {
        imageInputRows.forEach(r => {
            const input = r.querySelector('input');
            input.style.display = 'none';
            const dropZone = document.createElement('div');
            dropZone.classList.add('js-drop-zone', 'drop-zone', 'mb-5');
            dropZone.textContent = 'Déposez votre fichier ici';
            r.appendChild(dropZone);
            input.addEventListener('change', e => {
                e.preventDefault();
                previewImage(dropZone, input.files[0]);
            });
            dropZone.addEventListener('click', e => {
                e.preventDefault();
                input.click();
            });
            dropZone.addEventListener('dragover', e => {
                e.preventDefault();
                e.dataTransfer.dropEffect = 'copy';
                dropZone.style.backgroundColor = '#eedddd';
                dropZone.style.borderStyle = 'dashed';
            });
            dropZone.addEventListener('dragleave', e => {
                e.preventDefault();
                dropZone.style.backgroundColor = '#ddd';
                dropZone.style.borderStyle = 'initial';
            });
            dropZone.addEventListener('drop', e => {
                e.preventDefault();
                const { files } = e.dataTransfer;
                previewImage(dropZone, files[0]);
                input.files = e.dataTransfer.files; // TODO this does not work really upload file...
            });
        })
    }
    const previewImage = (dropZone, file) => {
        dropZone.style.backgroundColor = '#ddd';
        dropZone.style.borderStyle = 'initial';
        const img = document.createElement("img");
        img.src = URL.createObjectURL(file);
        dropZone.innerHTML = '';
        dropZone.appendChild(img);
    };
});