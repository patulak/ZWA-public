function loadGallery(galleryId) {
    let galleryContainer = document.getElementById('images_container');

    if (galleryId) {
        galleryContainer.innerHTML = 'Načítání galerie...';
        const xhr = new XMLHttpRequest();
        xhr.open('GET', `fetch_data?gallery=${galleryId}`, true);

        xhr.onload = function () {
            if (xhr.status === 200) {
                galleryContainer.innerHTML = xhr.responseText;
            } else {
                console.error('ERROR:', xhr.status, xhr.statusText);
                galleryContainer.innerHTML = '<p>Chyba načítání.</p>';
            }
        };

        xhr.onerror = function () {
            console.error('Chyba načítání images');
            galleryContainer.innerHTML = '<p>Chyba načítání.</p>';
        };

        xhr.send();
    } else {
        galleryContainer.innerHTML = '';
    }
}

function loadCards() {
    let cardsContainer = document.getElementById('cards_container');
    cardsContainer.innerHTML = 'Načítání příspěvků...';

    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'fetch_data?cards=true', true);

    xhr.onload = function () {
        if (xhr.status === 200) {
            cardsContainer.innerHTML = "<h2>Smazat příspěvek</h2>" + xhr.responseText;
        } else {
            console.error('ERROR:', xhr.status, xhr.statusText);
            cardsContainer.innerHTML = '<p>Chyba načítání.</p>';
        }
    };

    xhr.onerror = function () {
        console.error('ERROR loading card names');
        cardsContainer.innerHTML = '<p>Chyba načítání.</p>';
    };

    xhr.send();
}

document.addEventListener("DOMContentLoaded", function() {
    const initialGalleryId = document.getElementById('gallery_in').value;
    loadGallery(initialGalleryId);
    loadCards();

    document.getElementById("remove_gallery_form").addEventListener('submit', (event) => {
        const confirmation = confirm('Opravdu chcete smazat tuto galerii?');
        if (!confirmation) {
            event.preventDefault();
        }
    });
});





const swapButton = document.getElementById("swapForms")
swapButton.addEventListener("click", swapFormsVisibility);
    
function swapFormsVisibility(){
    if (swapButton.textContent == "Galerie"){
        swapButton.textContent = "Příspěvky";
    }
    else{
        swapButton.textContent = "Galerie";
    }
    const switches = document.querySelectorAll(".switchable");
    switches.forEach((item) => {
    item.classList.toggle("switchedOff");
    })
    const initialGalleryId = document.getElementById('gallery_in').value;
    loadGallery(initialGalleryId);
    loadCards();
}

document.addEventListener('DOMContentLoaded', () => {
    const params = new URLSearchParams(window.location.search);
    const side = params.get('side');

    if (side === 'gallery'){
        swapFormsVisibility();
    }
});