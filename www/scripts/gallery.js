function enlargeImg(button) {
    const imgSrc = button.querySelector('img').src;
    const modal = document.createElement('div');
    modal.classList.add('modal');
    
    const modalImg = document.createElement('img');
    modalImg.src = imgSrc;
    modalImg.classList.add('modal-content');
    
    const closeBtn = document.createElement('span');
    closeBtn.innerHTML = '&times;';
    closeBtn.classList.add('close');

    modal.appendChild(modalImg);
    modal.appendChild(closeBtn);
    
    document.body.appendChild(modal);
    modal.style.display = 'block';
    
    closeBtn.onclick = function() {
        modal.style.display = 'none';
        modal.remove();
    };

    modal.onclick = function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
            modal.remove();
        }
    };
}

//TODO: impore, add buttons to the sides, zoom?, ESC to exit