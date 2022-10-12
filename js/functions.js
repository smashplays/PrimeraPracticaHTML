const ObjectArray = [{
        "name": "Control de humo",
        "description": "Los sensores de humo son capaces de detectar el humo de \n un lugar a tiempo",
        "serial": "1582",
        "status": "Activo",
        "priority": "Alta"
    },
    {
        "name": "Medidor de presión",
        "description": "Los sensores medidores de presión son muy utilizados en el \n sector agricola para conocer, por ejemplo el flujo de agua \n de un lugar y para enviar una notificación a los equipos \n responsables cuando algo necesite ser corregido ",
        "serial": "1978",
        "status": "Inactivo",
        "priority": "Media"
    },
    {
        "name": "Control de la humedad",
        "description": "Permiten tener controlados factores como el clima o el \n almacenamiento de productos perecederos",
        "serial": "1956",
        "status": "Activo",
        "priority": "Alta"
    }
];

const filter = document.getElementById('filter');
const button = document.getElementsByClassName('button');

window.onload = function () {
    generateTable(ObjectArray);
}

function generateTable(data) {
    let element = document.querySelectorAll("tr td");
    Array.prototype.forEach.call( element, function( node ) {
        node.parentNode.removeChild( node );
    });
    const table = document.getElementById('table');

    for (let i = 0; i < data.length; i++) {
        let row = `<tr id="row${i}" class="row">
                        <td><button id="${i}" class="button" onclick="remove(this)">X</button></td>
						<td>${data[i].name}</td>
						<td>${data[i].description}</td>
						<td>${data[i].serial}</td>
                        <td>${data[i].status}</td>
                        <td>${data[i].priority}</td>
					</tr>`;
        table.innerHTML += row;
    }
}

function remove(button) {
    let number = button.id;
    let row = document.getElementById('row' + number);
    row.remove();
}

function filterTable(filter) {
    const search = filter.value.toLowerCase();

    if (search.length >= 3) {
        const filteredObjects = ObjectArray.filter((data) => {
            return (
                data.name.toLowerCase().includes(search) ||

                data.description.toLowerCase().includes(search) ||

                data.serial.toLowerCase().includes(search) ||

                data.status.toLowerCase().includes(search) ||

                data.priority.toLowerCase().includes(search)
            );
        });
        generateTable(filteredObjects);
    }
}

