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
    const table = document.querySelector('tbody');
    for (let i = 0; i < data.length; i++) {
        let row = `<tr id="row${i}" class="row">
                        <td><button id="${i}" class="button" onclick="removeRow(this)">X</button><button id="showModal" onclick="editRow(this, ${i})">Edit</button></td>
						<td>${data[i].name}</td>
						<td>${data[i].description}</td>
						<td>${data[i].serial}</td>
                        <td>${data[i].status}</td>
                        <td>${data[i].priority}</td>
					</tr>`;
        table.innerHTML += row;
    }
}


function removeRow(button) {
    const number = button.id;
    const row = document.getElementById('row' + number);
    row.remove();
}

function editRow(button, number) {

    //Abrir el modal del formulario

    const formModal = document.querySelector('.form-modal');

    formModal.classList.add('mostrar');

    // Botón X para el formulario

    const closeBtn = document.querySelector('#closeBtn');

    // Evento para cerrar con el botón X
    closeBtn.addEventListener('click', () => {
        formModal.classList.remove('mostrar');
    });

    // Fila padre del botón edit

    selectedRow = button.parentElement.parentElement;

    // Valores al abrir el formulario
    objectArray[number].name = selectedRow.cells[1].innerHTML;
    objectArray[number].description = selectedRow.cells[2].innerHTML;
    objectArray[number].serial = selectedRow.cells[3].innerHTML;

    document.getElementById("name").value = objectArray[number].name;
    document.getElementById("description").value = objectArray[number].description;
    document.getElementById("serial").value = objectArray[number].serial;

    const active = document.getElementById('status');
    if (selectedRow.cells[4].innerHTML === 'Activo') {
        objectArray[number].status = 'Activo';
        active.checked = true;
    } else {
        objectArray[number].status = 'Inactivo';
        active.checked = false;
    }

    const low = document.getElementById('low');
    const medium = document.getElementById('medium');
    const high = document.getElementById('high');

    if (selectedRow.cells[5].innerHTML === 'Alta') {
        objectArray[number].priority = 'Alta';
        high.checked = true;
        medium.checked = false;
        low.checked = false;
    } else if (selectedRow.cells[5].innerHTML === 'Media') {
        objectArray[number].priority = 'Media';
        high.checked = false;
        medium.checked = true;
        low.checked = false;
    } else if (selectedRow.cells[5].innerHTML === 'Baja') {
        objectArray[number].priority = 'Baja';
        high.checked = false;
        medium.checked = false;
        low.checked = true;
    }

    // Botón guardar del formulario

    const saveData = document.querySelector('#saveData');

    // Evento tras guardar los datos
    saveData.addEventListener('click', () => {

        // Asignamos los valores del formulario al objeto y luego metemos esos valores a las celdas de la tabla
        objectArray[number].name = document.getElementById("name").value;
        objectArray[number].description = document.getElementById("description").value;
        objectArray[number].serial = document.getElementById("serial").value;

        selectedRow.cells[1].innerHTML = objectArray[number].name;
        selectedRow.cells[2].innerHTML = objectArray[number].description;
        selectedRow.cells[3].innerHTML = objectArray[number].serial;

        if (active.checked) {
            objectArray[number].status = 'Activo';
            selectedRow.cells[4].innerHTML = objectArray[number].status;
        } else {
            objectArray[number].status = 'Inactivo';
            selectedRow.cells[4].innerHTML = objectArray[number].status;
        }

        if (low.checked) {
            objectArray[number].priority = 'Bajo';
            selectedRow.cells[5].innerHTML = objectArray[number].priority;
        } else if (medium.checked) {
            objectArray[number].priority = 'Medio';
            selectedRow.cells[5].innerHTML = objectArray[number].priority;
        } else if (high.checked) {
            objectArray[number].priority = 'Alto';
            selectedRow.cells[5].innerHTML = objectArray[number].priority;
        } else {
            objectArray[number].priority = 'Bajo';
            selectedRow.cells[5].innerHTML = objectArray[number].priority;
        }

        formModal.classList.remove('mostrar');
    });
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
        deleteTable();
        generateTable(filteredObjects);
    } else {
        deleteTable();
        generateTable(objectArray);
    }
}