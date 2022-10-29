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

window.onload = function () {
    generateTable(objectArray);
}

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
                        <td><button id="${i}" class="button" onclick="removeRow(this)">X</button onclick="editRow(this)"><button>Edit</button></td>
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

function editRow(button){
    
}

function editForm(row){
    
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
    }
    else{
        deleteTable();
        generateTable(objectArray);
    }
}