const gridContainer = document.getElementById('tours-grid').querySelector('tbody');
const paginationContainer = document.getElementById('pagination');

let currentPage = 1;
const itemsPerPage = 8;

let global_data = [];


document.getElementById('add-button').addEventListener('click', toggleAddForm);

document.getElementById('add-form').addEventListener('submit', function (e) {
    fetchTours();
});

function clearAddForm(){
    document.getElementById("id_action_type").value = "add";
    document.getElementById("id_submit").textContent = "Přidat";
    document.getElementById("id_tour_datetime").value = "";
    document.getElementById("id_capacity").value = "";
    document.getElementById("id_state").value = "closed";
    document.getElementById("id_payment_status").value = "not_paid";
    document.getElementById("id_payment_method").value = "cash";
    document.getElementById("id_description").value = "";
    document.getElementById("id_number_of_guides").value = "";
    document.getElementById("id_tour_id").value = "";
}

function toggleAddForm(forceDisplay = null) {
    const addForm = document.getElementById('add-form');
    const params = new URLSearchParams(window.location.search);
    const side = params.get('state');

    
    if (forceDisplay === true || forceDisplay === false) {
        addForm.style.display = forceDisplay ? 'block' : 'none';
    } else {
        if (addForm.style.display === 'none'){
            addForm.style.display = 'block';
            if (side !== 'open'){
                clearAddForm();
            }
        }
        else{
            if (document.getElementById("id_action_type").value == "edit"){
                if (side !== 'open'){
                    clearAddForm();
                }
            }
            else{
                addForm.style.display = 'none';
            }
        }
    }
}

function fetchTours(page = 1) {
    fetch(`fetch_tours?page=${page}`)
        .then(response => response.json())
        .then(data => {
            global_data = data.data;
            renderGrid(data.data);
            renderPagination(data.total, data.currentPage, data.itemsPerPage);
        })
        .catch(error => console.error('Error fetching data:', error));
}

function tsl(str){
    switch (str){
        case "canceled":
            return "Zrušeno";
        case "open":
            return "Otevřeno";
        case "closed":
            return "Uzavřeno";
        case "paid":
            return "Placeno";
        case "not_paid":
            return "Neplaceno";
        case "cash":
            return "Hotově";
        case "invoice":
            return "Faktura";
        default:
            return str;
    }
}

function fmt(datetime){
    const [datePart, timePart] = datetime.split(' ');
    const [year, month, day] = datePart.split('-');
    const [hours, minutes] = timePart.split(':');

    return `${parseInt(day)}.${parseInt(month)}.${year} ${hours}:${minutes}`;
}

function renderGrid(data) {
    gridContainer.innerHTML = '';
    let counter = 0;
    data.forEach(row => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${row.tour_id}</td>
            <td>${fmt(row.tour_datetime)}</td>
            <td>${row.used_slots} / ${row.capacity}</td>
            <td>${tsl(row.state)}</td>
            <td>${tsl(row.payment_status)}</td>
            <td>${tsl(row.payment_method)}</td>
            <td>${row.description}</td>
            <td>${row.number_of_guides}</td>
            <td>${fmt(row.updated_at)}</td>
            <td>${fmt(row.created_at)}</td>
            <td>
            <button id="${"row_"+counter}">Upravit</button>
            <form method="POST" action="tours" class="confirm">
            <button type="submit" id="${"del_"+counter}">Smazat</button>
            <input type="hidden" name="remove" value="${row.tour_id}">
            </form>
            </td>
        `;
        gridContainer.appendChild(tr);
        counter += 1;
    });

    const editButtons = document.querySelectorAll('[id^="row_"]');

    editButtons.forEach(button => {
        button.addEventListener('click', function () {
            const rowId = this.id.split('_')[1];
            const tour = global_data[rowId];
            if (tour){
                console.log(tour);
                toggleAddForm(true);
                document.getElementById("id_tour_datetime").value = tour.tour_datetime;
                document.getElementById("id_capacity").value = tour.capacity;
                document.getElementById("id_state").value = tour.state;
                document.getElementById("id_payment_status").value = tour.payment_status;
                document.getElementById("id_payment_method").value = tour.payment_method;
                document.getElementById("id_description").value = tour.description;
                document.getElementById("id_number_of_guides").value = tour.number_of_guides;
                document.getElementById("id_action_type").value = "edit";
                document.getElementById("id_submit").textContent = "Upravit";
                document.getElementById("id_tour_id").value = tour.tour_id;
            }
        });
    });

    const confirmForms = document.querySelectorAll('.confirm');

    confirmForms.forEach(form => {
        form.addEventListener('submit', (event) => {
            const confirmation = confirm('Opravdu chcete smazat tuto prohlídku?');
            if (!confirmation) {
                event.preventDefault();
            }
        });
    });
}

function renderPagination(total, currentPage, itemsPerPage) {
    paginationContainer.innerHTML = '';

    const totalPages = Math.ceil(total / itemsPerPage);

    for (let i = 1; i <= totalPages; i++) {
        const button = document.createElement('button');
        button.textContent = i;
        button.disabled = i === currentPage;
        button.addEventListener('click', () => {
            fetchTours(i);
        });
        paginationContainer.appendChild(button);
    }
}

fetchTours(currentPage);

document.addEventListener('DOMContentLoaded', () => {
    const params = new URLSearchParams(window.location.search);
    const side = params.get('state');

    if (side === 'open'){
        toggleAddForm();
    }
});