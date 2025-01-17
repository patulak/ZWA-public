function loadGallery(galleryId) {
    let galleryContainer = document.getElementById('images_container');

    if (galleryId) {
        galleryContainer.innerHTML = 'Načítání galerie...';
        const xhr = new XMLHttpRequest();
        xhr.open('GET', `fetch_data?gallery=${galleryId}`, true);

        xhr.onload = function () {
            if (xhr.status === 200) {
                galleryContainer.innerHTML = xhr.responseText;
                const confirmDeleteForms = document.querySelectorAll('.confirm-delete-gall');
                confirmDeleteForms.forEach(form => {
                    form.addEventListener('submit', (event) => {
                        const confirmed = confirm('Opravdu si přejete smazat tento obrázek?');
                        if (!confirmed) {
                            event.preventDefault();
                        }
                    });
                });
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

document.addEventListener('DOMContentLoaded', () => {
    const gallerySelect = document.getElementById('gallery_in');

    gallerySelect.addEventListener('change', function () {
        const galleryId = this.value;

        if (!galleryId) {
            console.log('No gallery selected.');
            return;
        }

        loadGallery(galleryId);
    });
});

document.addEventListener('DOMContentLoaded', () => {
    const gallerySelect = document.getElementById('edit_gallery_in');

    const loadGalleryData = (galleryId) => {
        if (!galleryId) {
            document.getElementById('edit_gallery_title_in').value = '';
            document.getElementById('edit_gallery_desc_in').value = '';
            return;
        }

        fetch(`fetch_gall_data?gallery_id=${galleryId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                document.getElementById('edit_gallery_title_in').value = data.title || '';
                document.getElementById('edit_gallery_desc_in').value = data.description || '';
            })
            .catch(error => {
                console.error('Error fetching gallery data:', error);
            });
    };

    gallerySelect.addEventListener('change', function () {
        loadGalleryData(this.value);
    });

    loadGalleryData(gallerySelect.value);
});


function loadCards() {
    let cardsContainer = document.getElementById('cards_container');
    cardsContainer.innerHTML = 'Načítání příspěvků...';

    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'fetch_data?cards=true', true);

    xhr.onload = function () {
        if (xhr.status === 200) {
            cardsContainer.innerHTML = "<h2>Smazat příspěvek</h2>" + xhr.responseText;

            const confirmDeleteForms = document.querySelectorAll('.confirm-delete-card');
            confirmDeleteForms.forEach(form => {
            form.addEventListener('submit', (event) => {
            const confirmed = confirm('Opravdu si přejete smazat tento příspěvek?');
            if (!confirmed) {
                event.preventDefault();
            }
            
        });
    });
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