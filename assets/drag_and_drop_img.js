document.addEventListener('DOMContentLoaded', () => {
    const imageInputRows = document.querySelectorAll('.js-draggable-image');
    if (imageInputRows.length > 0) {
        imageInputRows.forEach(r => {
            const input = r.querySelector('input');
            const initialImageUrl = r.dataset.initialImage;
            input.style.display = 'none';
            const dropZone = document.createElement('div');
            dropZone.classList.add('js-drop-zone', 'drop-zone', 'mb-3');
            dropZone.innerHTML = '<div>Cliquez ou déposez votre fichier ici</div>';
            if(initialImageUrl !== undefined && initialImageUrl !== '') {
                dropZone.innerHTML += `<img src="${initialImageUrl}">`;
            }
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
                dropZone.querySelector('div').style.backgroundColor = 'rgba(0,0,0,0.9)';
                dropZone.style.borderStyle = 'dashed';
            });
            dropZone.addEventListener('dragleave', e => {
                e.preventDefault();
                dropZone.querySelector('div').style.backgroundColor = 'rgba(0,0,0,0.5)';
                dropZone.style.borderStyle = 'solid';
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
        dropZone.style.borderStyle = 'solid';
        dropZone.querySelector('div').innerHTML = '';
        dropZone.querySelector('div').style.backgroundColor = 'rgba(0,0,0,0)';
        let img = dropZone.querySelector('img');
        if (!img) {
            img = document.createElement("img");
            dropZone.appendChild(img);
        }
        img.src = URL.createObjectURL(file);
    };
});