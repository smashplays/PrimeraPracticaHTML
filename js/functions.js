const objectArray = [{
        "name": "Control de humo",
        "description": "Los sensores de humo son capaces de detectar el humo de un lugar a tiempo",
        "serial": "1582",
        "status": "Activo",
        "priority": "Alta"
    },
    {
        "name": "Medidor de presión",
        "description": "Los sensores medidores de presión son muy utilizados en el sector agricola para conocer, por ejemplo el flujo de agua de un lugar y para enviar una notificación a los equipos responsables cuando algo necesite ser corregido ",
        "serial": "1978",
        "status": "Inactivo",
        "priority": "Media"
    },
    {
        "name": "Control de la humedad",
        "description": "Permiten tener controlados factores como el clima o el almacenamiento de productos perecederos",
        "serial": "1956",
        "status": "Activo",
        "priority": "Alta"
    }
];

addEventListener('load', () => {
    generateTable(objectArray);
});

function deleteTable() {
    const element = document.querySelectorAll('tbody .row');
    for (let i = 0; i < element.length; i++) {
        element[i].remove();
    }
}

function generateTable(data) {
    deleteTable();
    const table = document.querySelector('tbody');
    for (let i = 0; i < data.length; i++) {
        let row = `<tr id="row${i}" class="row">
                        <td><button id="${i}" class="button" onclick="removeRow(${i})">X</button><button id="showModal" onclick="editRow(this, ${i})">Edit</button></td>
						<td id="name${i}">${data[i].name}</td>
						<td id="description${i}">${data[i].description}</td>
						<td id="serial${i}">${data[i].serial}</td>
                        <td id="status${i}">${data[i].status}</td>
                        <td id="priority${i}">${data[i].priority}</td>
					</tr>`;
        table.innerHTML += row;
    }
}


function removeRow(button) {
    const row = document.getElementById('row' + button);
    row.remove();
}

function filterTable() {
    const filter = document.getElementById('filter');
    const search = filter.value.toLowerCase();

    if (search.length >= 3) {
        const filteredObjects = objectArray.filter((data) => {
            return (
                data.name.toLowerCase().includes(search) ||

                data.description.toLowerCase().includes(search) ||

                data.serial.toLowerCase().includes(search) ||

                data.status.toLowerCase().includes(search) ||

                data.priority.toLowerCase().includes(search)
            );
        });
        generateTable(filteredObjects);
    } else {
        generateTable(objectArray);
    }
}

function editRow(button, number) {
    const formModal = document.querySelector('#formModal');
    const closeBtn = document.querySelector('#closeBtn');

    selectedRow = button.parentElement.parentElement;

    formModal.classList.add('showModal');

    closeBtn.addEventListener('click', () => {
        formModal.classList.remove('showModal');
    });

    // Valores al abrir el formulario

    document.getElementById("name").value = objectArray[number].name;
    document.getElementById("description").value = objectArray[number].description;
    document.getElementById("serial").value = objectArray[number].serial;

    const active = document.getElementById('status');

    if (objectArray[number].status === 'Activo') {
        active.checked = true;
    } else {
        active.checked = false;
    }

    const low = document.getElementById('low');
    const medium = document.getElementById('medium');
    const high = document.getElementById('high');

    low.checked = false;
    medium.checked = false;
    high.checked = false;

    if (objectArray[number].priority === 'Alta') {
        high.checked = true;
    } else if (objectArray[number].priority === 'Media') {
        medium.checked = true;
    } else if (objectArray[number].priority === 'Baja') {
        low.checked = true;
    }

    const saveData = document.querySelector('#saveData');

    // Evento tras guardar los datos
    saveData.addEventListener('click', () => {

        // Asignamos los valores del formulario al objeto y luego metemos esos valores a las celdas de la tabla
        selectedRow.cells[1].innerText = document.getElementById("name").value;
        selectedRow.cells[2].innerText = document.getElementById("description").value;
        selectedRow.cells[3].innerText = document.getElementById("serial").value;

        if (active.checked) {
            selectedRow.cells[4].innerText = 'Activo';
        } else {
            selectedRow.cells[4].innerText = 'Inactivo';
        }

        if (low.checked) {
            selectedRow.cells[5].innerText = 'Baja';
        } else if (medium.checked) {
            selectedRow.cells[5].innerText = 'Media';
        } else if (high.checked) {
            selectedRow.cells[5].innerText = 'Alta';
        } else {
            selectedRow.cells[5].innerText = 'Baja';
        }

        objectArray[number].name = selectedRow.cells[1].innerText;
        objectArray[number].description = selectedRow.cells[2].innerText;
        objectArray[number].serial = selectedRow.cells[3].innerText;
        objectArray[number].status = selectedRow.cells[4].innerText;
        objectArray[number].priority = selectedRow.cells[5].innerText;

        formModal.classList.remove('showModal');
    });
}